<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\OperatorUserController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StrandController;
use App\Http\Controllers\StudentUserController;
use App\Http\Controllers\SubjectAssignmentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\TeacherUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginInterfaceController;
use App\Http\Controllers\UserHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;


//Tan awon nato kong ma change sa git
Route::get('/', function () {
    return view('Authentication.login');
});


//views
Route::get('/Authentication/login', [LoginInterfaceController::class, 'loginInterface'])->name('login');

//Login Processes
Route::post('/Authentication/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//crud
Route::prefix('AdminComponents')->middleware('auth')->group(function () {

    //Admin Crud
    Route::get('/admin', [UserController::class, 'admin'])->name('AdminComponents.admin');
    Route::post('/admin', [UserController::class, 'store'])->name('AdminComponents.admin.post');
    Route::post('/admin/update/{id}', [UserController::class, 'update'])->name('admin.update');
    Route::delete('/admin/delete/{id}', [UserController::class, 'destroy'])->name('admin.destroy');

    //Operator Crud
    Route::get('/operator', [UserController::class, 'operator'])->name('AdminComponents.operator');
    Route::post('/operator', [UserController::class, 'store'])->name('AdminComponents.operator.post');
    Route::post('/operator/update/{id}', [UserController::class, 'update'])->name('operator.update');
    Route::delete('/operator/delete/{id}', [UserController::class, 'destroy'])->name('operator.destroy');

    //Teacher Crud
    Route::get('/teacher', [UserController::class, 'teacher'])->name('AdminComponents.teacher');
    Route::post('/teacher', [UserController::class, 'store'])->name('AdminComponents.teacher.post');
    Route::post('/teacher/update/{id}', [UserController::class, 'update'])->name('teacher.update');
    Route::delete('/teacher/delete/{id}', [UserController::class, 'destroy'])->name('teacher.destroy');

    //Student Crud
    Route::get('/student', [UserController::class, 'student'])->name('AdminComponents.student');
    Route::post('/student', [UserController::class, 'store'])->name('AdminComponents.student.post');
    Route::post('/student/update/{id}', [UserController::class, 'update'])->name('student.update');
    Route::delete('/student/delete/{id}', [UserController::class, 'destroy'])->name('student.destroy');
    Route::post('/student/{id}/archive', [UserController::class, 'archive'])->name('student.archive');


    //History
    Route::get('/adminhistory', action: [UserHistoryController::class, 'admin'])->name('AdminComponents.adminhistory');
    Route::get('/operatorhistory', action: [UserHistoryController::class, 'operator'])->name('AdminComponents.operatorhistory');
    Route::get('/teacherhistory', action: [UserHistoryController::class, 'teacher'])->name('AdminComponents.teacherhistory');
    Route::get('/studenthistory', action: [UserHistoryController::class, 'student'])->name('AdminComponents.studenthistory');
    Route::get('/enrollmenthistory', action: [UserHistoryController::class, 'enrollment'])->name('AdminComponents.enrollmenthistory');


    //Strand Crud
    Route::get('/strand', action: [StrandController::class, 'strand'])->name('AdminComponents.strand');
    Route::post('/strand', [StrandController::class, 'store'])->name('AdminComponents.strand.post');
    Route::post('/strand/update/{id}', [StrandController::class, 'update'])->name('strand.update');
    Route::delete('/strand/delete/{id}', [StrandController::class, 'destroy'])->name('strand.destroy');

    // Subject CRUD
    Route::get('/subject', [SubjectController::class, 'subject'])->name('AdminComponents.subject');
    Route::post('/subject', [SubjectController::class, 'store'])->name('AdminComponents.subject.post');
    Route::post('/subject/update/{id}', [SubjectController::class, 'update'])->name('subject.update');
    Route::delete('/subject/delete/{id}', [SubjectController::class, 'destroy'])->name('subject.destroy');

    // SchoolYear CRUD
    Route::get('/schoolyear', [SchoolYearController::class, 'schoolYear'])->name('AdminComponents.schoolyear');
    Route::post('/schoolyear', [SchoolYearController::class, 'store'])->name('AdminComponents.schoolyear.post');
    Route::post('/schoolyear/update/{id}', [SchoolYearController::class, 'update'])->name('schoolyear.update');
    Route::delete('/schoolyear/delete/{id}', [SchoolYearController::class, 'destroy'])->name('schoolyear.destroy');
    Route::post('/schoolyear/set-active/{id}', [SchoolYearController::class, 'setActive'])->name('schoolyear.setActive');

    // Teacher Assignment CRUD
    Route::get('/teacherassignment', [TeacherAssignmentController::class, 'teacherAssignment'])->name('AdminComponents.teacherassignment');
    Route::post('/teacherassignment', [TeacherAssignmentController::class, 'store'])->name('AdminComponents.teacherassignment.post');
    Route::post('/teacherassignment/update/{id}', [TeacherAssignmentController::class, 'update'])->name('teacherassignment.update');
    Route::delete('/teacherassignment/delete/{id}', [TeacherAssignmentController::class, 'destroy'])->name('teacherassignment.destroy');

    // Section CRUD Routes
    Route::get('/section', [SectionController::class, 'section'])->name('AdminComponents.section');
    Route::post('/section', [SectionController::class, 'store'])->name('AdminComponents.section.post');
    Route::post('/section/update/{id}', [SectionController::class, 'update'])->name('section.update');
    Route::delete('/section/delete/{id}', [SectionController::class, 'destroy'])->name('section.destroy');

    //Settings
    Route::get('/setting', [SettingController::class, 'index'])->name('AdminComponents.setting');
    Route::post('/setting/update-user', [SettingController::class, 'updateUser'])->name('setting.updateUser');
    Route::post('/setting/update-address', [SettingController::class, 'updateAddress'])->name('setting.updateAddress');

    //Requirements
    Route::get('/requirement', [RequirementController::class, 'requirement'])->name('AdminComponents.requirement');

    //Archives
    Route::get('/archive', [ArchiveController::class, 'student'])->name('AdminComponents.archive');
    Route::post('/archive/{id}/archive', [ArchiveController::class, 'archive'])->name('archive.archive');

    // Subject Assignment CRUD
    Route::get('/subjectassignment', [SubjectAssignmentController::class, 'subjectAssignment'])->name('AdminComponents.subjectassignment');
    Route::post('/subjectassignment', [SubjectAssignmentController::class, 'store'])->name('AdminComponents.subjectassignment.post');
    Route::post('/subjectassignment/update/{id}', [SubjectAssignmentController::class, 'update'])->name('subjectassignment.update');
    Route::delete('/subjectassignment/delete/{id}', [SubjectAssignmentController::class, 'destroy'])->name('subjectassignment.destroy');

    // Section CRUD Routes
    Route::get('/schedule', [ScheduleController::class, 'section'])->name('AdminComponents.schedule');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('AdminComponents.schedule.post');
    Route::post('/schedule/update/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::post('/admin/sections/{id}/generate-schedule', [ScheduleController::class, 'generate'])->name('section.generateSchedule');
    Route::put('/schedule/update-teacher/{id}', [ScheduleController::class, 'updateTeacher'])->name('section.updateTeacher');

    // Enrollment CRUD
    Route::get('/enrollment', [EnrollmentController::class, 'enrollment'])->name('AdminComponents.enrollment');
    Route::post('/enrollment', [EnrollmentController::class, 'store'])->name('AdminComponents.enrollment.post');
    Route::post('/enrollment/update/{id}', [EnrollmentController::class, 'update'])->name('enrollment.update');
    Route::delete('/enrollment/delete/{id}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');
    Route::get('/get-sections-by-strand-and-grade', [EnrollmentController::class, 'getSectionsByStrandAndGrade'])->name('get.sections.by.strand.and.grade');




});


Route::prefix('OperatorComponents')->group(function () {

    // Section CRUD Routes
    Route::get('/schedule', [OperatorUserController::class, 'section'])->name('OperatorComponents.schedule');
    Route::post('/schedule', [OperatorUserController::class, 'store'])->name('OperatorComponents.schedule.post');
    Route::post('/schedule/update/{id}', [OperatorUserController::class, 'update'])->name('OperatorComponents.schedule.update');
    Route::post('/admin/sections/{id}/generate-schedule', [OperatorUserController::class, 'generate'])->name('OperatorComponents.section.generateSchedule');
    Route::put('/schedule/update-teacher/{id}', [OperatorUserController::class, 'updateTeacher'])->name('OperatorComponents.section.updateTeacher');

    // Enrollment CRUD
    Route::get('/enrollment', [OperatorUserController::class, 'enrollment'])->name('OperatorComponents.enrollment');
    Route::post('/enrollment', [OperatorUserController::class, 'store'])->name('OperatorComponents.enrollment.post');
    Route::post('/enrollment/update/{id}', [OperatorUserController::class, 'update'])->name('OperatorComponents.enrollment.update');
    Route::delete('/enrollment/delete/{id}', [OperatorUserController::class, 'destroy'])->name('OperatorComponents.enrollment.destroy');
    Route::get('/get-sections-by-strand-and-grade', [OperatorUserController::class, 'getSectionsByStrandAndGrade'])->name('OperatorComponents.get.sections.by.strand.and.grade');

    //Archives
    Route::get('/archive', [OperatorUserController::class, 'arstudent'])->name('OperatorComponents.archive');
    Route::post('/archive/{id}/archive', [OperatorUserController::class, 'archive'])->name('OperatorComponents.archive.archive');

    //Histo
    Route::get('/studenthistory', [OperatorUserController::class, 'studenthistory'])->name('OperatorComponents.studenthistory');
    Route::get('/enrollmenthistory', [OperatorUserController::class, 'enrollmenthistory'])->name('OperatorComponents.enrollmenthistory');

    //Student Crud
    Route::get('/student', [OperatorUserController::class, 'student'])->name('OperatorComponents.student');
    Route::post('/student', [OperatorUserController::class, 'store'])->name('OperatorComponents.student.post');
    Route::post('/student/update/{id}', [OperatorUserController::class, 'update'])->name('OperatorComponents.student.update');
    Route::delete('/student/delete/{id}', [OperatorUserController::class, 'destroy'])->name('OperatorComponents.student.destroy');
    Route::post('/student/{id}/archive', [OperatorUserController::class, 'archive'])->name('OperatorComponents.student.archive');

     //Requirements
     Route::get('/requirement', [OperatorUserController::class, 'requirement'])->name('OperatorComponents.requirement');

     //Settings
    Route::get('/setting', [OperatorUserController::class, 'index'])->name('OperatorComponents.setting');
    Route::post('/setting/update-user', [OperatorUserController::class, 'updateUser'])->name('OperatorComponents.setting.updateUser');
    Route::post('/setting/update-address', [OperatorUserController::class, 'updateAddress'])->name('OperatorComponents.setting.updateAddress');

});

Route::prefix('StudentComponents')->middleware('auth')->group(function () {


    Route::get('/spr', [StudentUserController::class, 'spr'])->name('StudentComponents.spr');
    Route::get('/class', [StudentUserController::class, 'class'])->name('StudentComponents.class');
    Route::get('/corecommitments', [StudentUserController::class, 'core'])->name('StudentComponents.corecommitments');
    Route::get('/setting', [StudentUserController::class, 'index'])->name('StudentComponents.setting');
    Route::post('/setting/update-user', [StudentUserController::class, 'updateUser'])->name('student.setting.updateUser');
    Route::post('/setting/update-address', [StudentUserController::class, 'updateAddress'])->name('student.setting.updateAddress');
    Route::get('/credential', [StudentUserController::class, 'credential'])->name('StudentComponents.credential');
});


Route::prefix('TeacherComponents')->middleware('auth')->group(function () {

    Route::get('/specialists', [TeacherUserController::class, 'specialists'])->name('TeacherComponents.specialists');
    Route::get('/corecommitments', [TeacherUserController::class, 'core'])->name('TeacherComponents.corecommitments');
    Route::get('/setting', [TeacherUserController::class, 'index'])->name('TeacherComponents.setting');
    Route::post('/setting/update-user', [TeacherUserController::class, 'updateUser'])->name('teacher.setting.updateUser');
    Route::post('/setting/update-address', [TeacherUserController::class, 'updateAddress'])->name('teacher.setting.updateAddress');
    Route::get('/class', [TeacherUserController::class, 'class'])->name('TeacherComponents.class');
});

Route::prefix('Dashboard')->middleware('auth')->group(function () {

    Route::get('/admin', [AuthController::class, 'adminDashboard'])->name('AdminComponents.dashboard');
    Route::get('/teacher', [AuthController::class, 'teacherDashboard'])->name('TeacherComponents.dashboard');
    Route::get('/student', [AuthController::class, 'studentDashboard'])->name('StudentComponents.dashboard');
    Route::get('/operator', [AuthController::class, 'operatorDashboard'])->name('OperatorComponents.dashboard');

});











