<?php

namespace App\Http\Controllers;

use App\Models\ProfileInformation;
use App\Models\module_permission;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Log;




use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\BankInformation;

class EmployeeController extends Controller
{
    /** All Employee Card View */

    public function cardAllEmployee(Request $request)
    {
        $users = DB::table('users')
            ->join('employees', 'users.user_id', 'employees.employee_id')
            ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
            ->get();
        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        return view('employees.allemployeecard', compact('users', 'userList', 'permission_lists'));
    }

    /** All Employee List */
    public function listAllEmployee()
    {
        $users = DB::table('users')
            ->join('employees', 'users.user_id', 'employees.employee_id')
            ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
            ->get();
        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        return view('employees.employeelist', compact('users', 'userList', 'permission_lists'));
    }

    /** Save Data Employee */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email',
            'birth_date'   => 'required|string|max:255',
            'gender'       => 'required|string|max:255',
            'employee_id'  => 'required|string|max:255',
            'line_manager' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::updateOrCreate(['email' => $request->email]);
            $employee->name         = $request->name;
            $employee->email        = $request->email;
            $employee->birth_date   = $request->birth_date;
            $employee->gender       = $request->gender;
            $employee->employee_id  = $request->employee_id;
            $employee->line_manager = $request->line_manager;
            $employee->save();

            $information = ProfileInformation::updateOrCreate(['user_id' => $request->employee_id]);
            $information->name         = $request->name;
            $information->user_id      = $request->employee_id;
            $information->email        = $request->email;
            $information->birth_date   = $request->birth_date;
            $information->gender       = $request->gender;
            $information->reports_to   = $request->line_manager;
            $information->save();

            for ($i = 0; $i < count($request->id_count); $i++) {
                $module_permissions = [
                    'employee_id'       => $request->employee_id,
                    'module_permission' => $request->permission[$i],
                    'id_count'          => $request->id_count[$i],
                    'read'              => $request->read[$i],
                    'write'             => $request->write[$i],
                    'create'            => $request->create[$i],
                    'delete'            => $request->delete[$i],
                    'import'            => $request->import[$i],
                    'export'            => $request->export[$i],
                ];
                DB::table('module_permissions')->insert($module_permissions);
            }

            DB::commit();
            flash()->success('Add new employee successfully :)');
            return redirect()->route('all/employee/card');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Add new employee fail :)');
            return redirect()->back();
        }
    }

    /** Edit Record */
    public function viewRecord($employee_id)
    {
        $permission = DB::table('employees')
            ->join('module_permissions', 'employees.employee_id', 'module_permissions.employee_id')
            ->select('employees.*', 'module_permissions.*')->where('employees.employee_id', $employee_id)->get();
        $employees = DB::table('employees')->where('employee_id', $employee_id)->get();
        return view('employees.edit.editemployee', compact('employees', 'permission'));
    }

    /** Update Record */

    public function updateRecord(Request $request)
{
    DB::beginTransaction();

    // ------------------------------------
    // 1. Update EMPLOYEE table
    // ------------------------------------
    $updateEmployee = [
        'name'         => $request->name,
        'email'        => $request->email,
        'birth_date'   => $request->birth_date,
        'gender'       => $request->gender,
        'employee_id'  => $request->employee_id,
        'line_manager' => $request->line_manager,
    ];

    Employee::where('id', $request->id)->update($updateEmployee);


    // ------------------------------------
    // 2. Update USERS table (only once)
    // ------------------------------------
    User::where('id', $request->id)->update([
        'name'         => $request->name,
        'email'        => $request->email,
        'line_manager' => $request->line_manager,
    ]);


    // ------------------------------------
    // 3. Update MODULE PERMISSIONS
    // ------------------------------------
    for ($i = 0; $i < count($request->id_permission); $i++) {

        module_permission::where('id', $request->id_permission[$i])
            ->update([
                'employee_id'       => $request->employee_id,
                'module_permission' => $request->permission[$i],
                'read'              => $request->read[$i],
                'write'             => $request->write[$i],
                'create'            => $request->create[$i],
                'delete'            => $request->delete[$i],
                'import'            => $request->import[$i],
                'export'            => $request->export[$i],
            ]);
    }


    // ------------------------------------
    // 4. Update PROFILE INFORMATION table
    // ------------------------------------
    ProfileInformation::updateOrCreate(
        ['user_id' => $request->id],   // match record
        [
            'name'       => $request->name,
            'email'      => $request->email,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'reports_to' => $request->line_manager,
        ]
    );


    // Commit process
    DB::commit();

    flash()->success('Updated record successfully :)');
    return redirect()->route('all/employee/card');
}










    /** Delete Record */
    public function deleteRecord($employee_id)
    {
        DB::beginTransaction();
        try {
            Employee::where('employee_id', $employee_id)->delete();
            module_permission::where('employee_id', $employee_id)->delete();

            DB::commit();
            flash()->success('Delete record successfully :)');
            return redirect()->route('all/employee/card');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Delete record fail :)');
            return redirect()->back();
        }
    }

    /** Employee Search */
    public function employeeSearch(Request $request)
    {
        $users = DB::table('users')
            ->join('employees', 'users.user_id', 'employees.employee_id')
            ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();

        // search by id
        if ($request->employee_id) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')->get();
        }
        // search by name
        if ($request->name) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')->get();
        }
        // search by name
        if ($request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }

        // search by name and id
        if ($request->employee_id && $request->name) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->get();
        }
        // search by position and id
        if ($request->employee_id && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }
        // search by name and position
        if ($request->name && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }
        // search by name and position and id
        if ($request->employee_id && $request->name && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }
        return view('employees.allemployeecard', compact('users', 'userList', 'permission_lists'));
    }

    /** List Search */
    public function employeeListSearch(Request $request)
    {
        $users = DB::table('users')
            ->join('employees', 'users.user_id', 'employees.employee_id')
            ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $userList         = DB::table('users')->get();

        // search by id
        if ($request->employee_id) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')->get();
        }

        // search by name
        if ($request->name) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')->get();
        }

        // search by name
        if ($request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }

        // search by name and id
        if ($request->employee_id && $request->name) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')->get();
        }

        // search by position and id
        if ($request->employee_id && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }

        // search by name and position
        if ($request->name && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }

        // search by name and position and id
        if ($request->employee_id && $request->name && $request->position) {
            $users = DB::table('users')
                ->join('employees', 'users.user_id', 'employees.employee_id')
                ->select('users.*', 'employees.birth_date', 'employees.gender', 'employees.line_manager')
                ->where('employee_id', 'LIKE', '%' . $request->employee_id . '%')
                ->where('users.name', 'LIKE', '%' . $request->name . '%')
                ->where('users.position', 'LIKE', '%' . $request->position . '%')->get();
        }
        return view('employees.employeelist', compact('users', 'userList', 'permission_lists'));
    }

    /** Employee profile */
    public function profileEmployee($user_id)
    {
        function getUserDetails($user_id)
        {
            return DB::table('users')
                ->leftJoin('personal_information as pi', 'pi.user_id', 'users.user_id')
                ->leftJoin('profile_information as pr', 'pr.user_id', 'users.user_id')
                ->leftJoin('user_emergency_contacts as ue', 'ue.user_id', 'users.user_id')
                ->select(
                    'users.*',
                    'pi.passport_no',
                    'pi.passport_expiry_date',
                    'pi.tel',
                    'pi.nationality',
                    'pi.religion',
                    'pi.marital_status',
                    'pi.employment_of_spouse',
                    'pi.children',
                    'pr.birth_date',
                    'pr.gender',
                    'pr.address',
                    'pr.country',
                    'pr.state',
                    'pr.pin_code',
                    'pr.phone_number',
                    'pr.department',
                    'pr.designation',
                    'pr.reports_to',
                    'ue.name_primary',
                    'ue.relationship_primary',
                    'ue.phone_primary',
                    'ue.phone_2_primary',
                    'ue.name_secondary',
                    'ue.relationship_secondary',
                    'ue.phone_secondary',
                    'ue.phone_2_secondary'
                )
                ->where('users.user_id', $user_id);
        }
        //  $user = User::find($user_id);

        $user = getUserDetails($user_id)->get();
        $users = getUserDetails($user_id)->first();
        $bankInformation  = BankInformation::where('user_id', $user_id)->first();
        $userfamilyinfo = DB::table('user_family_info')->where('user_id', $user_id)->get();
        $userEducation = DB::table('user_education')->where('user_id', $user_id)->get();
        $userExperiences = DB::table('user_experiences')->where('user_id', $user_id)->get();

        return view('employees.employeeprofile', compact('user_id', 'user', 'users', 'bankInformation', 'userfamilyinfo', 'userEducation', 'userExperiences'));
    }

    /** Page Departments */
    public function index()
    {
        $departments = DB::table('departments')->get();
        return view('employees.departments', compact('departments'));
    }

    /** Save Record */
    public function saveRecordDepartment(Request $request)
    {
        $request->validate([
            'department' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $department = department::where('department', $request->department)->first();
            if ($department === null) {
                $department = new department;
                $department->department = $request->department;
                $department->save();

                DB::commit();
                flash()->success('Add new department successfully :)');
                return redirect()->back();
            } else {
                DB::rollback();
                flash()->error('Add new department exits :)');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Add new department fail :)');
            return redirect()->back();
        }
    }

    /** Update Record */
    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            // update table departments
            $department = [
                'id'         => $request->id,
                'department' => $request->department,
            ];
            department::where('id', $request->id)->update($department);
            DB::commit();
            flash()->success('Updated record successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->success('Updated record fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecordDepartment(Request $request)
    {
        try {
            department::destroy($request->id);
            flash()->success('Department deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Department delete fail :)');
            return redirect()->back();
        }
    }

    /** Page Designations */


    public function designationsIndex()
    {
        $departments = Department::all();
        $designations = Designation::all();
        return view('employees.designations',  compact('departments', 'designations'));
    }



    /** Save designation record */

    public function saveRecordDesignations(Request $request)
    {
        //   dd($request->all());
        Designation::create([
            'designation_name' => $request->designation_name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->back()->with('success', 'Designation created.');
    }

    /** Update Record */
    public function updateRecordDesignations(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|integer|exists:designations,id',
            'designation_name' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id',
        ]);

        $designation = Designation::where('id', $validated['id'])->firstOrFail();

        $designation->update([
            'designation_name' => $validated['designation_name'],
            'department_id' => $validated['department_id'],
        ]);

        return redirect()->back()->with('success', 'Designation updated successfully.');
    }



    /** Delete Record */
    public function deleteRecordDesignations(Request $request)
    {
        $id = $request->input('id');


        $designation = Designation::find($id);
        if (!$designation) {
            return redirect()->back()->with('error', 'Designation not found.');
        }

        $designation->delete();

        return redirect()->back()->with('success', 'Designation deleted successfully.');
    }


    /** Page Time Sheet */
    public function timeSheetIndex()
    {
        return view('employees.timesheet');
    }

    /** Page Overtime */
    public function overTimeIndex()
    {
        return view('employees.overtime');
    }
}
