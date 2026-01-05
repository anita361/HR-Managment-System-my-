<?php

namespace App\Http\Controllers;

use App\Models\Performance;

use App\Models\performanceIndicator;
use App\Models\performance_appraisal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Session;
use Auth;
use DB;

class PerformanceController extends Controller
{
    /** View Page */
    public function index()
    {
        $user_id = Auth::User()->user_id;
        Session::put('user_id', $user_id);

        $indicator   = DB::table('performance_indicator_lists')->get();
        $departments = DB::table('departments')->get();
        $performance_indicators = DB::table('users')
            ->join('performance_indicators', 'users.user_id', 'performance_indicators.user_id')
            ->select('users.*', 'performance_indicators.*')->get();
        return view('performance.performanceindicator', compact('indicator', 'departments', 'performance_indicators'));
    }



    /** Performance */
    public function performance()
    {
        // $userList = User::all();
        $userList = DB::table('users')->get();
        $position    = DB::table('position_types')->get();
        $department  = DB::table('departments')->get();
        return view('performance.performance', compact('userList', 'position', 'department',));
    }

    /** Store Performance Data */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'date_of_join' => 'nullable|date',
            'date_of_confirmation' => 'nullable|date',
            'previous_experience' => 'nullable|string|max:255',
            'ro_name' => 'nullable|string|max:255',
            'ro_designation' => 'nullable|string|max:255',
        ]);

        $lastEmployee = Performance::latest('id')->first();
        if ($lastEmployee) {
            $number = (int) str_replace('EMP', '', $lastEmployee->emp_id) + 1;
            $empId = 'EMP' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            $empId = 'EMP0001';
        }
        Performance::create([
            'name' => $request->name,
            'department' => $request->department,
            'designation' => $request->designation,
            'qualification' => $request->qualification,
            'emp_id' => $empId,
            'date_of_join' => $request->date_of_join,
            'date_of_confirmation' => $request->date_of_confirmation,
            'previous_experience' => $request->previous_experience,
            'ro_name' => $request->ro_name ?? '',
            'ro_designation' => $request->ro_designation ?? '',
        ]);

        return redirect()->back()->with('success', 'Data inserted successfully! Employee ID: ' . $empId);
    }





    public function prostore(Request $request)
    {

        $request->validate([
            'self_percentage_quality' => 'nullable|numeric',
            'self_percentage_tat' => 'nullable|numeric',
            'self_percentage_process' => 'nullable|numeric',
            'self_percentage_team' => 'nullable|numeric',
            'self_percentage_knowledge' => 'nullable|numeric',
            'self_percentage_reporting' => 'nullable|numeric',

            'ro_percentage_quality' => 'nullable|numeric',
            'ro_percentage_tat' => 'nullable|numeric',
            'ro_percentage_process' => 'nullable|numeric',
            'ro_percentage_team' => 'nullable|numeric',
            'ro_percentage_knowledge' => 'nullable|numeric',
            'ro_percentage_reporting' => 'nullable|numeric',
        ]);


        $selfPoints = [
            'quality' => ($request->self_percentage_quality ?? 0) * 30 / 100,
            'tat' => ($request->self_percentage_tat ?? 0) * 30 / 100,
            'process' => ($request->self_percentage_process ?? 0) * 10 / 100,
            'team' => ($request->self_percentage_team ?? 0) * 5 / 100,
            'knowledge' => ($request->self_percentage_knowledge ?? 0) * 5 / 100,
            'reporting' => ($request->self_percentage_reporting ?? 0) * 5 / 100,
        ];


        $roPoints = [
            'quality' => ($request->ro_percentage_quality ?? 0) * 30 / 100,
            'tat' => ($request->ro_percentage_tat ?? 0) * 30 / 100,
            'process' => ($request->ro_percentage_process ?? 0) * 10 / 100,
            'team' => ($request->ro_percentage_team ?? 0) * 5 / 100,
            'knowledge' => ($request->ro_percentage_knowledge ?? 0) * 5 / 100,
            'reporting' => ($request->ro_percentage_reporting ?? 0) * 5 / 100,
        ];


        Performance::create([

            'self_percentage_quality' => $request->self_percentage_quality ?? 0,
            'self_points_quality' => $selfPoints['quality'],

            'self_percentage_tat' => $request->self_percentage_tat ?? 0,
            'self_points_tat' => $selfPoints['tat'],

            'self_percentage_process' => $request->self_percentage_process ?? 0,
            'self_points_process' => $selfPoints['process'],

            'self_percentage_team' => $request->self_percentage_team ?? 0,
            'self_points_team' => $selfPoints['team'],

            'self_percentage_knowledge' => $request->self_percentage_knowledge ?? 0,
            'self_points_knowledge' => $selfPoints['knowledge'],

            'self_percentage_reporting' => $request->self_percentage_reporting ?? 0,
            'self_points_reporting' => $selfPoints['reporting'],


            'ro_percentage_quality' => $request->ro_percentage_quality ?? 0,
            'ro_points_quality' => $roPoints['quality'],

            'ro_percentage_tat' => $request->ro_percentage_tat ?? 0,
            'ro_points_tat' => $roPoints['tat'],

            'ro_percentage_process' => $request->ro_percentage_process ?? 0,
            'ro_points_process' => $roPoints['process'],

            'ro_percentage_team' => $request->ro_percentage_team ?? 0,
            'ro_points_team' => $roPoints['team'],

            'ro_percentage_knowledge' => $request->ro_percentage_knowledge ?? 0,
            'ro_points_knowledge' => $roPoints['knowledge'],

            'ro_percentage_reporting' => $request->ro_percentage_reporting ?? 0,
            'ro_points_reporting' => $roPoints['reporting'],


            'total_self_points' => array_sum($selfPoints),
            'total_ro_points' => array_sum($roPoints),
        ]);

        return redirect()->back()->with('success', 'Professional Excellence scores saved successfully!');
    }



    /** Performance Appraisal View Page */
    public function performanceAppraisal()
    {
        $users      = DB::table('users')->get();
        $indicator  = DB::table('performance_indicator_lists')->get();
        $appraisals = DB::table('users')
            ->join('performance_appraisals', 'users.user_id', 'performance_appraisals.user_id')
            ->select('users.*', 'performance_appraisals.*')->get();
        return view('performance.performanceappraisal', compact('users', 'indicator', 'appraisals'));
    }

    /** Save Record */
    public function saveRecordIndicator(Request $request)
    {
        $request->validate([
            'designation'        => 'required|string|max:255',
            'customer_eperience' => 'required|string|max:255',
            'marketing'          => 'required|string|max:255',
            'management'         => 'required|string|max:255',
            'administration'     => 'required|string|max:255',
            'presentation_skill' => 'required|string|max:255',
            'quality_of_Work'    => 'required|string|max:255',
            'efficiency'         => 'required|string|max:255',
            'integrity'          => 'required|string|max:255',
            'professionalism'    => 'required|string|max:255',
            'team_work'          => 'required|string|max:255',
            'critical_thinking'  => 'required|string|max:255',
            'conflict_management' => 'required|string|max:255',
            'attendance'         => 'required|string|max:255',
            'ability_to_meet_deadline' => 'required|string|max:255',
            'status'             => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $indicator = new performanceIndicator;
            $indicator->user_id            = $request->user_id;
            $indicator->designation        = $request->designation;
            $indicator->customer_eperience = $request->customer_eperience;
            $indicator->marketing          = $request->marketing;
            $indicator->management         = $request->management;
            $indicator->administration     = $request->administration;
            $indicator->presentation_skill = $request->presentation_skill;
            $indicator->quality_of_Work    = $request->quality_of_Work;
            $indicator->efficiency         = $request->efficiency;
            $indicator->integrity          = $request->integrity;
            $indicator->professionalism    = $request->professionalism;
            $indicator->team_work          = $request->team_work;
            $indicator->critical_thinking  = $request->critical_thinking;
            $indicator->conflict_management = $request->attendance;
            $indicator->attendance         = $request->attendance;
            $indicator->ability_to_meet_deadline = $request->ability_to_meet_deadline;
            $indicator->status             = $request->status;
            $indicator->save();

            DB::commit();
            flash()->success('Create new performance indicator successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Add performance indicator fail :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateIndicator(Request $request)
    {
        $validated = $request->validate([
            'id'                        => 'required|exists:performance_indicators,id',
            'designation'               => 'nullable|string|max:255',
            'customer_experience'       => 'nullable|string|max:255', // fixed typo
            'marketing'                 => 'nullable|string|max:255',
            'management'                => 'nullable|string|max:255',
            'administration'            => 'nullable|string|max:255',
            'presentation_skill'        => 'nullable|string|max:255',
            'quality_of_work'           => 'nullable|string|max:255', // unified name
            'efficiency'                => 'nullable|string|max:255',
            'integrity'                 => 'nullable|string|max:255',
            'professionalism'           => 'nullable|string|max:255',
            'team_work'                 => 'nullable|string|max:255',
            'critical_thinking'         => 'nullable|string|max:255',
            'conflict_management'       => 'nullable|string|max:255',
            'attendance'                => 'nullable|string|max:255',
            'ability_to_meet_deadline'  => 'nullable|string|max:255',
            'status'                    => 'required|in:Active,Inactive',
        ]);

        $updateData = [
            'designation' => $validated['designation'] ?? null,
            'customer_experience' => $validated['customer_experience'] ?? null,
            'marketing' => $validated['marketing'] ?? null,
            'management' => $validated['management'] ?? null,
            'administration' => $validated['administration'] ?? null,
            'presentation_skill' => $validated['presentation_skill'] ?? null,
            'quality_of_work' => $validated['quality_of_work'] ?? null,
            'efficiency' => $validated['efficiency'] ?? null,
            'integrity' => $validated['integrity'] ?? null,
            'professionalism' => $validated['professionalism'] ?? null,
            'team_work' => $validated['team_work'] ?? null,
            'critical_thinking' => $validated['critical_thinking'] ?? null,
            'conflict_management' => $validated['conflict_management'] ?? null,
            'attendance' => $validated['attendance'] ?? null,
            'ability_to_meet_deadline' => $validated['ability_to_meet_deadline'] ?? null,
            'status' => $validated['status'],
        ];

        DB::beginTransaction();
        try {
            $indicator = PerformanceIndicator::findOrFail($validated['id']);

            // Ensure model allows mass-assignment of these fields:
            Log::info('Model fillable: ' . json_encode($indicator->getFillable()));

            // try mass update
            $indicator->fill($updateData);
            $indicator->save();

            DB::commit();
            flash()->success('Performance indicator updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update failed: ' . $e->getMessage());
            Log::error('Payload: ' . json_encode($request->all()));
            flash()->error('Performance indicator update failed. Error: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    /** Delete Record */
    public function deleteIndicator(Request $request)
    {
        // quick logging for debugging
        \Log::info('deleteIndicator request', $request->only('_token', 'id'));

        $data = $request->validate([
            'id' => 'required|integer|exists:performance_indicators,id',
        ]);

        try {
            $indicator = PerformanceIndicator::find($data['id']);
            if ($indicator) {
                $indicator->delete();
                flash()->success('Performance indicator deleted successfully.');
            } else {
                flash()->error('Performance indicator not found.');
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting performance indicator: ' . $e->getMessage());
            flash()->error('Failed to delete performance indicator.');
        }

        return redirect()->back();
    }

    /** Save Record */
    public function saveRecordAppraisal(Request $request)
    {
        DB::beginTransaction();
        try {
            $appraisal = new performance_appraisal;
            $appraisal->user_id              = $request->user_id;
            $appraisal->date                = $request->date;
            $appraisal->name                = $request->name;
            $appraisal->customer_experience = $request->customer_experience;
            $appraisal->marketing           = $request->marketing;
            $appraisal->management          = $request->management;
            $appraisal->administration      = $request->administration;
            $appraisal->presentation_skill  = $request->presentation_skill;
            $appraisal->quality_of_Work     = $request->quality_of_work;
            $appraisal->efficiency          = $request->efficiency;
            $appraisal->integrity           = $request->integrity;
            $appraisal->professionalism     = $request->professionalism;
            $appraisal->team_work           = $request->team_work;
            $appraisal->critical_thinking   = $request->critical_thinking;
            $appraisal->conflict_management = $request->attendance;
            $appraisal->attendance          = $request->attendance;
            $appraisal->ability_to_meet_deadline = $request->ability_to_meet_deadline;
            $appraisal->status              = $request->status;
            $appraisal->save();

            DB::commit();
            flash()->success('Create new performance appraisal successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Add performance appraisal fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteAppraisal(Request $request)
    {
        try {
            performance_appraisal::destroy($request->id);
            flash()->success('Performance Appraisal deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Performance Appraisal delete fail :)');
            return redirect()->back();
        }
    }

    /** Update Appraisal */
    public function updateAppraisal(Request $request)
    {
        DB::beginTransaction();
        try {
            $update = [
                'id'                        => $request->id,
                'date'                      => $request->date,
                'customer_experience'       => $request->customer_experience,
                'marketing'                 => $request->marketing,
                'management'                => $request->management,
                'administration'            => $request->administration,
                'presentation_skill'        => $request->presentation_skill,
                'quality_of_Work'           => $request->quality_of_work,
                'efficiency'                => $request->efficiency,
                'integrity'                 => $request->integrity,
                'professionalism'           => $request->professionalism,
                'team_work'                 => $request->team_work,
                'critical_thinking'         => $request->critical_thinking,
                'conflict_management'       => $request->conflict_management,
                'attendance'                => $request->attendance,
                'ability_to_meet_deadline'  => $request->ability_to_meet_deadline,
                'status'                    => $request->status,
            ];
            performance_appraisal::where('id', $request->id)->update($update);
            DB::commit();
            flash()->success('Performance Appraisal deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Performance Appraisal fail :)');
            return redirect()->back();
        }
    }
}
