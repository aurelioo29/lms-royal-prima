<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\PlanEventController;
use App\Http\Controllers\AnnualPlanController;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\ModuleQuizController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\CourseModuleController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\InstructorMotController;
use App\Http\Controllers\TorSubmissionController;
use App\Http\Controllers\AdminMotReviewController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\InstructorCourseController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\EmployeeCourseModuleController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // (opsional kalau kamu aktifkan lagi)
    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    // Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

Route::middleware('auth')->group(function () {
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (Superadmin/HR)
    |--------------------------------------------------------------------------
    */
    Route::middleware('cap:can_manage_users')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('job-categories', JobCategoryController::class);
        Route::resource('job-titles', JobTitleController::class);
        Route::resource('employees', EmployeeController::class)->except(['show']);

        Route::get('/admin/mot', [AdminMotReviewController::class, 'index'])->name('admin.mot.index');
        Route::get('/admin/mot/{doc}', [AdminMotReviewController::class, 'show'])->name('admin.mot.show');
        Route::put('/admin/mot/{doc}', [AdminMotReviewController::class, 'update'])->name('admin.mot.update');
    });

    /*
    |--------------------------------------------------------------------------
    | ANNUAL PLANS
    |--------------------------------------------------------------------------
    */
    Route::get('/annual-plans', [AnnualPlanController::class, 'index'])
        ->name('annual-plans.index');

    // approvals (harus sebelum /{annualPlan})
    Route::middleware('cap:can_approve_plans')->group(function () {
        Route::get('/annual-plans/approvals', [AnnualPlanController::class, 'approvals'])
            ->name('annual-plans.approvals');

        Route::post('/annual-plans/{annualPlan}/approve', [AnnualPlanController::class, 'approve'])
            ->name('annual-plans.approve');

        Route::post('/annual-plans/{annualPlan}/reject', [AnnualPlanController::class, 'reject'])
            ->name('annual-plans.reject');
    });

    // create/edit/update/submit (harus sebelum /{annualPlan})
    Route::middleware('cap:can_create_plans')->group(function () {
        Route::get('/annual-plans/create', [AnnualPlanController::class, 'create'])
            ->name('annual-plans.create');

        Route::post('/annual-plans', [AnnualPlanController::class, 'store'])
            ->name('annual-plans.store');

        Route::get('/annual-plans/{annualPlan}/edit', [AnnualPlanController::class, 'edit'])
            ->name('annual-plans.edit');

        Route::put('/annual-plans/{annualPlan}', [AnnualPlanController::class, 'update'])
            ->name('annual-plans.update');

        Route::post('/annual-plans/{annualPlan}/submit', [AnnualPlanController::class, 'submit'])
            ->name('annual-plans.submit');
    });

    Route::middleware('cap:can_create_plans')
        ->scopeBindings()
        ->group(function () {
            Route::patch('/annual-plans/{annualPlan}/events/{planEvent}/submit', [PlanEventController::class, 'submit'])
                ->name('annual-plans.events.submit');

            Route::get('/annual-plans/{annualPlan}/events/create', [PlanEventController::class, 'create'])
                ->name('annual-plans.events.create');

            Route::post('/annual-plans/{annualPlan}/events', [PlanEventController::class, 'store'])
                ->name('annual-plans.events.store');

            Route::get('/annual-plans/{annualPlan}/events/{planEvent}', [PlanEventController::class, 'show'])
                ->name('annual-plans.events.show');

            Route::get('/annual-plans/{annualPlan}/events/{planEvent}/edit', [PlanEventController::class, 'edit'])
                ->name('annual-plans.events.edit');

            Route::put('/annual-plans/{annualPlan}/events/{planEvent}', [PlanEventController::class, 'update'])
                ->name('annual-plans.events.update');

            Route::delete('/annual-plans/{annualPlan}/events/{planEvent}', [PlanEventController::class, 'destroy'])
                ->name('annual-plans.events.destroy');
        });

    // ✅ TARUH PALING BAWAH
    Route::get('/annual-plans/{annualPlan}', [AnnualPlanController::class, 'show'])
        ->name('annual-plans.show');
    /*
    |--------------------------------------------------------------------------
    | TOR SUBMISSIONS
    |--------------------------------------------------------------------------
    | Kabid buat TOR + submit
    | Direktur approve/reject
    */
    Route::middleware('cap:can_create_plans')->group(function () {
        Route::get('/tor-submissions', [TorSubmissionController::class, 'index'])
            ->name('tor-submissions.index');

        Route::get('/plan-events/{planEvent}/tor/create', [TorSubmissionController::class, 'create'])
            ->name('tor-submissions.create');

        Route::post('/tor-submissions', [TorSubmissionController::class, 'store'])
            ->name('tor-submissions.store');

        Route::get('/tor-submissions/{torSubmission}/edit', [TorSubmissionController::class, 'edit'])
            ->name('tor-submissions.edit');

        Route::put('/tor-submissions/{torSubmission}', [TorSubmissionController::class, 'update'])
            ->name('tor-submissions.update');

        Route::patch('/tor-submissions/{torSubmission}/submit', [TorSubmissionController::class, 'submit'])
            ->name('tor-submissions.submit');
    });

    Route::middleware('cap:can_approve_plans')->group(function () {
        Route::patch('/tor-submissions/{torSubmission}/approve', [TorSubmissionController::class, 'approve'])
            ->name('tor-submissions.approve');

        Route::get('/tor-submissions/approvals', [TorSubmissionController::class, 'approvals'])
            ->name('tor-submissions.approvals');

        Route::patch('/tor-submissions/{torSubmission}/reject', [TorSubmissionController::class, 'reject'])
            ->name('tor-submissions.reject');

        Route::post('/annual-plans/{annualPlan}/reopen', [AnnualPlanController::class, 'reopen'])
            ->name('annual-plans.reopen');

        Route::patch('/annual-plans/{annualPlan}/events/{planEvent}/approve', [PlanEventController::class, 'approve'])
            ->name('annual-plans.events.approve');

        Route::patch('/annual-plans/{annualPlan}/events/{planEvent}/reject', [PlanEventController::class, 'reject'])
            ->name('annual-plans.events.reject');

        Route::post('/annual-plans/{annualPlan}/events/{planEvent}/reopen', [PlanEventController::class, 'reopen'])
            ->name('annual-plans.events.reopen');
    });

    /*
    |--------------------------------------------------------------------------
    | COURSE TYPES (dynamic type_course) - Admin Course
    |--------------------------------------------------------------------------
    */
    Route::middleware('cap:can_create_courses')->group(function () {
        Route::resource('course-types', CourseTypeController::class)->except(['show']);

        Route::patch('course-types/{courseType}/toggle', [CourseTypeController::class, 'toggle'])
            ->name('course-types.toggle');

        Route::prefix('courses/{course}')
            ->name('courses.')
            ->group(function () {

                // QUIZ QUESTION MANAGEMENT (ADMIN)
                Route::prefix('/modules/{module}/quiz')
                    ->name('modules.quiz.')
                    ->group(function () {

                        Route::get('/questions', [QuizQuestionController::class, 'index'])
                            ->name('questions.index');

                        Route::get('/questions/create', [QuizQuestionController::class, 'create'])
                            ->name('questions.create');

                        Route::post('/questions', [QuizQuestionController::class, 'store'])
                            ->name('questions.store');

                        Route::get('/questions/{question}/edit', [QuizQuestionController::class, 'edit'])
                            ->name('questions.edit');

                        Route::put('/questions/{question}', [QuizQuestionController::class, 'update'])
                            ->name('questions.update');

                        Route::put('/questions', [QuizQuestionController::class, 'bulkUpdate'])
                            ->name('questions.bulk-update');

                        Route::delete('/questions/{question}', [QuizQuestionController::class, 'destroy'])
                            ->name('questions.destroy');
                    });


                Route::get('/modules', [CourseModuleController::class, 'index'])
                    ->name('modules.index');

                Route::get('/modules/create', [CourseModuleController::class, 'create'])
                    ->name('modules.create');

                Route::post('/modules', [CourseModuleController::class, 'store'])
                    ->name('modules.store');

                Route::get('/modules/{module}/show', [CourseModuleController::class, 'show'])
                    ->name('modules.show');

                Route::get('/modules/{module}/edit', [CourseModuleController::class, 'edit'])
                    ->name('modules.edit');

                Route::put('/modules/{module}', [CourseModuleController::class, 'update'])
                    ->name('modules.update');

                Route::delete('/modules/{module}', [CourseModuleController::class, 'destroy'])
                    ->name('modules.destroy');

                Route::patch('/modules/{module}/toggle', [CourseModuleController::class, 'toggle'])
                    ->name('modules.toggle');

                Route::patch('/modules/{module}/reorder', [CourseModuleController::class, 'reorder'])
                    ->name('modules.reorder');
            });
    });

    /*
    |--------------------------------------------------------------------------
    | COURSES - Admin Course
    |--------------------------------------------------------------------------
    */
    Route::middleware('cap:can_create_courses')->group(function () {
        Route::resource('courses', CourseController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | COURSES - INSTRUCTOR
    |--------------------------------------------------------------------------
    */
    Route::prefix('instructor')
        ->middleware(['auth'])
        ->name('instructor.')
        ->group(function () {

            // list course milik instructor
            Route::get('courses', [InstructorCourseController::class, 'index'])
                ->name('courses.index');

            // MODULE MANAGEMENT (INSTRUCTOR)
            Route::prefix('courses/{course}')
                ->middleware('owns.course') // middleware custom
                ->name('courses.')
                ->group(function () {

                    // QUIZ QUESTION MANAGEMENT (INSTRUCTOR)
                    Route::prefix('/modules/{module}/quiz')
                        ->name('modules.quiz.')
                        ->group(function () {

                            Route::get('/questions', [QuizQuestionController::class, 'index'])
                                ->name('questions.index');

                            Route::get('/questions/create', [QuizQuestionController::class, 'create'])
                                ->name('questions.create');

                            Route::post('/questions', [QuizQuestionController::class, 'store'])
                                ->name('questions.store');

                            Route::get('/questions/{question}/edit', [QuizQuestionController::class, 'edit'])
                                ->name('questions.edit');

                            Route::put('/questions/{question}', [QuizQuestionController::class, 'update'])
                                ->name('questions.update');

                            Route::put('/questions', [QuizQuestionController::class, 'bulkUpdate'])
                                ->name('questions.bulk-update');

                            Route::delete('/questions/{question}', [QuizQuestionController::class, 'destroy'])
                                ->name('questions.destroy');
                        });


                    Route::get('/modules', [CourseModuleController::class, 'index'])
                        ->name('modules.index');

                    Route::get('/modules/create', [CourseModuleController::class, 'create'])
                        ->name('modules.create');

                    Route::post('/modules', [CourseModuleController::class, 'store'])
                        ->name('modules.store');

                    Route::get('/modules/{module}', [CourseModuleController::class, 'show'])
                        ->name('modules.show');

                    Route::get('/modules/{module}/edit', [CourseModuleController::class, 'edit'])
                        ->name('modules.edit');

                    Route::put('/modules/{module}', [CourseModuleController::class, 'update'])
                        ->name('modules.update');

                    Route::delete('/modules/{module}', [CourseModuleController::class, 'destroy'])
                        ->name('modules.destroy');

                    Route::patch('/modules/{module}/toggle', [CourseModuleController::class, 'toggle'])
                        ->name('modules.toggle');

                    Route::patch('/modules/{module}/reorder', [CourseModuleController::class, 'reorder'])
                        ->name('modules.reorder');
                });
        });


    /*
    |--------------------------------------------------------------------------
    | INSTRUCTOR MOT
    |--------------------------------------------------------------------------
    */
    Route::get('/instructor/mot', [InstructorMotController::class, 'show'])
        ->name('instructor.mot.show');
    Route::post('/instructor/mot', [InstructorMotController::class, 'store'])
        ->name('instructor.mot.store');

    /*
    |--------------------------------------------------------------------------
    | EMAIL VERIFICATION + PASSWORD
    |--------------------------------------------------------------------------
    */
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Employee / Peserta
    |--------------------------------------------------------------------------
    */
    Route::prefix('employee/courses')->name('employee.courses.')->group(function () {

        // COURSE ENROLLMENT
        Route::get('/', [CourseEnrollmentController::class, 'index'])->name('index');
        Route::get('/{course}', [CourseEnrollmentController::class, 'show'])->name('show');
        Route::get('/{course}/enroll', [CourseEnrollmentController::class, 'create'])->name('enroll.form');
        Route::post('/enroll', [CourseEnrollmentController::class, 'store'])->name('enroll');
        Route::post('/{course}/complete', [CourseEnrollmentController::class, 'complete'])->name('complete');

        // COURSE MODULES - EMPLOYEE
        Route::get('/{course}/modules', [EmployeeCourseModuleController::class, 'index'])
            ->name('modules.index');

        Route::get('/{course}/modules/{module}', [EmployeeCourseModuleController::class, 'show'])
            ->name('modules.show');

        Route::post('/{course}/modules/{module}/complete', [EmployeeCourseModuleController::class, 'complete'])
            ->name('modules.complete');

         Route::prefix('{course}/modules/{module}')
        ->name('modules.')
        ->group(function () {

            // ================= QUIZ =================

            // HALAMAN START QUIZ
            Route::get('quiz/start', [QuizAttemptController::class, 'startPage'])
                ->name('quiz.start');

            // ACTION START QUIZ
            Route::post('quiz/start', [QuizAttemptController::class, 'start'])
                ->name('quiz.start.submit');

            // ATTEMPT
            Route::get('quiz/attempt/{attempt}', [QuizAttemptController::class, 'attempt'])
                ->name('quiz.attempt');

            // SUBMIT
            Route::post('quiz/submit/{attempt}', [QuizAttemptController::class, 'submit'])
                ->name('quiz.submit');

            // RESULT
            Route::get('quiz/result/{attempt}', [QuizAttemptController::class, 'result'])
                ->name('quiz.result');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
