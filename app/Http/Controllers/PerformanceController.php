<?php

namespace App\Http\Controllers;

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
        return view('performance.performance');
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
