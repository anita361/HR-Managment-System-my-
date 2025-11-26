<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estimates;
use App\Models\User;
use DB;

class ExpenseReportsController extends Controller
{

    public function index()
    {
        $expenses = DB::table('expenses')
            ->leftJoin('users', 'users.id', '=', 'expenses.purchased_by')
            ->select(
                'expenses.id',
                'expenses.item_name',
                'expenses.purchase_from',
                'expenses.purchase_date',
                'expenses.amount',
                'expenses.paid_by',
                'expenses.status',
                'expenses.purchased_by',
                'users.name as purchaser_name',
                'users.avatar as purchaser_avatar'
            )
            ->orderBy('expenses.id', 'desc')
            ->get();

        return view('reports.expensereport', compact('expenses'));
    }

    // view page
    public function invoiceReports()
    {
        $invoices = Estimates::all();

        return view('reports.invoicereports', compact('invoices'));
    }

    // daily report page
    public function dailyReport()
    {
        return view('reports.dailyreports');
    }

    // leave reports page
    public function leaveReport()
    {
        $leaves = DB::table('leaves_admins')
            ->join('users', 'users.user_id', 'leaves_admins.user_id')
            ->select('leaves_admins.*', 'users.*')
            ->get();
        return view('reports.leavereports', compact('leaves'));
    }

    /** payment report index page */
    public function paymentsReportIndex()
    {
        return view('reports.payments-reports');
    }

    /** employee-reports page */
    public function employeeReportsIndex()
    {
        $users = User::select(
            'id',
            'name',
            'email',
            'role_name',
            'department',
            'position',
            'join_date',
           
            'phone_number',
           
            'avatar',
            'status'
        )->orderBy('name')->get();

        return view('reports.employee-reports', compact('users'));
    }
    /** Payslip Reports */
   public function payslipReports()
{
    $users = DB::table('users')
        ->join('staff_salaries', 'users.user_id', '=', 'staff_salaries.user_id')
        ->select('users.*', 'staff_salaries.*')
        ->get();

    $userList = DB::table('users')->get();
    $permission_lists = DB::table('permission_lists')->get();

    return view('reports.payslipreports', [
        'users' => $users,
        'userList' => $userList,
        'permission_lists' => $permission_lists
    ]);
}

   

    /** Attendance Reports */
    public function attendanceReports()
    {
        return view('reports.attendance-reports');
    }
}
