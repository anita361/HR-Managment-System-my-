<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ShiftController extends Controller
{
    /**
     * Display the main Shift Scheduling page (with modal etc.)
     */
    public function shiftScheduling()
    {
        $shifts = Shift::orderBy('created_at', 'desc')->get();
        return view('employees.shiftscheduling', compact('shifts'));
    }

    /**
     * Display list of shifts (for table list or AJAX table)
     */
    public function shiftList()
    {
        // Fetch all shifts, latest first
        // $shifts = Shift::orderBy('created_at', 'desc')->get();
         $shifts = Shift::orderBy('created_at', 'desc')->get();

        // Return the view with shifts
        return view('employees.shiftlist', compact('shifts'));
    }

    /**
     * Store a new shift record (Add Shift Modal)
     */
    public function store(Request $request)
    {
        // --- helpers with robust try/catch around Carbon parsing ---
        $tryParseTime = function ($value) {
            if (empty($value)) return null;
            $value = trim($value);

            // Accept multiple common formats (ordered)
            $formats = ['H:i', 'H:i:s', 'g:i A', 'h:i A', 'g:ia', 'h:ia'];

            foreach ($formats as $fmt) {
                try {
                    // createFromFormat can throw InvalidFormatException — catch below
                    $dt = Carbon::createFromFormat($fmt, $value);
                    if ($dt !== false && $dt instanceof Carbon) {
                        return $dt->format('H:i');
                    }
                } catch (\Exception $e) {
                    // ignore and try next format
                    continue;
                }
            }

            // Last-resort: strtotime()
            try {
                $ts = strtotime($value);
                if ($ts !== false && $ts !== -1) {
                    return Carbon::createFromTimestamp($ts)->format('H:i');
                }
            } catch (\Exception $e) {
                // ignore
            }

            // Log the unparseable time for debugging
            Log::warning('Could not parse time value', ['value' => $value]);

            return null;
        };

        $tryParseDate = function ($value) {
            if (empty($value)) return null;
            $value = trim($value);

            $formats = ['Y-m-d', 'd M, Y', 'd M Y', 'd/m/Y', 'm/d/Y'];

            foreach ($formats as $fmt) {
                try {
                    $dt = Carbon::createFromFormat($fmt, $value);
                    if ($dt !== false && $dt instanceof Carbon) {
                        return $dt->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // fallback to strtotime
            try {
                $ts = strtotime($value);
                if ($ts !== false && $ts !== -1) {
                    return Carbon::createFromTimestamp($ts)->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // ignore
            }

            Log::warning('Could not parse date value', ['value' => $value]);

            return null;
        };

        // Normalize incoming time/date inputs before validation
        $input = $request->all();
        $timeFields = ['min_start_time', 'start_time', 'max_start_time', 'min_end_time', 'end_time', 'max_end_time'];

        foreach ($timeFields as $f) {
            if (isset($input[$f]) && $input[$f] !== null && $input[$f] !== '') {
                $parsed = $tryParseTime($input[$f]);
                if ($parsed !== null) {
                    $request->merge([$f => $parsed]);
                } else {
                    // leave original - validation will catch invalid formats
                }
            }
        }

        if (isset($input['end_on']) && $input['end_on'] !== null && $input['end_on'] !== '') {
            $parsedDate = $tryParseDate($input['end_on']);
            if ($parsedDate !== null) {
                $request->merge(['end_on' => $parsedDate]);
            } else {
                // leave original - validator will fail if it's invalid
            }
        }

        // Validate
        $validated = $request->validate([
            'name'               => 'required|string|max:255',

            'min_start_time'     => 'nullable|date_format:H:i',
            'start_time'         => 'nullable|date_format:H:i',
            'max_start_time'     => 'nullable|date_format:H:i',

            'min_end_time'       => 'nullable|date_format:H:i',
            'end_time'           => 'nullable|date_format:H:i',
            'max_end_time'       => 'nullable|date_format:H:i',

            'break_time_minutes' => 'nullable|integer|min:0',

            'recurring'          => 'nullable|in:0,1',
            'repeat_every'       => 'nullable|integer|min:1|max:52',
            'days'               => 'nullable|array',
            'days.*'             => 'nullable|string',

            'end_on'             => 'nullable|date',
            'indefinite'         => 'nullable|in:0,1',

            'tag'                => 'nullable|string|max:255',
            'note'               => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $validated['name'],
                'min_start_time' => $validated['min_start_time'] ?? null,
                'start_time'     => $validated['start_time'] ?? null,
                'max_start_time' => $validated['max_start_time'] ?? null,
                'min_end_time'   => $validated['min_end_time'] ?? null,
                'end_time'       => $validated['end_time'] ?? null,
                'max_end_time'   => $validated['max_end_time'] ?? null,
                'break_time_minutes' => $validated['break_time_minutes'] ?? null,
                'recurring' => $request->input('recurring') == '1' ? 1 : 0,
                'repeat_every' => $validated['repeat_every'] ?? null,
                'days' => $request->input('days') ? $request->input('days') : null,
                'end_on' => $validated['end_on'] ?? null,
                'indefinite' => $request->input('indefinite') == '1' ? 1 : 0,
                'tag' => $validated['tag'] ?? null,
                'note' => $validated['note'] ?? null,
            ];

            Shift::create($data);

            DB::commit();
            return redirect()->back()->with('success', 'Shift added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to add shift', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Failed to add shift: ' . $e->getMessage());
        }
    }
    /**
     * Update an existing shift
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'min_start_time' => 'nullable|date_format:H:i',
            'start_time'     => 'nullable|date_format:H:i',
            'max_start_time' => 'nullable|date_format:H:i',

            'min_end_time'   => 'nullable|date_format:H:i',
            'end_time'       => 'nullable|date_format:H:i',
            'max_end_time'   => 'nullable|date_format:H:i',

            'break_time_minutes' => 'nullable|integer|min:0',

            'recurring' => 'nullable|in:on,1',
            'repeat_every' => 'nullable|integer|min:1',
            'days' => 'nullable|array',
            'days.*' => 'nullable|string',

            'end_on' => 'nullable|date',
            'indefinite' => 'nullable|in:on,1',

            'tag' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $shift = Shift::findOrFail($id);

            $shift->update([
                'name' => $validated['name'],
                'min_start_time' => $validated['min_start_time'] ?? null,
                'start_time' => $validated['start_time'] ?? null,
                'max_start_time' => $validated['max_start_time'] ?? null,

                'min_end_time' => $validated['min_end_time'] ?? null,
                'end_time' => $validated['end_time'] ?? null,
                'max_end_time' => $validated['max_end_time'] ?? null,

                'break_time_minutes' => $validated['break_time_minutes'] ?? null,
                'recurring' => isset($validated['recurring']),
                'repeat_every' => $validated['repeat_every'] ?? null,
                'days' => $validated['days'] ?? null,
                'end_on' => $validated['end_on'] ?? null,
                'indefinite' => isset($validated['indefinite']),
                'tag' => $validated['tag'] ?? null,
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Shift updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update shift: ' . $e->getMessage());
        }
    }

    /**
     * Delete a shift record
     */
    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->delete();

            return redirect()->back()->with('success', 'Shift deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete shift: ' . $e->getMessage());
        }
    }
}
