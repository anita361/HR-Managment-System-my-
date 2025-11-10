<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use  App\Models\Employee;


class ShiftScheduleController extends Controller
{

    public function shiftScheduling()
    {
        $shifts = Shift::orderBy('created_at', 'desc')->get();
        $users = User::select('id', 'name')->get();
        $schedules = ShiftSchedule::orderBy('created_at', 'desc')->get();
        $shift_schedules = ShiftSchedule::with('employee', 'shift')->get();
        $employees = Employee::with(['designation', 'department'])->orderBy('name')->get();


        return view('employees.shiftscheduling', compact(
            'shifts',
            'schedules',
            'users',
            'shift_schedules',
            'employees'
        ));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        Log::info('ShiftSchedule store payload', $request->all());


        $validator = \Validator::make($request->all(), [
            'department_id'      => 'required|exists:departments,id',
            'employee_id'        => 'required|exists:employees,id',
            'date'               => 'required|string',
            'shift_id'           => 'required|exists:shifts,id',
            'min_start_time'     => ['required', 'date_format:H:i'],
            'start_time'         => ['required', 'date_format:H:i'],
            'max_start_time'     => ['required', 'date_format:H:i'],
            'min_end_time'       => ['required', 'date_format:H:i'],
            'end_time'           => ['required', 'date_format:H:i'],
            'max_end_time'       => ['required', 'date_format:H:i'],
            'break_time'         => 'required|string',
            // checkboxes: allow null; we will coerce
            'accept_extra_hours' => 'nullable',
            'publish'            => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }


        try {
            $parsedDate = null;
            $formats = ['d M, Y', 'd M Y', 'Y-m-d', 'd-m-Y', 'd/m/Y'];
            foreach ($formats as $fmt) {
                try {
                    $c = Carbon::createFromFormat($fmt, $request->date);
                    if ($c) {
                        $parsedDate = $c->toDateString();
                        break;
                    }
                } catch (\Exception $e) {
                }
            }
            if (! $parsedDate) {
                $parsedDate = Carbon::parse($request->date)->toDateString();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date' => 'Unrecognized date format. Use "11 Nov, 2025" or "2025-11-11".']);
        }


        $data = [
            'department_id'      => $request->department_id,
            'employee_id'        => $request->employee_id,
            'date'               => $parsedDate,
            'shift_id'           => $request->shift_id,
            'min_start_time'     => $request->min_start_time,
            'start_time'         => $request->start_time,
            'max_start_time'     => $request->max_start_time,
            'min_end_time'       => $request->min_end_time,
            'end_time'           => $request->end_time,
            'max_end_time'       => $request->max_end_time,
            'break_time'         => $request->break_time,
            'accept_extra_hours' => $request->has('accept_extra_hours') ? 1 : 0,
            'publish'            => $request->has('publish') ? 1 : 0,
        ];

        DB::beginTransaction();
        try {

            $schedule = ShiftSchedule::create($data);
            DB::commit();

            return redirect()->route('shiftscheduling.page')
                ->with('success', 'Schedule added successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();


            Log::error('Failed to create ShiftSchedule: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'payload' => $data,
            ]);


            $msg = config('app.debug') ? 'Failed to create schedule: ' . $e->getMessage() : 'Failed to create schedule. Check logs.';
            return redirect()->back()->withInput()->withErrors(['error' => $msg]);
        }
    }
    // Update schedule
    public function update(Request $request, $id)
    {
        $schedule = ShiftSchedule::findOrFail($id);

        $validated = $request->validate([
            'department_id'      => ['required', 'exists:departments,id'],
            'employee_id'        => ['required', 'exists:employees,id'],
            'date'               => ['required', 'date'],
            'shift_id'           => ['required', 'exists:shifts,id'],
            'min_start_time'     => ['required', 'string'],
            'start_time'         => ['required', 'string'],
            'max_start_time'     => ['required', 'string'],
            'min_end_time'       => ['required', 'string'],
            'end_time'           => ['required', 'string'],
            'max_end_time'       => ['required', 'string'],
            'break_time'         => ['required', 'string'],
            'accept_extra_hours' => 'nullable|boolean',
            'publish'            => 'nullable|boolean',
        ]);

        $validated['accept_extra_hours'] = $request->has('accept_extra_hours') ? 1 : 0;
        $validated['publish'] = $request->has('publish') ? 1 : 0;

        DB::beginTransaction();
        try {
            $schedule->update($validated);
            DB::commit();

            return redirect()->route('shiftscheduling.page')
                ->with('success', 'Schedule updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update schedule: ' . $e->getMessage()]);
        }
    }

    // Delete schedule
    public function destroy($id)
    {
        $schedule = ShiftSchedule::findOrFail($id);

        DB::beginTransaction();
        try {
            $schedule->delete();
            DB::commit();

            return redirect()->route('shiftscheduling.page')
                ->with('success', 'Schedule deleted successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete schedule: ' . $e->getMessage()]);
        }
    }
}
