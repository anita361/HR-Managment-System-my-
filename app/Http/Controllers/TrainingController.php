<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; 

class TrainingController extends Controller
{
    /** page */
    public function index()
    {
        $trainings = DB::table('trainings')
            ->join('users', 'users.user_id', 'trainings.trainer_id')
            ->select('trainings.*', 'users.avatar', 'users.user_id')
            ->get();


        $users = \App\Models\User::all();
        $trainings = Training::all();
        return view('training.traininglist', compact('users', 'trainings'));
    }
    
//     public function index()
// {
//     $trainings = Training::with('trainer')->get(); // eager load trainer
//     $users = User::all();

//     return view('training.traininglist', compact('trainings', 'users'));
// }

    /**  Save record */



    public function addNewTraining(Request $request)
    {

        Training::create([
            'training_type' => $request->training_type,
            'trainer_id'    => $request->trainer_id,
            'employees_id' => $request->employees_id, // <- use directly
            'training_cost' => $request->training_cost,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'description'   => $request->description,
            'status'        => $request->status,
        ]);

        return redirect()->back()->with('success', 'Training Added Successfully!');
    }


    /** Update record */
    public function updateTraining(Request $request)
    {
        $rules = [
            'id'            => 'required|integer|exists:trainings,id',
            'trainer_id'    => 'required|integer|exists:users,id',
            'employees_id'  => 'required|integer|exists:users,id',
            'training_type' => 'required|string|max:255',
            'training_cost' => 'required|numeric|min:0',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'required|string|in:Active,Inactive',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }


        try {

            $start = Carbon::parse($request->start_date)->toDateString();
            $end = Carbon::parse($request->end_date)->toDateString();
        } catch (\Exception $ex) {

            try {
                $start = Carbon::createFromFormat('d M, Y', $request->start_date)->toDateString();
                $end = Carbon::createFromFormat('d M, Y', $request->end_date)->toDateString();
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['start_date' => 'Invalid date format'])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $payload = $request->only([
                'trainer_id',
                'employees_id',
                'training_type',
                'training_cost',
                'description',
                'status'
            ]);


            $payload['start_date'] = $start;
            $payload['end_date'] = $end;

            Training::where('id', $request->id)->update($payload);

            DB::commit();
            flash()->success('Updated Training successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('UpdateTraining error: ' . $e->getMessage());
            DB::rollback();
            flash()->error('Failed to update Training :)');
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
}
