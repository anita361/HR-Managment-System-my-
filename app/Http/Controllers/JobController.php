<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplyForJob;

use App\Models\Category;
use App\Models\Department;
use App\Models\Question;
use App\Models\AddJob;
use App\Models\ExperienceLevel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Candidate;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /** Job List */
    public function jobList()
    {
        $job_list = DB::table('add_jobs')->get();
        return view('job.joblist', compact('job_list'));
    }

    /** Job View */
    public function jobView($id)
    {
        $post = ApplyForJob::findOrFail($id);
        $post->increment('count');
        $job_view = $post;
        return view('job.jobview', compact('job_view'));
    }

    /** Users Dashboard */
    public function userDashboard()
    {
        $job_list   = DB::table('add_jobs')->get();
        return view('job.userdashboard', compact('job_list'));
    }

    /** Jobs Dashboard */
    public function jobsDashboard()
    {
        return view('job.jobsdashboard');
    }

    /** User All Job */
    public function userDashboardAll()
    {
        return view('job.useralljobs');
    }

    /** Save Job */
    public function userDashboardSave()
    {
        return view('job.savedjobs');
    }

    /** Applied Job */
    public function userDashboardApplied()
    {
        return view('job.appliedjobs');
    }

    /** Inter Viewing Job*/
    public function userDashboardInterviewing()
    {
        return view('job.interviewing');
    }

    /** Inter viewing Job*/
    public function userDashboardOffered()
    {
        return view('job.offeredjobs');
    }

    /** Visited Job */
    public function userDashboardVisited()
    {
        return view('job.visitedjobs');
    }

    /** Archived Job*/
    public function userDashboardArchived()
    {
        return view('job.visitedjobs');
    }

    /** Jobs */
    public function Jobs()
    {
        $department = DB::table('departments')->get();
        $type_job   = DB::table('type_jobs')->get();
        $job_list   = DB::table('add_jobs')->get();
        return view('job.jobs', compact('department', 'type_job', 'job_list'));
    }

    /** Save Record */
    public function JobsSaveRecord(Request $request)
    {
        $request->validate([
            'job_title'       => 'required|string|max:255',
            'department'      => 'required|string|max:255',
            'job_location'    => 'required|string|max:255',
            'no_of_vacancies' => 'required|string|max:255',
            'experience'      => 'required|string|max:255',
            'age'             => 'required|integer',
            'salary_from'     => 'required|string|max:255',
            'salary_to'       => 'required|string|max:255',
            'job_type'        => 'required|string|max:255',
            'status'          => 'required|string|max:255',
            'start_date'      => 'required',
            'expired_date'    => 'required',
            'description'     => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            AddJob::create($request->all());
            flash()->success('Job created successfully :)');
        });
        return redirect()->back();
    }

    /** Update Ajax Status */
    public function jobTypeStatusUpdate(Request $request)
    {

        $job_type = $request->only(['full_time', 'part_time', 'internship', 'temporary', 'remote', 'others']);
        $job_type = array_filter($job_type);
        $job_type = reset($job_type);


        if ($job_type) {
            AddJob::where('id', $request->id_update)->update(['job_type' => $job_type]);
            flash()->success('Updated successfully :)');
            return Response::json(['success' => $job_type], 200);
        }

        flash()->error('Update failed. No job type provided.');
        return Response::json(['error' => 'No job type provided'], 400);
    }

    /** Job Applicants */
    public function jobApplicants($job_title)
    {
        $apply_for_jobs = DB::table('apply_for_jobs')->where('job_title', $job_title)->get();
        return view('job.jobapplicants', compact('apply_for_jobs'));
    }

    /** Download */
    public function downloadCV($id)
    {
        $cv_uploads = DB::table('apply_for_jobs')->where('id', $id)->first();
        $pathToFile = public_path("assets/images/{$cv_uploads->cv_upload}");
        return \Response::download($pathToFile);
    }

    /** Job Details */
    public function jobDetails($id)
    {
        $job_view_detail = DB::table('add_jobs')->where('id', $id)->get();
        return view('job.jobdetails', compact('job_view_detail'));
    }





    /** apply Job SaveRecord */

    public function applyJobSaveRecord(Request $request)
    {
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
            'email'     => 'required|string|email|max:255',
            'message'   => 'required|string|max:1000',
            'cv_upload' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('cv_upload')) {
                // ensure directory exists
                $destinationPath = public_path('uploads/cvs');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // safe unique filename
                $file = $request->file('cv_upload');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);
            } else {
                // unexpected: validation should have prevented this
                throw new \Exception('CV file missing');
            }

            ApplyForJob::create([
                'job_title' => $validatedData['job_title'],
                'name'      => $validatedData['name'],
                'phone'     => $validatedData['phone'],
                'email'     => $validatedData['email'],
                'message'   => $validatedData['message'],
                'cv_upload' => 'uploads/cvs/' . $filename,
            ]);

            DB::commit();

            // Using whatever flash helper you had:
            flash()->success('Job application submitted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Apply job save error: ' . $e->getMessage());
            flash()->error('Job application submission failed :(');
            return redirect()->back()->withInput();
        }
    }







    // public function applyJobSaveRecord(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'job_title' => 'required|string|max:255',
    //         'name'      => 'required|string|max:255',
    //         'phone'     => 'required|string|max:255',
    //         'email'     => 'required|string|email|max:255',
    //         'message'   => 'required|string|max:255',
    //         'cv_upload' => 'required|file|mimes:pdf,doc,docx|max:2048', // Validate file type and size
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         // Upload file
    //         $cv_uploads = time() . '.' . $request->file('cv_upload')->extension();
    //         $request->file('cv_upload')->move(public_path('assets/images'), $cv_uploads);

    //         // Save application
    //         ApplyForJob::create([
    //             'job_title' => $validatedData['job_title'],
    //             'name'      => $validatedData['name'],
    //             'phone'     => $validatedData['phone'],
    //             'email'     => $validatedData['email'],
    //             'message'   => $validatedData['message'],
    //             'cv_upload' => $cv_uploads,
    //         ]);

    //         DB::commit();
    //         flash()->success('Job application submitted successfully :)');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         flash()->error('Job application submission failed :)');
    //         return redirect()->back()->withInput();
    //     }
    // }

    /** applyJobUpdateRecord */

    public function applyJobUpdateRecord(Request $request)
    {
        // dd($request->all());
        // validate incoming request
        $validated = $request->validate([
            'id'              => 'required',
            'job_title'       => 'required|string|max:255',
            'department'      => 'required|string|max:255',
            'job_location'    => 'required|string|max:255',
            'no_of_vacancies' => 'required|integer',
            'experience'      => 'required|string|max:255',
            'age'             => 'required|integer',
            'salary_from'     => 'required|numeric',
            'salary_to'       => 'required|numeric',
            'job_type'        => 'required|string|max:255',
            'status'          => 'required|string|max:255',
            'start_date'      => 'required|string',
            'expired_date'    => 'required|string',
            'description'     => 'required|string',
        ]);


        DB::beginTransaction();

        try {
            // find the model
            $job = AddJob::findOrFail($validated['id']);

            try {
                $job->start_date = Carbon::createFromFormat('d M, Y', $validated['start_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                $job->start_date = $validated['start_date'];
            }

            try {
                $job->expired_date = Carbon::createFromFormat('d M, Y', $validated['expired_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                $job->expired_date = $validated['expired_date'];
            }
            $other = collect($validated)->except(['id', 'start_date', 'expired_date'])->toArray();
            $job->fill($other);
            $job->save();

            DB::commit();

            flash()->success('Job details updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            flash()->error('Failed to update job details :)');
            return redirect()->back()->withInput();
        }
    }
    /**applyJobDeleteRecord  */

    public function applyJobDeleteRecord(Request $request)
    {
        //   dd($request->all());
        $data = $request->validate([
            'id' => 'required|integer',
        ]);

        $job = AddJob::find($data['id']);

        if (! $job) {
            return redirect()->back()->with('error', 'Record not found');
        }

        $job->delete();

        return redirect()->back()->with('success', 'Job deleted successfully');
    }



    /** manage Resumes */
    // public function manageResumesIndex()
    // {
    //     $department    = DB::table('departments')->get();
    //     $type_job      = DB::table('type_jobs')->get();
    //     $manageResumes = DB::table('add_jobs')
    //         ->join('apply_for_jobs', 'apply_for_jobs.job_title', 'add_jobs.job_title')
    //         ->select('add_jobs.*', 'apply_for_jobs.*')
    //         ->get();
    //     return view('job.manageresumes', compact('manageResumes', 'department', 'type_job'));
    // }


    public function manageResumesIndex()
    {

        $departments = DB::table('departments')->get();
        $jobTypes = DB::table('type_jobs')->get();

        $manageResumes = DB::table('add_jobs')
            ->join('users', 'users.id', '=', 'add_jobs.id')
            ->select(
                'add_jobs.id as job_id',
                'add_jobs.job_title',
                'add_jobs.department',
                'add_jobs.start_date',
                'add_jobs.expired_date',
                'add_jobs.job_type',
                'add_jobs.status',
                'users.id as user_id',
                'users.name',
                'users.avatar'
            )
            ->get();

        return view('job.manageresumes', [
            'manageResumes' => $manageResumes,
            'department' => $departments,
            'type_job' => $jobTypes
        ]);
    }

    /** shortlist candidates */
    public function shortlistCandidatesIndex()
    {
        $jobs = AddJob::join('users', 'users.id', '=', 'add_jobs.id')
            ->select(
                'add_jobs.*',
                'users.name',
                'users.avatar'
            )
            ->get();

        return view('job.shortlistcandidates', compact('jobs'));
    }

    /** Interview Questions */
    public function interviewQuestionsIndex()
    {
        $question    = DB::table('questions')->get();
        $category = Category::all();
        $department = Department::all();
        $answer      = DB::table('answers')->get();
        return view('job.interviewquestions', compact('category', 'department', 'answer', 'question'));
    }

    /** Interview Questions Save */
    public function categorySave(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255|unique:categories,category',
        ]);

        DB::beginTransaction();

        try {
            $category = new Category();
            $category->category = $request->category;
            $category->save();

            DB::commit();
            flash()->success('New Category created successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to add Category :)');
            return redirect()->back()->withInput();
        }
    }

    public function interviewQuestionsStore(Request $request)
    {
        try {
            // Normalize video link if missing scheme
            if ($request->filled('video_link') && !preg_match('/^https?:\/\//', $request->video_link)) {
                $request->merge([
                    'video_link' => 'https://' . $request->video_link,
                ]);
            }

            $validated = $request->validate([
                'category' => 'required|string',
                'department' => 'required|string',
                'questions' => 'required|string',
                'option_a' => 'required|string',
                'option_b' => 'required|string',
                'option_c' => 'required|string',
                'option_d' => 'required|string',
                'answer' => 'required|in:A,B,C,D',
                'code_snippets' => 'nullable|string',
                'answer_explanation' => 'nullable|string',
                'video_link' => 'nullable|url',
                // If you want to allow xlsx, pdf files:
                'image_to_question' => 'nullable|mimes:jpeg,png,jpg,gif,svg,xlsx,pdf|max:2048',
            ]);

            if ($request->hasFile('image_to_question')) {
                $file = $request->file('image_to_question');
                $filename = time() . '_' . $file->getClientOriginalName();
                $validated['image_to_question'] = $file->storeAs('uploads/questions', $filename, 'public');
            }

            Question::create($validated);

            return redirect()->back()->with('success', 'Question added successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing interview question: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    /** Question Update */
    public function interviewQuestionsUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:questions,id',
            'category' => 'required|string',
            'department' => 'required|string',
            'questions' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'answer' => 'required|in:A,B,C,D',
            'code_snippets' => 'nullable|string',
            'answer_explanation' => 'nullable|string',
            'video_link' => 'nullable|url',
            'image_to_question' => 'nullable|image|max:2048',
        ]);

        // whitelist the exact DB columns you want to update
        $allowed = [
            'category',
            'department',
            'questions',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'answer',
            'code_snippets',
            'answer_explanation',
            'video_link'
        ];

        $data = array_intersect_key($request->only($allowed), array_flip($allowed));

        if ($request->hasFile('image_to_question')) {
            $image = $request->file('image_to_question');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/questions'), $imageName);
            $data['image_to_question'] = $imageName;
        }

        DB::table('questions')->where('id', $request->id)->update($data);

        return back()->with('success', 'Question updated successfully!');
    }

    /** Delete Question */
    public function interviewQuestionsDelete(Request $request)
    {
        // dd($request->all());
        try {

            $question = Question::findOrFail($request->id);

            $imagePath = 'assets/images/question/' . $question->image_to_question;

            if ($question->image_to_question && file_exists($imagePath)) {
                unlink($imagePath);
            }

            $question->delete();

            flash()->success('Question deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Failed to delete question :)');
            return redirect()->back();
        }
    }

    /** Offer Approvals */
    public function offerApprovalsIndex()
    {
        return view('job.offerapprovals');
    }

    /** Experience Level */
    public function experienceLevelIndex()
    {
        $experienceLevels = ExperienceLevel::all();

        return view('job.experiencelevel', compact('experienceLevels'));
    }
    /** Experience Store */

    public function experiencestore(Request $request)
    {

        $request->validate([
            'level_name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);


        ExperienceLevel::create([
            'level_name' => $request->level_name,
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Experience Level added successfully!');
    }
    /** Experience Update */

    public function experienceUpdate(Request $request, $id)
    {
        $request->validate([
            'level_name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $level = ExperienceLevel::findOrFail($id);
        $level->update([
            'level_name' => $request->level_name,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Experience Level updated successfully!');
    }

    // Delete experience level
    public function experienceDelete($id)
    {
        $level = ExperienceLevel::findOrFail($id);
        $level->delete();

        return redirect()->back()->with('success', 'Experience Level deleted successfully!');
    }

    /** Candidates */
    public function candidatesIndex()
    {
        $users = User::all();
        $candidates = Candidate::all();

        return view('job.candidates', compact('users', 'candidates'));
    }




    // Store new candidate

    public function Candidatesstore(Request $request)
    {

        $data = $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'email'        => 'required|email|unique:candidates,email',
            'employee_id'  => 'required|exists:users,id',
            'created_date' => 'required|string',
            'phone'        => 'nullable|string|max:20',
        ]);


        try {

            $parsed = Carbon::createFromFormat('d M, Y', $data['created_date']);
            $data['created_date'] = $parsed->format('Y-m-d');
        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->withErrors(['created_date' => 'Invalid date format. Please use the date-picker.']);
        }


        Candidate::create($data);

        return redirect()->route('page/candidates')->with('success', 'Candidate added successfully!');
    }

    public function CandidatesEdit($id)
    {
        // dd($request->all());
        $candidate = Candidate::findOrFail($id);
        return response()->json($candidate);
    }

    public function CandidatesUpdate(Request $request)
    {
        $data = $request->validate([
            'id'           => 'required|integer|exists:candidates,id',
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'nullable|string|max:255',
            'email'        => 'required|email|max:255',
            'employee_id'  => 'nullable|string|max:100',
            'created_date' => 'nullable|string',
            'phone'        => 'nullable|string|max:30',
        ]);

        Candidate::findOrFail($data['id'])->update($data);

        return redirect()->back()->with('success', 'Candidate updated successfully.');
    }


    // Delete candidate
    public function Candidatedelete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $candidate = Candidate::find($request->id);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found.'], 404);
        }

        $candidate->delete();

        return response()->json(['success' => 'Candidate deleted successfully.']);
    }

    /** Schedule Timing */
    public function scheduleTimingIndex()
    {
        return view('job.scheduletiming');
    }

    /** Aptitude Result */
    public function aptituderesultIndex()
    {
        return view('job.aptituderesult');
    }
}
