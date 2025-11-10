<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;


class OvertimeController extends Controller
{
    // GET: show page with table + modals
    public function overTimeIndex()
    {
        // Load employees for select and overtimes list
        $employees = Employee::orderBy('name')->get();
        $overtimes = Overtime::with(['employee', 'approver'])
            ->orderBy('ot_date', 'desc')
            ->get();

        // statistics (this month)
        $now = Carbon::now();
        $overtimeEmployeesCount = Overtime::whereYear('ot_date', $now->year)
            ->whereMonth('ot_date', $now->month)
            ->distinct()
            ->count('employee_id');

        $overtimeHours = Overtime::whereYear('ot_date', $now->year)
            ->whereMonth('ot_date', $now->month)
            ->sum('ot_hours');

        $pendingCount = Overtime::where('status', 'pending')->count();
        $rejectedCount = Overtime::where('status', 'rejected')->count();

        // $users = User::all();


        return view('employees.overtime', compact(
            'employees',
            'overtimes',
            'overtimeEmployeesCount',
            'overtimeHours',
            'pendingCount',
            'rejectedCount'
        ))->with('users', $employees);
    }

    // POST save new overtime
    // public function saveRecordOverTime(Request $request)
    // {
    //     // dd($request->all());

    //     $data = $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'ot_date'     => 'required|date',
    //         'ot_hours'    => 'required|numeric|min:0',
    //         'ot_type'     => 'nullable|string|max:255',
    //         'description' => 'nullable|string',
    //     ]);

    //     $data['ot_date'] = Carbon::parse($data['ot_date'])->toDateString();
    //     $data['status'] = 'pending';

    //     Overtime::create($data);

    //     return redirect()->back()->with('success', 'Overtime added successfully.');
    // }


    public function saveRecordOverTime(Request $request)
    {

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'ot_date'     => 'required|string',
            'ot_hours'    => 'required|numeric|min:0',
            'ot_type'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);


        $date = null;
        $formatsToTry = [
            'Y-m-d',
            'd/m/Y',
            'm/d/Y',
            'd-m-Y',
            'm-d-Y',
            'Y/m/d',
            'd M Y',
        ];

        foreach ($formatsToTry as $fmt) {
            try {
                $date = Carbon::createFromFormat($fmt, $validated['ot_date']);
                break;
            } catch (\Exception $e) {
            }
        }


        if (!$date) {
            try {
                $date = Carbon::parse($validated['ot_date']);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['ot_date' => 'Unable to parse date. Use a valid date format.']);
            }
        }

        $payload = [
            'employee_id' => $validated['employee_id'],
            'ot_date'     => $date->toDateString(),
            'ot_hours'    => $validated['ot_hours'],
            'ot_type'     => $validated['ot_type'] ?? null,
            'description' => $validated['description'] ?? null,
            'status'      => 'pending',
        ];

        DB::beginTransaction();
        try {
            Overtime::create($payload);
            DB::commit();
            return redirect()->back()->with('success', 'Overtime added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Overtime save failed: ' . $e->getMessage(), [
                'payload' => $payload,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Failed to save overtime. Please check logs or contact admin.');
        }
    }

    // POST update overtime (expects id in request)

    // public function updateRecordOverTime(Request $request)
    // {

    //     // dd($request->all());

    //     $data = $request->validate([
    //         'id'          => 'required|exists:overtimes,id',
    //         'employee_id' => 'required|exists:employees,id',
    //         'ot_date'     => 'required|date',
    //         'ot_hours'    => 'required|numeric|min:0',
    //         'ot_type'     => 'nullable|string|max:255',
    //         'description' => 'required|string',
    //         'status'      => 'nullable|in:pending,approved,rejected',
    //         'approved_by' => 'nullable|exists:employees,id',
    //     ]);

    //     // Format date properly
    //     $data['ot_date'] = Carbon::parse($data['ot_date'])->toDateString();

    //     $id = $data['id'];
    //     unset($data['id']); // Remove id so we can use it for find

    //     DB::beginTransaction();
    //     try {
    //         $overtime = Overtime::findOrFail($id);
    //         $overtime->update($data);

    //         DB::commit();
    //         return redirect()->back()->with('success', 'Overtime updated successfully.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Failed to update overtime: ' . $e->getMessage());
    //     }
    // }


    public function updateRecordOverTime(Request $request)
    {
        $data = $request->validate([
            'id'          => 'required|exists:overtimes,id',
            'employee_id' => 'required|exists:employees,id',
            'ot_date'     => 'required|date',
            'ot_hours'    => 'required|numeric|min:0',
            'ot_type'     => 'nullable|string|max:255',
            'description' => 'required|string',
            'status'      => 'nullable|in:pending,approved,rejected',
            'approved_by' => 'nullable|exists:employees,id',
        ]);


        try {
            $data['ot_date'] = Carbon::parse($data['ot_date'])->toDateString();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Invalid date format.');
        }

        $id = $data['id'];
        unset($data['id']);

        DB::beginTransaction();
        try {
            $overtime = Overtime::findOrFail($id);


            $overtime->fill($data);
            $overtime->save();

            DB::commit();
            return redirect()->back()->with('success', 'Overtime updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Overtime update failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Failed to update overtime.');
        }
    }

    // DELETE with id param
    public function deleteRecordOverTime(Request $request)
    {
        DB::beginTransaction();
        try {
            $overtime = Overtime::findOrFail($request->id);
            $overtime->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Overtime deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete overtime: ' . $e->getMessage());
        }
    }
}
