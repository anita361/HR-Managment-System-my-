<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Timesheet;
use App\Models\User;

class TimeSheetController extends Controller
{
    /**
     * Display the Timesheet page.
     */
    public function timeSheetIndex()
    {
        $timesheets = DB::table('timesheets')
            ->leftJoin('users', 'timesheets.user_id', '=', 'users.id')
            ->select(
                'timesheets.*',
                'users.name as employee_name',
                'users.avatar as employee_avatar'
                
            )
            ->orderBy('timesheets.date', 'desc')
            ->get();

        $projects = [
            'Office Management',
            'Project Management',
            'Video Calling App',
            'Hospital Administration',
        ];

        $users = User::all();

        return view('employees.timesheet', compact('timesheets', 'projects', 'users'));
    }



    public function getRecordTimeSheets($id)
    {
        $timesheet = DB::table('timesheets')->find($id);

        if (!$timesheet) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        return response()->json($timesheet);
    }

    /**
     * Save new timesheet record.
     */
    public function saveRecordTimeSheets(Request $request)
    {

        $rules = [
            'project'         => 'required|string|max:255',
            'user_id'         => 'required',
            'deadline'        => 'nullable|string',
            'total_hours'     => 'required|numeric',
            'remaining_hours' => 'nullable|numeric',
            'date'            => 'required|string',
            'hours'           => 'required|numeric',
            'description'     => 'required|string',
        ];

        $messages = [
            'project.required'     => 'Project is required.',
            'total_hours.required' => 'Total hours is required.',
            'date.required'        => 'Date is required.',
            'hours.required'       => 'Hours is required.',
            'description.required' => 'Description is required.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();


        $parseDate = function (?string $value) {
            if (empty($value)) {
                return null;
            }

            $formatsToTry = [
                'd M, Y',
                'd M Y',
                'd-m-Y',
                'Y-m-d',
                'm/d/Y',
                'd/m/Y',
            ];

            foreach ($formatsToTry as $fmt) {
                try {
                    $c = Carbon::createFromFormat($fmt, $value);
                    if ($c !== false) {
                        return $c->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                }
            }


            try {
                $c = Carbon::parse($value);
                return $c->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        };


        $deadline = $parseDate($data['deadline'] ?? null);
        $date = $parseDate($data['date'] ?? null);


        if (is_null($date)) {
            $errorMsg = ['date' => ['The date format is invalid. Try like "07 Nov, 2025" or "2025-11-07".']];
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $errorMsg], 422);
            }
            return redirect()->back()->withErrors($errorMsg)->withInput();
        }

        try {
            DB::beginTransaction();

            DB::table('timesheets')->insert([
                'project'         => $data['project'],
                'user_id'         => $data['user_id'],
                'deadline'        => $deadline,
                'total_hours'     => (float) $data['total_hours'],
                'remaining_hours' => isset($data['remaining_hours']) ? (float) $data['remaining_hours'] : null,
                'date'            => $date,
                'hours'           => (float) $data['hours'],
                'description'     => $data['description'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Timesheet added successfully!'], 201);
            }

            return redirect()->back()->with('success', 'Timesheet added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save timesheet: ' . $e->getMessage(), [
                'exception' => $e->__toString(),
                'payload' => $request->all(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Something went wrong.'], 500);
            }

            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    /**
     * Update existing timesheet record.
     */
    public function updateRecordTimeSheets(Request $request)
    {
        try {
            DB::beginTransaction();

            DB::table('timesheets')->where('id', $request->id)->update([
                'project' => $request->project,
                'deadline' => $request->deadline,
                'total_hours' => $request->total_hours,
                'remaining_hours' => $request->remaining_hours,
                'date' => $request->date,
                'hours' => $request->hours,
                'description' => $request->description,
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Timesheet updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a timesheet record.
     */


    public function deleteRecordTimeSheets(Request $request)
    {
        Timesheet::findOrFail($request->id)->delete();
        return redirect()->back()->with('success', 'Timesheet deleted successfully.');
    }
}
