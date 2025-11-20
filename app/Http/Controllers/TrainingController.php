<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainingController extends Controller
{
    /** page */
    public function index()
    {
        $trainings = DB::table('trainings')
            ->join('users', 'users.user_id', 'trainings.trainer_id')
            ->select('trainings.*', 'users.avatar', 'users.user_id')
            ->get();

        $users = DB::table('users')->get();
        return view('training.traininglist', compact('users', 'trainings'));
    }

    /**  Save record */
    // public function addNewTraining(Request $request)
    // {
    //     // dd($request->all());
    //     $data = $request->validate([
    //         'training_type' => 'required|string|max:255',
    //         'trainer_id'    => 'required|exists:trainers,id',
    //         'employees_id'  => 'required|exists:employees,id',
    //         'training_cost' => 'required|string|max:255',
    //         'start_date'    => 'required|date',
    //         'end_date'      => 'required|date|after_or_equal:start_date',
    //         'description'   => 'required|string|max:255',
    //         'status'        => 'required|string|max:255',
    //     ]);

    //     DB::beginTransaction();
    //     try {

    //         Training::create($data);

    //         DB::commit();
    //         flash()->success('Created new Training successfully :)');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         \Log::error($e);
    //         DB::rollback();
    //         flash()->error('Failed to add Training :)');
    //         return redirect()->back();
    //     }
    // }



    public function addNewTraining(Request $request)
    {
        $data = $request->validate([
            'training_type'   => 'required|string|max:255',
            'trainer_id'      => 'required|exists:trainers,id',
            'employees_id'    => 'required|array|min:1',
            'employees_id.*'  => 'required|exists:employees,id',
            'training_cost'   => 'required|numeric|min:0',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'description'     => 'nullable|string|max:1000',
            'status'          => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
           
            $training = Training::create([
                'training_type' => $data['training_type'],
                'trainer_id'    => $data['trainer_id'],
                'training_cost' => $data['training_cost'],
                'start_date'    => $data['start_date'],
                'end_date'      => $data['end_date'],
                'description'   => $data['description'] ?? null,
                'status'        => $data['status'],
            ]);

            
            if (!empty($data['employees_id'])) {
                $training->employees()->sync($data['employees_id']);
            }

            DB::commit();
            flash()->success('Created new Training successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('addNewTraining error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            DB::rollback();
            flash()->error('Failed to add Training :(');
            return redirect()->back()->withInput();
        }
    }

    /** Delete record */
    public function deleteTraining(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:trainings,id']);

        try {
            Training::destroy($request->id);
            flash()->success('Training deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error for debugging
            flash()->error('ailed to delete Training :)');
            return redirect()->back();
        }
    }

    /** Update record */
    public function updateTraining(Request $request)
    {
        $request->validate([
            'id'            => 'required',
            'trainer_id'    => 'required',
            'employees_id'  => 'required|string|max:255',
            'training_type' => 'required|string|max:255',
            'training_cost' => 'required|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required',
            'description'   => 'required|string|max:255',
            'status'        => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            Training::where('id', $request->id)->update($request->except('id'));

            DB::commit();
            flash()->success('Updated Training successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error for debugging
            DB::rollback();
            flash()->error('Failed to update Training :)');
            return redirect()->back();
        }
    }
}
