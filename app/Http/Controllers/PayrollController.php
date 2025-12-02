<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\SalaryExcel;
use App\Models\StaffSalary;
use App\Models\PayrollItem;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\PayrollOvertime;
use App\Models\Deduction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /** View Page Salary */
    public function salary()
    {
        $users            = DB::table('users')->join('staff_salaries', 'users.user_id', '=', 'staff_salaries.user_id')->select('users.*', 'staff_salaries.*')->get();
        $userList         = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        return view('payroll.employeesalary', compact('users', 'userList', 'permission_lists'));
    }

    /** Save Record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'salary'            => 'required|string|max:255',
            'basic'             => 'required|string|max:255',
            'da'                => 'required|string|max:255',
            'hra'               => 'required|string|max:255',
            'conveyance'        => 'required|string|max:255',
            'allowance'         => 'required|string|max:255',
            'medical_allowance' => 'required|string|max:255',
            'tds'               => 'required|string|max:255',
            'esi'               => 'required|string|max:255',
            'pf'                => 'required|string|max:255',
            'leave'             => 'required|string|max:255',
            'prof_tax'          => 'required|string|max:255',
            'labour_welfare'    => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $salary = StaffSalary::updateOrCreate(['user_id' => $request->user_id]);
            $salary->name              = $request->name;
            $salary->user_id           = $request->user_id;
            $salary->salary            = $request->salary;
            $salary->basic             = $request->basic;
            $salary->da                = $request->da;
            $salary->hra               = $request->hra;
            $salary->conveyance        = $request->conveyance;
            $salary->allowance         = $request->allowance;
            $salary->medical_allowance = $request->medical_allowance;
            $salary->tds               = $request->tds;
            $salary->esi               = $request->esi;
            $salary->pf                = $request->pf;
            $salary->leave             = $request->leave;
            $salary->prof_tax          = $request->prof_tax;
            $salary->labour_welfare    = $request->labour_welfare;
            $salary->save();

            DB::commit();
            flash()->success('Create new Salary successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Add Salary fail :)');
            return redirect()->back();
        }
    }

    /** Salary View Detail */
    public function salaryView($user_id)
    {
        $users = DB::table('users')
            ->join('staff_salaries', 'users.user_id', 'staff_salaries.user_id')
            ->join('profile_information', 'users.user_id', 'profile_information.user_id')
            ->select('users.*', 'staff_salaries.*', 'profile_information.*')
            ->where('staff_salaries.user_id', $user_id)->first();
        if (!empty($users)) {
            return view('payroll.salaryview', compact('users'));
        } else {
            flash()->warning('Please update information user :)');
            return redirect()->route('profile_user');
        }
    }

    /** Update Record */
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $update = [
                'id'                => $request->id,
                'name'              => $request->name,
                'salary'            => $request->salary,
                'basic'             => $request->basic,
                'da'                => $request->da,
                'hra'               => $request->hra,
                'conveyance'        => $request->conveyance,
                'allowance'         => $request->allowance,
                'medical_allowance' => $request->medical_allowance,
                'tds'               => $request->tds,
                'esi'               => $request->esi,
                'pf'                => $request->pf,
                'leave'             => $request->leave,
                'prof_tax'          => $request->prof_tax,
                'labour_welfare'    => $request->labour_welfare,
            ];

            StaffSalary::where('id', $request->id)->update($update);
            DB::commit();
            flash()->success('Salary updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Salary update fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function deleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            StaffSalary::destroy($request->id);
            DB::commit();
            flash()->success('Salary deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Salary deleted fail :)');
            return redirect()->back();
        }
    }

    /** Payroll Items */
    public function payrollItems()
    {
        $payrollItems = PayrollItem::all();
        $employees    = Employee::all();
        $overtimes = Overtime::all();
        $payroll_items = PayrollItem::orderBy('name')->get();
        $payrollOvertimes = PayrollOvertime::orderBy('name')->get();
        $deductions        = Deduction::orderBy('name')->get();

        return view('payroll.payrollitems', compact('payrollItems', 'employees', 'overtimes', 'payrollOvertimes',  'deductions'));
    }

    // Save Payroll Items
    public function storeAddition(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'required|string|max:255',
            'unit_calculation' => 'nullable|boolean',
            'unit_amount'      => 'nullable|numeric|min:0',
            'assignee'         => 'required|string',
            'employee_id'      => 'nullable|integer|exists:employees,id',
        ]);

        // Normalize checkbox (checkbox only present if checked)
        $validated['unit_calculation'] = $request->has('unit_calculation') ? 1 : 0;

        $item = PayrollItem::create([
            'name'             => $validated['name'],
            'category'         => $validated['category'],
            'unit_calculation' => $validated['unit_calculation'],
            'unit_amount'      => $validated['unit_amount'] ?? 0,
            'assignee'         => $validated['assignee'],
            'employee_id'      => $validated['employee_id'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Addition added successfully!');
    }

    /** Update Record */

    public function updateAddition(Request $request)
    {
        $validated = $request->validate([
            'id'               => 'required|exists:payroll_items,id', // ✅ corrected table name
            'name'             => 'required|string|max:255',
            'category'         => 'required|string|max:255',
            'unit_calculation' => 'required|in:0,1',
            'unit_amount'      => 'nullable|numeric|min:0|required_if:unit_calculation,1',
            'assignee'         => 'required|string|in:no,all,single',
            'employee_id'      => 'nullable|exists:employees,id|required_if:assignee,single',
        ]);

        try {
            DB::beginTransaction();

            $item = PayrollItem::findOrFail($validated['id']);

            $data = [
                'name'             => $validated['name'],
                'category'         => $validated['category'],
                'unit_calculation' => (int)$validated['unit_calculation'],
                'unit_amount'      => $validated['unit_amount'] ?? 0,
                'assignee'         => $validated['assignee'],
                'employee_id'      => $validated['assignee'] === 'single' ? $validated['employee_id'] : null,
            ];

            $item->update($data);

            DB::commit();

            return redirect()->back()->with('success', 'Addition updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Addition update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while updating the addition.');
        }
    }


    public function deleteAddition(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:payroll_items,id',
        ]);

        try {
            DB::beginTransaction();

            $item = PayrollItem::findOrFail($validated['id']);


            $item->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Addition deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Addition delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while deleting the addition.');
        }
    }

    // Save storeOvertime 
    public function storeOvertime(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'rate_type' => 'required|string|in:Daily Rate,Hourly Rate',
            'rate'      => 'required|numeric|min:0',
        ]);

        PayrollOvertime::create([
            'name'      => $request->name,
            'rate_type' => $request->rate_type,
            'rate'      => $request->rate,
        ]);

        return redirect()->back()->with('success', 'PayrollOvertime added successfully!');
    }


    public function updateOvertime(Request $request)
    {
        $data = $request->validate([
            'id'        => 'required|exists:payroll_overtimes,id',
            'name'      => 'required|string|max:255',
            'rate_type' => 'required|string|max:255',
            'rate'      => 'required|numeric|min:0',
        ]);

        try {
            $overtime = PayrollOvertime::findOrFail($data['id']);
            $overtime->name = $data['name'];
            $overtime->rate_type = $data['rate_type'];
            $overtime->rate = $data['rate'];
            $overtime->save();

            flash()->success('Overtime record updated successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Overtime update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->all()
            ]);

            flash()->error('Something went wrong while updating the record.');
            return redirect()->back()->withInput();
        }
    }











    // Save storeDeduction 

    public function storeDeduction(Request $request)
    {
        // Validate input. Note: unit_calculation is handled via $request->has().
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'unit_calculation' => 'nullable',
            'unit_amount'      => 'nullable|numeric|min:0',
            'assignee'         => 'required|in:none,all,single',
            'employee_id'      => 'nullable|integer|exists:employees,id',
        ]);

        // Normalize data for saving
        $data = [
            'name'             => $validated['name'],
            'unit_calculation' => $request->has('unit_calculation') ? true : false,
            'unit_amount'      => $validated['unit_amount'] ?? null,
            'assignee'         => $validated['assignee'],
            'employee_id'      => $validated['employee_id'] ?? null,
        ];

        if ($validated['assignee'] === 'single' && isset($validated['employee_id'])) {
            $data['employee_id'] = $validated['employee_id'];
        }


        // Store in database
        Deduction::create($data);

        // Redirect with success message
        return redirect()->back()->with('success', 'Deduction created successfully.');
    }

    public function editDeduction($id)
    {
        $deduction = Deduction::findOrFail($id);
        $employees = Employee::orderBy('name')->get();

        // If you want JSON for AJAX edit:
        return response()->json([
            'deduction' => $deduction,
            'employees' => $employees,
        ]);
    }

    public function updateDeduction(Request $request, $id)
    {
        $deduction = Deduction::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'unit_calculation' => 'sometimes|boolean',
            'unit_amount' => 'nullable|numeric|min:0',
            'assignee' => 'required|in:none,all,single',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        if ($data['assignee'] !== 'single') {
            $data['employee_id'] = null;
        }

        $data['unit_calculation'] = $request->has('unit_calculation') ? true : false;

        $deduction->update($data);

        return redirect()->back()->with('success', 'Deduction updated.');
    }

    public function destroyDeduction($id)
    {
        $deduction = Deduction::findOrFail($id);
        $deduction->delete();

        return redirect()->back()->with('success', 'Deduction deleted.');
    }





    /** Report PDF */
    public function reportPDF(Request $request)
    {
        $user_id = $request->user_id;

        $users = DB::table('users')
            ->join('staff_salaries', 'users.user_id', 'staff_salaries.user_id')
            ->join('profile_information', 'users.user_id', 'profile_information.user_id')
            ->select('users.*', 'staff_salaries.*', 'profile_information.*')
            ->where('staff_salaries.user_id', $user_id)
            ->first();

        $pdf = Pdf::loadView('report_template.salary_pdf', compact('users'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('ReportDetailSalary.pdf');
    }

    /** Export Excel */
    public function reportExcel(Request $request)
    {
        $user_id = $request->user_id;
        $users = DB::table('users')
            ->join('staff_salaries', 'users.user_id', 'staff_salaries.user_id')
            ->join('profile_information', 'users.user_id', 'profile_information.user_id')
            ->select('users.*', 'staff_salaries.*', 'profile_information.*')
            ->where('staff_salaries.user_id', $user_id)->get();
        return Excel::download(new SalaryExcel($user_id), 'ReportDetailSalary' . '.xlsx');
    }
}
