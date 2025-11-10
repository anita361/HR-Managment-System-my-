<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;

class TimeSheetController extends Controller
{
    /**
     * Show the timesheet page (list + modals).
     */
    public function timeSheetIndex()
    {


        $timesheets = Timesheet::orderBy('date', 'desc')->get();
        $employees = Employee::orderBy('name')->get();
        $users = User::all();


        if (class_exists(\App\Models\Project::class)) {
            $projects = \App\Models\Project::all();
        } else {
            $projects = Timesheet::select('project')
                ->whereNotNull('project')
                ->distinct()
                ->orderBy('project', 'asc')
                ->pluck('project');

            $users = User::all();
        }



        return view('employees.timesheet', compact('timesheets', 'projects', 'users'));
    }

    /**
     * Return a single timesheet record as JSON (used by AJAX for edit modal).
     */
    public function getRecordTimeSheets($id)
    {
        $ts = Timesheet::find($id);

        if (! $ts) {
            return response()->json(['error' => 'Timesheet not found.'], 404);
        }

        return response()->json($ts);
    }

    /**
     * Save a new timesheet record.
     */
    public function saveRecordTimeSheets(Request $request)
    {
        $data = $request->validate([
            'project'         =>  'required|string',
            'user_id'         => 'required|integer|exists:users,id',
            'deadline'        => 'nullable|string',
            'date'            => 'required|string',
            'total_hours'     => 'nullable|string',
            'remaining_hours' => 'nullable|string',
            'hours'           => 'required|string',
            'description'     => 'nullable|string',
        ]);

        try {
            $dateString = str_replace(',', '', trim($data['date']));
            try {
                $date = Carbon::createFromFormat('d M Y', $dateString)->startOfDay();
            } catch (\Exception $e) {
                $date = Carbon::parse($dateString);
            }

            $deadline = null;
            if (!empty($data['deadline'])) {
                $deadlineString = str_replace(',', '', trim($data['deadline']));
                try {
                    $deadline = Carbon::createFromFormat('d M Y', $deadlineString);
                } catch (\Exception $e) {
                    $deadline = Carbon::parse($deadlineString);
                }
            }

            $hours = (int) preg_replace('/\D+/', '', $data['hours'] ?? 0);
            $totalHours = isset($data['total_hours']) ? (int) preg_replace('/\D+/', '', $data['total_hours']) : null;
            $remainingHours = isset($data['remaining_hours']) ? (int) preg_replace('/\D+/', '', $data['remaining_hours']) : null;

            DB::beginTransaction();

            $timesheet = new TimeSheet();

            $timesheet->user_id         = $data['user_id'];
            $timesheet->project         = $data['project'];
            $timesheet->date            = $date->toDateString();
            $timesheet->deadline        = $deadline ? $deadline->toDateString() : null;
            $timesheet->total_hours     = $totalHours;
            $timesheet->remaining_hours = $remainingHours;
            $timesheet->hours           = $hours;
            $timesheet->description     = $data['description'] ?? null;
            $timesheet->save();

            DB::commit();

            return redirect()->back()->with('success', 'Timesheet saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Timesheet save error: ' . $e->getMessage(), [
                'exception_class' => get_class($e),
                'stack' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            if (config('app.debug')) {
                // DEV: show full exception in browser so you can fix quickly
                $msg = $e->getMessage() . "\n\n" . $e->getTraceAsString();
                return response()->make(nl2br(e($msg)), 500);
            }

            return redirect()->back()->withInput()->with('error', 'Unable to save timesheet. Check logs for details.');
        }
    }


    /**
     * Update an existing timesheet.
     */
    public function updateRecordTimeSheets(Request $request)
    {
        $rules = [
            'id' => 'required|exists:timesheets,id',
            'project' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'total_hours' => 'nullable|numeric|min:0',
            'remaining_hours' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'hours' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $ts = Timesheet::findOrFail($request->id);

            $ts->project = $request->project ?? $ts->project;
            $ts->deadline = $request->deadline ? Carbon::parse($request->deadline) : $ts->deadline;
            $ts->total_hours = $request->total_hours ?? $ts->total_hours;
            $ts->remaining_hours = $request->remaining_hours ?? $ts->remaining_hours;
            $ts->date = $request->date ? Carbon::parse($request->date) : $ts->date;
            $ts->hours = $request->hours ?? $ts->hours;
            $ts->description = $request->description ?? $ts->description;

            $ts->save();
            DB::commit();

            session()->flash('success', 'Timesheet updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Timesheet update error: ' . $e->getMessage());
            session()->flash('error', 'Failed to update timesheet.');
            return redirect()->back();
        }
    }

    /**
     * Delete a timesheet record.
     */
    public function deleteRecordTimeSheets(Request $request)
    {
        $rules = [
            'id' => 'required|exists:timesheets,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            session()->flash('error', 'Invalid request.');
            return redirect()->back();
        }

        try {
            $ts = Timesheet::findOrFail($request->id);
            $ts->delete();
            session()->flash('success', 'Timesheet deleted successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Timesheet delete error: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete timesheet.');
            return redirect()->back();
        }
    }
}
