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
            'employees_id' => $request->employees_id, 
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
    //  dd($request->all());
    $request->validate([
        'id'            => 'required|integer|exists:trainings,id',
        'trainer_id'    => 'required|integer|exists:users,id',
        'employees_id'  => 'required',
        'training_type' => 'required|string|max:255',
        'training_cost' => 'required|numeric|min:0',
        'start_date'    => 'required|string',
        'end_date'      => 'required|string',
        'description'   => 'nullable|string|max:1000',
        'status'        => 'required|string|in:Active,Inactive',
    ]);

    $parseDate = fn($d) => Carbon::parse($d)->toDateString();

    $start = $parseDate($request->start_date);
    $end   = $parseDate($request->end_date);

    if (Carbon::parse($end)->lt(Carbon::parse($start))) {
        return redirect()->back()->withErrors(['end_date' => 'End date must be same or after start'])->withInput();
    }

    $employees = is_array($request->employees_id) 
        ? $request->employees_id 
        : explode(',', $request->employees_id);

    $payload = $request->only([
        'trainer_id', 'training_type', 'training_cost', 'description', 'status'
    ]);
    $payload['employees_id'] = count($employees) > 1 ? json_encode($employees) : intval($employees[0]);
    $payload['start_date'] = $start;
    $payload['end_date']   = $end;

    DB::transaction(fn() => Training::where('id', $request->id)->update($payload));

    flash()->success('Updated Training successfully :)');
    return redirect()->back();
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
