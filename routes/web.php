<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StrandController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginInterfaceController;
use App\Http\Controllers\UserHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;


//Tan awon nato kong ma change sa git
Route::get('/', function () {
    return view('welcome');
});


//views
Route::get('/Authentication/login', [LoginInterfaceController::class, 'loginInterface'])->name('login');

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



});

//Login Processes
Route::post('/Authentication/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('Dashboard')->middleware('auth')->group(function () {

    Route::get('/admin', [AuthController::class, 'adminDashboard'])->name('AdminComponents.dashboard');
    Route::get('/teacher', [AuthController::class, 'teacherDashboard']);
    Route::get('/student', [AuthController::class, 'studentDashboard']);
    Route::get('/operator', [AuthController::class, 'operatorDashboard']);

});

Route::get('/AdminComponents/enrollmenthistory', function () {
    return view('AdminComponents.enrollmenthistory');
})->name('AdminComponents.enrollmenthistory');


Route::get('/AdminComponents/staffarchive', function () {
    return view('AdminComponents.staffarchive');
})->name('AdminComponents.staffarchive');











