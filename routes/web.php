<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
use App\Http\Controllers\AssetsController;
=======
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// ----------- Public Routes -------------- //
Route::get('/', function () {
    return view('auth.login');
});

// Laravel built-in auth routes (login, register, etc.)
Auth::routes();

// ---------------- Auth controllers (custom) -----------------//
// These controllers live in App\Http\Controllers\Auth
Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    // Login
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    // Register
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeUser')->name('register.store');
    });

    // Forgot Password
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');
    });

    // Reset Password
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');
    });
});

// ---------------- All routes that require authentication -----------------//
// Wrap the whole area that needs auth inside one middleware group to avoid
// repeated nested middleware/group closures and reduce chances of unbalanced braces.
Route::middleware('auth')->namespace('App\Http\Controllers')->group(function () {

    // Main Dashboard
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
    });

    // Lock Screen
    Route::controller(LockScreen::class)->group(function () {
        Route::get('lock_screen', 'lockScreen')->name('lock_screen');
        Route::post('unlock', 'unlock')->name('unlock');
    });

    // Settings
    Route::controller(SettingController::class)->group(function () {
        Route::get('company/settings/page', 'companySettings')->name('company/settings/page');
        Route::post('company/settings/save', 'saveRecord')->name('company/settings/save');

        Route::get('roles/permissions/page', 'rolesPermissions')->name('roles/permissions/page');
        Route::post('roles/permissions/save', 'addRecord')->name('roles/permissions/save');
        Route::post('roles/permissions/update', 'editRolesPermissions')->name('roles/permissions/update');
        Route::post('roles/permissions/delete', 'deleteRolesPermissions')->name('roles/permissions/delete');

        Route::get('localization/page', 'localizationIndex')->name('localization/page');
        Route::get('salary/settings/page', 'salarySettingsIndex')->name('salary/settings/page');
        Route::get('email/settings/page', 'emailSettingsIndex')->name('email/settings/page');
    });

    // User Management
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('profile_user', 'profile')->name('profile_user');
        Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');
        Route::get('userManagement', 'index')->name('userManagement');
        Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
        Route::post('update', 'update')->name('update');
        Route::post('user/delete', 'delete')->name('user/delete');
        Route::get('change/password', 'changePasswordView')->name('change/password');
        Route::post('change/password/db', 'changePasswordDB')->name('change/password/db');
        Route::post('user/profile/emergency/contact/save', 'emergencyContactSaveOrUpdate')->name('user/profile/emergency/contact/save');
        Route::get('get-users-data', 'getUsersData')->name('get-users-data');
    });

    // Jobs
    Route::controller(JobController::class)->group(function () {
        Route::get('form/job/list', 'jobList')->name('form/job/list');
        Route::get('form/job/view/{id}', 'jobView');
        Route::get('user/dashboard/index', 'userDashboard')->name('user/dashboard/index');
        Route::get('jobs/dashboard/index', 'jobsDashboard')->name('jobs/dashboard/index');
        Route::get('user/dashboard/all', 'userDashboardAll')->name('user/dashboard/all');
        Route::get('user/dashboard/save', 'userDashboardSave')->name('user/dashboard/save');
        Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->name('user/dashboard/applied/jobs');
        Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->name('user/dashboard/interviewing');
        Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->name('user/dashboard/offered/jobs');
        Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->name('user/dashboard/visited/jobs');
        Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->name('user/dashboard/archived/jobs');
        Route::get('jobs', 'Jobs')->name('jobs');
        Route::get('job/applicants/{job_title}', 'jobApplicants');
        Route::get('job/details/{id}', 'jobDetails');
        Route::get('cv/download/{id}', 'downloadCV');
        Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
        Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
        Route::post('form/apply/job/update', 'applyJobUpdateRecord')->name('form/apply/job/update');
<<<<<<< HEAD
        Route::post('form/apply/job/delete', 'applyJobDeleteRecord')->name('form/apply/job/delete');
        Route::get('page/manage/resumes', 'manageResumesIndex')->name('page/manage/resumes');
        Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->name('page/shortlist/candidates');

        Route::get('page/interview/questions', 'interviewQuestionsIndex')->name('page/interview/questions');
        Route::post('page/interview/questions/store', 'interviewQuestionsStore')->name('questions.store');
        Route::post('page/interview/questions/update', 'interviewQuestionsUpdate')->name('questions.update');
        Route::delete('page/interview/questions/delete', 'interviewQuestionsDelete')->name('questions.delete');
        Route::post('save/category', 'categorySave')->name('save/category');
        // Route::post('save/questions', 'questionSave')->name('save/questions');
        // Route::post('questions/update', 'questionsUpdate')->name('questions/update');
        // Route::post('questions/delete', 'questionsDelete')->name('questions/delete');
        Route::get('page/offer/approvals', 'offerApprovalsIndex')->name('page/offer/approvals');

        Route::get('page/experience/level', 'experienceLevelIndex')->name('page/experience/level');
        Route::post('/experience-level/store',  'experiencestore')->name('experience-level.store');
        Route::post('/experience-level/update/{id}', 'experienceUpdate')->name('experience-level.update');
        Route::delete('/experience-level/delete/{id}', 'experienceDelete')->name('experience-level.delete');




        Route::get('page/candidates', 'candidatesIndex')->name('page/candidates');
        Route::post('page/candidates/store', 'Candidatesstore')->name('candidates.store');

        Route::get('page/candidates/edit/{id}', 'CandidatesEdit')->name('candidates.edit');
        Route::post('page/candidates/update', 'CandidatesUpdate')->name('candidates.update');
        Route::post('page/candidates/delete', 'Candidatedelete')->name('candidates.delete');



=======
        Route::get('page/manage/resumes', 'manageResumesIndex')->name('page/manage/resumes');
        Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->name('page/shortlist/candidates');
        Route::get('page/interview/questions', 'interviewQuestionsIndex')->name('page/interview/questions');
        Route::post('save/category', 'categorySave')->name('save/category');
        Route::post('save/questions', 'questionSave')->name('save/questions');
        Route::post('questions/update', 'questionsUpdate')->name('questions/update');
        Route::post('questions/delete', 'questionsDelete')->name('questions/delete');
        Route::get('page/offer/approvals', 'offerApprovalsIndex')->name('page/offer/approvals');
        Route::get('page/experience/level', 'experienceLevelIndex')->name('page/experience/level');
        Route::get('page/candidates', 'candidatesIndex')->name('page/candidates');
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7
        Route::get('page/schedule/timing', 'scheduleTimingIndex')->name('page/schedule/timing');
        Route::get('page/aptitude/result', 'aptituderesultIndex')->name('page/aptitude/result');
        Route::post('jobtypestatus/update', 'jobTypeStatusUpdate')->name('jobtypestatus/update');
    });

    // Employee-related (forms, departments, timesheet, overtime)
    Route::controller(EmployeeController::class)->group(function () {

        // All employees
        Route::prefix('all/employee')->group(function () {
            Route::get('/card', 'cardAllEmployee')->name('all/employee/card');
            Route::get('/list', 'listAllEmployee')->name('all/employee/list');
            Route::post('/save', 'saveRecord')->name('all/employee/save');
            Route::get('/view/edit/{employee_id}', 'viewRecord');
            Route::post('/update', 'updateRecord')->name('all/employee/update');
            Route::get('/delete/{employee_id}', 'deleteRecord');
            Route::post('/search', 'employeeSearch')->name('all/employee/search');
            Route::post('/list/search', 'employeeListSearch')->name('all/employee/list/search');
        });

        // Form section
        Route::prefix('form')->group(function () {

            // Departments
            Route::prefix('departments')->group(function () {
                Route::get('/page', 'index')->name('form/departments/page');
                Route::post('/save', 'saveRecordDepartment')->name('form/departments/save');
                Route::post('/update', 'updateRecordDepartment')->name('form/department/update');
                Route::post('/delete', 'deleteRecordDepartment')->name('form/department/delete');
            });

            // Designations
            Route::prefix('designations')->group(function () {
                Route::get('/page', 'designationsIndex')->name('form/designations/page');
                Route::post('/save', 'saveRecordDesignations')->name('form.designations.save');
                Route::post('/update', 'updateRecordDesignations')->name('form/designations/update');
                Route::post('/delete', 'deleteRecordDesignations')->name('form/designations/delete');
            });

            // Timesheet (ensure TimeSheetController exists & imported by namespace)
            Route::prefix('timesheet')->controller(TimeSheetController::class)->group(function () {
                Route::get('/page', 'timeSheetIndex')->name('form/timesheet/page');
                Route::get('/edit/{id}', 'getRecordTimeSheets')->name('timesheet.edit');
                Route::post('/save', 'saveRecordTimeSheets')->name('form/timesheet/save');
                Route::post('/update', 'updateRecordTimeSheets')->name('form/timesheet/update');
                Route::post('/delete', 'deleteRecordTimeSheets')->name('form/timesheet/delete');
            });

            // Over Time (correctly placed - uses OvertimeController)
            Route::prefix('overtime')->controller(OvertimeController::class)->group(function () {
                Route::get('/page', 'overTimeIndex')->name('form/overtime/page');
                Route::post('/save', 'saveRecordOverTime')->name('form/overtime/save');
                Route::post('/update', 'updateRecordOverTime')->name('form/overtime/update');
                // Route::post('/delete/{id}', 'deleteRecordOverTime')->name('form/overtime/delete');
                Route::post('/delete', 'deleteRecordOverTime')->name('form/overtime/delete');
            });

            // Profile employee (inside 'form' group)
<<<<<<< HEAD
            // Route::get('employee/profile/{user_id}', 'profileEmployee')->name();
            Route::get('employee/profile/{user_id}', 'profileEmployee')->name('employee.profile');
=======
            Route::get('employee/profile/{user_id}', 'profileEmployee');
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7
        });
    });



    // Shift  (correctly placed - uses ShiftController)
    Route::prefix('shiftscheduling')->controller(ShiftController::class)->group(function () {

        Route::get('/page', 'shiftScheduling')->name('form/shiftscheduling/page');
        Route::get('/list', 'shiftList')->name('form/shiftlist/page');
        Route::post('/shifts/store', 'store')->name('form/shiftscheduling/store');
        Route::post('shiftscheduling/update/{id}', 'update')->name('form/shiftscheduling/update');
        Route::get('/delete/{id}', 'destroy')->name('form/shiftscheduling/delete');
    });


    // Scheduling  (correctly placed - uses ShiftController)
    Route::prefix('shiftscheduling')->controller(ShiftScheduleController::class)->group(function () {
        Route::get('/', 'shiftScheduling')->name('shiftscheduling.page');
        Route::post('store', 'store')->name('shiftscheduling.store');
        Route::put('/update/{id}', 'update')->name('shiftscheduling.update');
        Route::delete('/delete/{id}', 'destroy')->name('shiftscheduling.destroy');
    });





    // Holidays
    Route::controller(HolidayController::class)->group(function () {
        Route::get('form/holidays/new', 'holiday')->name('form/holidays/new');
        Route::post('form/holidays/save', 'saveRecord')->name('form/holidays/save');
        Route::post('form/holidays/update', 'updateRecord')->name('form/holidays/update');
        Route::post('form/holidays/delete', 'deleteRecord')->name('form/holidays/delete');
    });

    // Leaves
    Route::controller(LeavesController::class)->group(function () {
        Route::prefix('form/leaves')->group(function () {
            Route::get('/new', 'leavesAdmin')->name('form/leaves/new');
            Route::post('/save', 'saveRecordLeave')->name('form/leaves/save');
            Route::get('/employee/new', 'leavesEmployee')->name('form/leaves/employee/new');
            Route::post('/edit/delete', 'deleteLeave')->name('form/leaves/edit/delete');
            Route::post('/leave/approve', 'approveleave')->name('leave.approve');
            Route::post('/form/leaves/update', 'update')->name('form/leaves/update');
        });

        Route::post('get/information/leave', 'getInformationLeave')->name('hr/get/information/leave');
        Route::get('form/leavesettings/page', 'leaveSettings')->name('form/leavesettings/page');
        Route::get('attendance/page', 'attendanceIndex')->name('attendance/page');
        Route::get('attendance/employee/page', 'AttendanceEmployee')->name('attendance/employee/page');
    });

    // Payroll
    Route::controller(PayrollController::class)->group(function () {
        Route::prefix('form/salary')->group(function () {
            Route::get('/page', 'salary')->name('form/salary/page');
            Route::post('/save', 'saveRecord')->name('form/salary/save');
            Route::post('/update', 'updateRecord')->name('form/salary/update');
            Route::post('/delete', 'deleteRecord')->name('form/salary/delete');
            Route::get('/view/{user_id}', 'salaryView');
        });

        Route::get('form/payroll/items', 'payrollItems')->name('form/payroll/items');
        Route::post('form/payroll/addition', 'storeAddition')->name('form/payroll/addition/store');
        Route::post('form/payroll/addition/update', 'updateAddition')->name('form/payroll/addition/update');
        Route::post('form/payroll/addition/delete', 'deleteAddition')->name('form/payroll/addition/delete');

        Route::post('form/payroll/overtime/add', 'storeOvertime')->name('form/payroll/overtime/add');
        Route::post('form/payroll/overtime/update', 'updateOvertime')->name('form/payroll/overtime/update');
        Route::post('form/payroll/overtime/delete', 'deleteOvertime')->name('form/payroll/overtime/delete');


        Route::post('/payroll/deductions',  'storeDeduction')->name('payroll.deductions.store');
        Route::get('/payroll/deductions/{id}/edit',  'editDeduction')->name('payroll.deductions.edit');
        Route::put('/payroll/deductions/{id}',  'updateDeduction')->name('payroll.deductions.update');
        Route::delete('/payroll/deductions/{id}',  'destroyDeduction')->name('payroll.deductions.destroy');



        Route::get('extra/report/pdf', 'reportPDF');
        Route::get('extra/report/excel', 'reportExcel');
    });

    // Reports
    Route::controller(ExpenseReportsController::class)->group(function () {
        Route::get('form/expense/reports/page', 'index')->name('form/expense/reports/page');
        Route::get('form/invoice/reports/page', 'invoiceReports')->name('form/invoice/reports/page');
        Route::get('form/daily/reports/page', 'dailyReport')->name('form/daily/reports/page');
        Route::get('form/leave/reports/page', 'leaveReport')->name('form/leave/reports/page');
        Route::get('form/payments/reports/page', 'paymentsReportIndex')->name('form/payments/reports/page');
        Route::get('form/employee/reports/page', 'employeeReportsIndex')->name('form/employee/reports/page');
        Route::get('form/payslip/reports/page', 'payslipReports')->name('form/payslip/reports/page');
        Route::get('form/attendance/reports/page', 'attendanceReports')->name('form/attendance/reports/page');
    });

    // Performance
    Route::controller(PerformanceController::class)->group(function () {
        Route::get('form/performance/indicator/page', 'index')->name('form/performance/indicator/page');
        Route::get('form/performance/page', 'performance')->name('form/performance/page');
        Route::get('form/performance/appraisal/page', 'performanceAppraisal')->name('form/performance/appraisal/page');

        Route::post('form/performance/indicator/save', 'saveRecordIndicator')->name('form/performance/indicator/save');
        Route::post('form/performance/indicator/delete', 'deleteIndicator')->name('form/performance/indicator/delete');
        Route::post('form/performance/indicator/update', 'updateIndicator')->name('form/performance/indicator/update');

        Route::post('form/performance/appraisal/save', 'saveRecordAppraisal')->name('form/performance/appraisal/save');
        Route::post('form/performance/appraisal/update', 'updateAppraisal')->name('form/performance/appraisal/update');
        Route::post('form/performance/appraisal/delete', 'deleteAppraisal')->name('form/performance/appraisal/delete');
    });

    // Training
    Route::controller(TrainingController::class)->group(function () {
        Route::get('form/training/list/page', 'index')->name('form/training/list/page');
<<<<<<< HEAD
        // Route::post('form/training/save', 'addNewTraining')->name('form/training/save');
        Route::post('/form/training/save', 'addNewTraining')->name('form.training.save');
=======
        Route::post('form/training/save', 'addNewTraining')->name('form/training/save');
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7
        Route::post('form/training/delete', 'deleteTraining')->name('form/training/delete');
        Route::post('form/training/update', 'updateTraining')->name('form/training/update');
    });

    // Trainers
    Route::controller(TrainersController::class)->group(function () {
        Route::get('form/trainers/list/page', 'index')->name('form/trainers/list/page');
        Route::post('form/trainers/save', 'saveRecord')->name('form/trainers/save');
        Route::post('form/trainers/update', 'updateRecord')->name('form/trainers/update');
        Route::post('form/trainers/delete', 'deleteRecord')->name('form/trainers/delete');
    });

    // Training Type
    Route::controller(TrainingTypeController::class)->group(function () {
        Route::get('form/training/type/list/page', 'index')->name('form/training/type/list/page');
        Route::post('form/training/type/save', 'saveRecord')->name('form/training/type/save');
        Route::post('form/training/type/update', 'updateRecord')->name('form/training/type/update');
        Route::post('form/training/type/delete', 'deleteTrainingType')->name('form/training/type/delete');
    });

    // Sales
    Route::controller(SalesController::class)->group(function () {
        Route::get('form/estimates/page', 'estimatesIndex')->name('form/estimates/page');
        Route::get('create/estimate/page', 'createEstimateIndex')->name('create/estimate/page');
        Route::get('edit/estimate/{estimate_number}', 'editEstimateIndex');
        Route::get('estimate/view/{estimate_number}', 'viewEstimateIndex');

        Route::post('create/estimate/save', 'createEstimateSaveRecord')->name('create/estimate/save');
        Route::post('create/estimate/update', 'EstimateUpdateRecord')->name('create/estimate/update');
        Route::post('estimate_add/delete', 'EstimateAddDeleteRecord')->name('estimate_add/delete');
        Route::post('estimate/delete', 'EstimateDeleteRecord')->name('estimate/delete');
        Route::post('form/estimates/search', 'EstimatessearchRecord')->name('form/estimates/search');

        Route::get('payments', 'Payments')->name('payments');
        Route::get('expenses/page', 'Expenses')->name('expenses/page');
        Route::post('expenses/save', 'saveRecord')->name('expenses/save');
        Route::post('expenses/update', 'updateRecord')->name('expenses/update');
        Route::post('expenses/delete', 'deleteRecord')->name('expenses/delete');
        Route::post('expenses/search', 'searchRecord')->name('expenses/search');
    });

    // Personal Information
    Route::controller(PersonalInformationController::class)->group(function () {
        Route::post('user/information/save', 'saveRecord')->name('user/information/save');
        Route::post('user-family/information/save', 'savefamilyRecord')->name('user-family/information/save');
        Route::post('user-edit-family/information/save/{id}', 'saveEditfamilyRecord')->name('user-edit-family/information/save');
        Route::delete('user-family/information/delete/{id}', 'deleteFamilyRecord');
        Route::post('user/education/', 'saveEducation')->name('saveEducation');
        Route::post('user/education/edit', 'editEducation')->name('editEducation');
        Route::post('user/exprience/edit/save', 'saveExprience')->name('saveExprience');
        Route::post('/update-experience', 'updateExperience')->name('updateExperience');
    });

    // Bank Information
    Route::controller(BankInformationController::class)->group(function () {
        Route::post('bank/information/save', 'saveRecord')->name('bank/information/save');
    });

    // Chat
<<<<<<< HEAD
    // Route::controller(ChatController::class)->group(function () {
    //     Route::get('chat', 'chatWith')->name('chat.with');
    // });


    Route::middleware(['auth'])->group(function () {
        Route::controller(ChatController::class)->group(function () {
            Route::get('chat', 'chatWith')->name('chat.with');
            Route::get('/chat/messages/{receiver}', 'messages')->name('chat.messages');
            Route::post('/chat/send', 'sendMessage')->name('chat.send'); 
            Route::post('/chat/user/add', 'addChatUser')->name('chat.user.add'); 
        });
    });

    // Assets
    Route::get('assets/page', [AssetsController::class, 'index'])->name('assets/page');
    Route::post('assets/query', [AssetsController::class, 'store'])->name('assets.store');
    Route::get('assets/{id}', [AssetsController::class, 'getAsset']);

    Route::post('assets/update', [AssetsController::class, 'update'])->name('assets.update');
    Route::delete('assets/{asset}', [AssetsController::class, 'destroy'])->name('assets.destroy');
=======
    Route::controller(ChatController::class)->group(function () {
        Route::get('chat', 'chat')->name('chat');
    });

    // Assets
    Route::controller(AssetsController::class)->group(function () {
        Route::get('assets/page', 'index')->name('assets/page');
    });
>>>>>>> 666be08a4b014c268fe5f6cc17e3d71fb9da67d7
}); // end middleware('auth') group
