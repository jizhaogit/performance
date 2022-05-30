<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\MyOrgController;
use App\Http\Controllers\HRadminController;
use App\Http\Controllers\HRAdmin\HRGoalController;
use App\Http\Controllers\GenericTemplateController;
use App\Http\Controllers\SharedEmployeesController;
use App\Http\Controllers\HRAdmin\NotificationController;
use App\Http\Controllers\HRAdmin\MyOrganizationController;
use App\Http\Controllers\HRAdmin\StatisticsReportController;


Route::group(['middleware' => ['role:HR Admin']], function () 
{

    Route::get('/hradmin/org-organizations', [HRadminController::class,'getOrganizations']);
    Route::get('/hradmin/org-programs', [HRadminController::class,'getPrograms']);
    Route::get('/hradmin/org-divisions', [HRadminController::class,'getDivisions']);
    Route::get('/hradmin/org-branches', [HRadminController::class,'getBranches']);
    Route::get('/hradmin/org-level4', [HRadminController::class,'getLevel4']);


    Route::get('hradmin/myorg', [MyOrganizationController::class, 'index'])->name('hradmin.myorg');
    Route::get('hradmin/myorg/myorganization', [MyOrganizationController::class, 'getList'])->name('hradmin.myorg.myorganization');
    Route::post('hradmin/myorg/myorganization', [MyOrganizationController::class, 'index'])->name('hradmin.myorg.myorganization');


    Route::get('hradmin/shared/shareemployee', [HRadminController::class, 'shareemployee'])->name('hradmin.shared.shareemployee');
    Route::get('hradmin/shared/manageshares', [HRadminController::class, 'manageshares'])->name('hradmin.shared.manageshares');


    //Goal Routes
    Route::get('/hradmin/goals/addgoals', [HRGoalController::class, 'addgoals'])->name('hradmin.goals.addgoals');
    Route::post('hradmin/goals/addgoals', [HRGoalController::class, 'addgoals'])->name('hradmin.goals.addgoals');
    Route::get('hradmin/goals/listgoals', [HRGoalController::class, 'listgoals'])->name('hradmin.goals.listgoals');
    Route::get('hradmin/goals/showgoals', [HRGoalController::class, 'showGoals'])->name('hradmin.goals.showgoals');


    Route::get('hradmin/excused/excuseemployee', [HRadminController::class, 'excuseemployee'])->name('hradmin.excused.excuseemployee');
    Route::get('hradmin/excused/manageexcused', [HRadminController::class, 'manageexcused'])->name('hradmin.excused.manageexcused');
    Route::get('hradmin/goals/addgoal', [HRadminController::class, 'addgoal'])->name('hradmin.goals.addgoal');
    Route::get('hradmin/goals/goal-bank', [HRadminController::class, 'addgoal'])->name('hradmin.goals.goal-bank');
    Route::get('hradmin/goals/goal-edit/{id}', [HRadminController::class, 'goaledit'])->name('hradmin.goals.goal-edit');
    Route::post('hradmin/goals/goaladd', [HRadminController::class, 'goaladd'])->name('hradmin.goals.goaladd');
    Route::post('hradmin/goals/goalupdate/{id}', [HRadminController::class, 'goalupdate'])->name('hradmin.goals.goalupdate');



    Route::get('/hradmin/notifications', [NotificationController::class, 'index'])->name('hradmin.notifications');
    Route::get('/hradmin/notifications/detail/{notification_id}', [NotificationController::class, 'show']);
    Route::get('/hradmin/notifications/notify', [NotificationController::class, 'notify'])->name('hradmin.notifications.notify');
    Route::post('/hradmin/notifications/notify', [NotificationController::class, 'notify'])->name('hradmin.notifications.search');
    Route::post('/hradmin/notifications/notify-send', [NotificationController::class, 'send'])->name('hradmin.notifications.send');
    Route::get('/hradmin/notifications/users', [NotificationController::class, 'getUsers'])->name('hradmin.notifications.users.list');
    Route::resource('/hradmin/notifications/generic-template', GenericTemplateController::class)->except(['destroy']);
    Route::get('graph-users', [GenericTemplateController::class,'getUsers']);
    
    Route::get('/hradmin/notifications/org-tree', [NotificationController::class,'loadOrganizationTree']);
    Route::get('/hradmin/notifications/org-organizations', [NotificationController::class,'getOrganizations']);
    Route::get('/hradmin/notifications/org-programs', [NotificationController::class,'getPrograms']);
    Route::get('/hradmin/notifications/org-divisions', [NotificationController::class,'getDivisions']);
    Route::get('/hradmin/notifications/org-branches', [NotificationController::class,'getBranches']);
    Route::get('/hradmin/notifications/org-level4', [NotificationController::class,'getLevel4']);
    Route::get('/hradmin/notifications/job-titles', [NotificationController::class,'getJobTitles']);
    Route::get('/hradmin/notifications/employees/{id}', [NotificationController::class,'getEmployees']);
    Route::get('/hradmin/notifications/employee-list', [NotificationController::class, 'getDatatableEmployees'])->name('hradmin.notifications.employee.list');

    
    Route::get('hradmin/statistics/goalsummary', [StatisticsReportController::class, 'goalsummary'])->name('hradmin.statistics.goalsummary');
    Route::get('hradmin/statistics/goalsummary-export', [StatisticsReportController::class, 'goalSummaryExport'])->name('hradmin.statistics.goalsummary.export');
    Route::get('hradmin/statistics/conversationsummary', [StatisticsReportController::class, 'conversationsummary'])->name('hradmin.statistics.conversationsummary');
    Route::get('hradmin/statistics/conversationsummary-export', [StatisticsReportController::class, 'conversationSummaryExport'])->name('hradmin.statistics.conversationsummary.export');
    Route::get('hradmin/statistics/sharedsummary', [StatisticsReportController::class, 'sharedsummary'])->name('hradmin.statistics.sharedsummary');
    Route::get('hradmin/statistics/excusedsummary', [StatisticsReportController::class, 'excusedsummary'])->name('hradmin.statistics.excusedsummary');


    Route::get('hradmin/level0', 'App\Http\Controllers\HRadminController@getOrgLevel0')->name('hradmin.level0');
    Route::get('hradmin/level1/{id0}', 'App\Http\Controllers\HRadminController@getOrgLevel1')->name('hradmin.level1');
    Route::get('hradmin/level2/{id0}/{id1}', 'App\Http\Controllers\HRadminController@getOrgLevel2')->name('hradmin.level2');
    Route::get('hradmin/level3/{id0}/{id1}/{id2}', 'App\Http\Controllers\HRadminController@getOrgLevel3')->name('hradmin.level3');
    Route::get('hradmin/level4/{id0}/{id1}/{id2}/{id3}', 'App\Http\Controllers\HRadminController@getOrgLevel4')->name('hradmin.level4');


});
