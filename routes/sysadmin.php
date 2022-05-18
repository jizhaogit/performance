<?php

use App\Http\Controllers\SysadminController;
use App\Http\Controllers\CurrentEmployeesController;
use App\Http\Controllers\PastEmployeesController;
use App\Http\Controllers\CreateAccessController;
use App\Http\Controllers\GenericTemplateController;
use App\Http\Controllers\ManageExistingAccessController;
use App\Http\Controllers\ManageExistingSharesController;
use App\Http\Controllers\ManageExistingExcusedController;
use App\Http\Controllers\ManageGoalBankController;
use App\Http\Controllers\SysAdmin\UnlockConversationController;
use App\Http\Controllers\SysAdmin\NotificationController;
use App\Http\Controllers\SysAdmin\ExcusedEmployeesController;
use App\Http\Controllers\SysAdmin\AccessPermissionsController;
use App\Http\Controllers\SysAdmin\SharedEmployeesController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['role:Sys Admin']], function () {


Route::get('sysadmin/employees/currentemployees', [CurrentEmployeesController::class, 'index'])->name('sysadmin.employees.currentemployees');
Route::get('sysadmin/employees/currentemployeeslist', [CurrentEmployeesController::class, 'getList'])->name('sysadmin.employees.currentemployeeslist');
Route::post('sysadmin/employees/currentemployees', [CurrentEmployeesController::class, 'index'])->name('sysadmin.employees.currentemployees');
Route::get('/sysadmin/employees/org-organizations', [CurrentEmployeesController::class,'getOrganizations']);
Route::get('/sysadmin/employees/org-programs', [CurrentEmployeesController::class,'getPrograms']);
Route::get('/sysadmin/employees/org-divisions', [CurrentEmployeesController::class,'getDivisions']);
Route::get('/sysadmin/employees/org-branches', [CurrentEmployeesController::class,'getBranches']);
Route::get('/sysadmin/employees/org-level4', [CurrentEmployeesController::class,'getLevel4']);

Route::get('sysadmin/employees/pastemployees', [PastEmployeesController::class, 'index'])->name('sysadmin.employees.pastemployees');
Route::get('sysadmin/employees/pastemployeeslist', [PastEmployeesController::class, 'getList'])->name('sysadmin.employees.pastemployeeslist');
Route::post('sysadmin/employees/pastemployees', [PastEmployeesController::class, 'index'])->name('sysadmin.employees.pastemployees');

Route::get('sysadmin/shared/shareemployee', [SysadminController::class, 'shareemployee'])->name('sysadmin.shared.shareemployee');

Route::get('sysadmin/shared/manageexistingshares', [ManageExistingSharesController::class, 'index'])->name('sysadmin.shared.manageexistingshares');
Route::get('sysadmin/employees/manageexistingshareslist', [ManageExistingSharesController::class, 'getList'])->name('sysadmin.employees.manageexistingshareslist');
Route::post('sysadmin/shared/manageexistingshares', [ManageExistingSharesController::class, 'index'])->name('sysadmin.shared.manageexistingshares');

Route::get('sysadmin/excused/excuseemployee', [SysadminController::class, 'excuseemployee'])->name('sysadmin.excused.excuseemployee');

Route::get('sysadmin/excused/manageexistingexcused', [ManageExistingExcusedController::class, 'index'])->name('sysadmin.excused.manageexistingexcused');
Route::get('sysadmin/excused/manageexistingexcusedlist', [ManageExistingExcusedController::class, 'getList'])->name('sysadmin.excused.manageexistingexcusedlist');
Route::post('sysadmin/excused/manageexistingexcused', [ManageExistingExcusedController::class, 'index'])->name('sysadmin.excused.manageexistingexcused');

Route::get('sysadmin/goals/managegoalbank', [ManageGoalBankController::class, 'index'])->name('sysadmin.goals.managegoalbank');
Route::post('sysadmin/goals/managegoalbank', [ManageGoalBankController::class, 'index'])->name('sysadmin.goals.managegoalbank');
Route::get('sysadmin/goals/managegoalbanklist', [ManageGoalBankController::class, 'getList'])->name('sysadmin.goals.managegoalbanklist');



Route::get('sysadmin/goals/addgoal', [SysadminController::class, 'addgoal'])->name('sysadmin.goals.addgoal');
Route::get('sysadmin/goals/goal-bank', [SysadminController::class, 'addgoal'])->name('sysadmin.goals.goal-bank');
// Route::get('sysadmin/goals/managegoals', [SysadminController::class, 'managegoals'])->name('sysadmin.goals.managegoals');
Route::get('sysadmin/goals/goal-edit/{id}', [SysadminController::class, 'goaledit'])->name('sysadmin.goals.goal-edit');
Route::post('sysadmin/goals/goaladd', [SysadminController::class, 'goaladd'])->name('sysadmin.goals.goaladd');
Route::post('sysadmin/goals/goalupdate/{id}', [SysadminController::class, 'goalupdate'])->name('sysadmin.goal.goalupdate');


Route::group(['middleware' => ['auth']], function() 
{    
    Route::get('/sysadmin/unlock/unlockconversation', [UnlockConversationController::class, 'index'])->name('sysadmin.unlock.unlockconversation');
    Route::post('/sysadmin/unlock/unlockconversation', [UnlockConversationController::class, 'index'])->name('sysadmin.unlock.unlockconversation.search');
    Route::get('/sysadmin/unlock/locked-conversation-list', [UnlockConversationController::class, 'getDatatableConversations'])->name('sysadmin.unlock.lockedconversation.list');
    Route::put('/sysadmin/unlock/unlockconversation/{id}', [UnlockConversationController::class, 'update'])->name('sysadmin.unlock.unlockconversation.store');
    Route::get('/sysadmin/unlock/manageunlocked', [UnlockConversationController::class, 'indexManageUnlocked'])->name('sysadmin.unlock.manageunlocked');
    Route::post('/sysadmin/unlock/manageunlocked', [UnlockConversationController::class, 'indexManageUnlocked'])->name('sysadmin.unlock.manageunlocked.search');
    Route::get('/sysadmin/unlock/unlocked-conversation-list', [UnlockConversationController::class, 'getDatatableManagedUnlocked'])->name('sysadmin.unlock.unlockconversation.list');
});


// Route::get('sysadmin/notifications/createnotification', [SysadminController::class, 'createnotification'])->name('sysadmin.notifications.createnotification');
// Route::get('sysadmin/notifications/viewnotifications', [SysadminController::class, 'viewnotifications'])->name('sysadmin.notifications.viewnotifications');



// Route::get('/hradmin/notifications/employee-list', [NotificationController::class, 'getDatatableEmployees'])->name('hradmin.notifications.employee.list');
Route::get('/sysadmin/access/employee-list', [CreateAccessController::class, 'getDatatableEmployees'])->name('sysadmin.access.employee.list');
Route::get('sysadmin/access/createaccess', [CreateAccessController::class, 'index'])->name('sysadmin.access.createaccess');
Route::get('sysadmin/access/org-tree', [CreateAccessController::class,'loadOrganizationTree']);
Route::get('sysadmin/access/users', [CreateAccessController::class, 'getUsers'])->name('sysadmin.access.users.list');
// Route::get('sysadmin/access/manageaccess', [ManageExistingAccessController::class, 'manageaccess'])->name('sysadmin.access.manageaccess');



Route::get('sysadmin/statistics/goalsummary', [SysadminController::class, 'goalsummary'])->name('sysadmin.statistics.goalsummary');
Route::get('sysadmin/statistics/conversationsummary', [SysadminController::class, 'conversationsummary'])->name('sysadmin.statistics.conversationsummary');
Route::get('sysadmin/statistics/sharedsummary', [SysadminController::class, 'sharedsummary'])->name('sysadmin.statistics.sharedsummary');
Route::get('sysadmin/statistics/excusedsummary', [SysadminController::class, 'excusedsummary'])->name('sysadmin.statistics.excusedsummary');
Route::get('sysadmin/switch-identity', [SysadminController::class, 'switchIdentity'])->name('sysadmin.switch-identity');


//Shared Employees
Route::group(['middleware' => ['auth']], function() {    
    Route::get('/sysadmin/sharedemployees', [SharedEmployeesController::class, 'index'])->name('sysadmin.sharedemployees');
    Route::get('/sysadmin/sharedemployees/detail/{notification_id}', [SharedEmployeesController::class, 'show']);
    Route::get('/sysadmin/sharedemployees/notify', [SharedEmployeesController::class, 'notify'])->name('sysadmin.sharedemployees.notify');
    Route::post('/sysadmin/sharedemployees/notify', [SharedEmployeesController::class, 'notify'])->name('sysadmin.sharedemployees.search');
    Route::post('/sysadmin/sharedemployees/notify-send', [SharedEmployeesController::class, 'send'])->name('sysadmin.sharedemployees.send');
    Route::get('/sysadmin/sharedemployees/users', [SharedEmployeesController::class, 'getUsers'])->name('sysadmin.sharedemployees.users.list');
    
    Route::get('/sysadmin/sharedemployees/org-tree', [SharedEmployeesController::class,'loadOrganizationTree']);
    Route::get('/sysadmin/sharedemployees/org-organizations', [SharedEmployeesController::class,'getOrganizations']);
    Route::get('/sysadmin/sharedemployees/org-programs', [SharedEmployeesController::class,'getPrograms']);
    Route::get('/sysadmin/sharedemployees/org-divisions', [SharedEmployeesController::class,'getDivisions']);
    Route::get('/sysadmin/sharedemployees/org-branches', [SharedEmployeesController::class,'getBranches']);
    Route::get('/sysadmin/sharedemployees/org-level4', [SharedEmployeesController::class,'getLevel4']);
    Route::get('/sysadmin/sharedemployees/job-titles', [SharedEmployeesController::class,'getJobTitles']);
    Route::get('/sysadmin/sharedemployees/employees/{id}', [SharedEmployeesController::class,'getEmployees']);
    Route::get('/sysadmin/sharedemployees/employee-list', [SharedEmployeesController::class, 'getDatatableEmployees'])->name('sysadmin.sharedemployees.employee.list');
    
});


//Excused Employees
Route::group(['middleware' => ['auth']], function() {    
    Route::get('/sysadmin/excusedemployees', [ExcusedEmployeesController::class, 'index'])->name('sysadmin.excusedemployees');
    Route::get('/sysadmin/excusedemployees/detail/{notification_id}', [ExcusedEmployeesController::class, 'show']);
    Route::get('/sysadmin/excusedemployees/notify', [ExcusedEmployeesController::class, 'notify'])->name('sysadmin.excusedemployees.notify');
    Route::post('/sysadmin/excusedemployees/notify', [ExcusedEmployeesController::class, 'notify'])->name('sysadmin.excusedemployees.search');
    Route::post('/sysadmin/excusedemployees/notify-send', [ExcusedEmployeesController::class, 'send'])->name('sysadmin.excusedemployees.send');
    Route::get('/sysadmin/excusedemployees/users', [ExcusedEmployeesController::class, 'getUsers'])->name('sysadmin.excusedemployees.users.list');
    
    Route::get('/sysadmin/excusedemployees/org-tree', [ExcusedEmployeesController::class,'loadOrganizationTree']);
    Route::get('/sysadmin/excusedemployees/org-organizations', [ExcusedEmployeesController::class,'getOrganizations']);
    Route::get('/sysadmin/excusedemployees/org-programs', [ExcusedEmployeesController::class,'getPrograms']);
    Route::get('/sysadmin/excusedemployees/org-divisions', [ExcusedEmployeesController::class,'getDivisions']);
    Route::get('/sysadmin/excusedemployees/org-branches', [ExcusedEmployeesController::class,'getBranches']);
    Route::get('/sysadmin/excusedemployees/org-level4', [ExcusedEmployeesController::class,'getLevel4']);
    Route::get('/sysadmin/excusedemployees/job-titles', [ExcusedEmployeesController::class,'getJobTitles']);
    Route::get('/sysadmin/excusedemployees/employees/{id}', [ExcusedEmployeesController::class,'getEmployees']);
    Route::get('/sysadmin/excusedemployees/employee-list', [ExcusedEmployeesController::class, 'getDatatableEmployees'])->name('sysadmin.excusedemployees.employee.list');
    
});


//Notifications
Route::group(['middleware' => ['auth']], function() {    
    Route::get('/sysadmin/notifications', [NotificationController::class, 'index'])->name('sysadmin.notifications');
    Route::get('/sysadmin/notifications/detail/{notification_id}', [NotificationController::class, 'show']);
    Route::get('/sysadmin/notifications/notify', [NotificationController::class, 'notify'])->name('sysadmin.notifications.notify');
    Route::post('/sysadmin/notifications/notify', [NotificationController::class, 'notify'])->name('sysadmin.notifications.search');
    Route::post('/sysadmin/notifications/notify-send', [NotificationController::class, 'send'])->name('sysadmin.notifications.send');
    Route::get('/sysadmin/notifications/users', [NotificationController::class, 'getUsers'])->name('sysadmin.notifications.users.list');
    Route::resource('/sysadmin/notifications/generic-template', GenericTemplateController::class)->except(['destroy']);
    Route::get('graph-users', [GenericTemplateController::class,'getUsers']);
    
    Route::get('/sysadmin/notifications/org-tree', [NotificationController::class,'loadOrganizationTree']);
    Route::get('/sysadmin/notifications/org-organizations', [NotificationController::class,'getOrganizations']);
    Route::get('/sysadmin/notifications/org-programs', [NotificationController::class,'getPrograms']);
    Route::get('/sysadmin/notifications/org-divisions', [NotificationController::class,'getDivisions']);
    Route::get('/sysadmin/notifications/org-branches', [NotificationController::class,'getBranches']);
    Route::get('/sysadmin/notifications/org-level4', [NotificationController::class,'getLevel4']);
    Route::get('/sysadmin/notifications/job-titles', [NotificationController::class,'getJobTitles']);
    Route::get('/sysadmin/notifications/employees/{id}', [NotificationController::class,'getEmployees']);
    Route::get('/sysadmin/notifications/employee-list', [NotificationController::class, 'getDatatableEmployees'])->name('sysadmin.notifications.employee.list');
    
});

 
//Access and Permissions
Route::group(['middleware' => ['auth']], function() {    
    Route::get('/sysadmin/accesspermissions', [AccessPermissionsController::class, 'index'])->name('sysadmin.accesspermissions');
    Route::get('/sysadmin/accesspermissions/detail/{notification_id}', [AccessPermissionsController::class, 'show']);
    Route::get('/sysadmin/accesspermissions/notify', [AccessPermissionsController::class, 'notify'])->name('sysadmin.accesspermissions.notify');
    Route::post('/sysadmin/accesspermissions/notify', [AccessPermissionsController::class, 'notify'])->name('sysadmin.accesspermissions.search');
    Route::post('/sysadmin/accesspermissions/saveaccess', [AccessPermissionsController::class, 'saveAccess'])->name('sysadmin.accesspermissions.saveaccess');
    Route::get('/sysadmin/accesspermissions/users', [AccessPermissionsController::class, 'getUsers'])->name('sysadmin.accesspermissions.users.list');
    
    Route::get('/sysadmin/accesspermissions/org-tree', [AccessPermissionsController::class,'loadOrganizationTree']);
    Route::get('/sysadmin/accesspermissions/eorg-tree', [AccessPermissionsController::class,'eloadOrganizationTree']);
    Route::get('/sysadmin/accesspermissions/org-organizations', [AccessPermissionsController::class,'getOrganizations']);
    Route::get('/sysadmin/accesspermissions/org-programs', [AccessPermissionsController::class,'getPrograms']);
    Route::get('/sysadmin/accesspermissions/org-divisions', [AccessPermissionsController::class,'getDivisions']);
    Route::get('/sysadmin/accesspermissions/org-branches', [AccessPermissionsController::class,'getBranches']);
    Route::get('/sysadmin/accesspermissions/org-level4', [AccessPermissionsController::class,'getLevel4']);
    Route::get('/sysadmin/accesspermissions/eorg-organizations', [AccessPermissionsController::class,'geteOrganizations']);
    Route::get('/sysadmin/accesspermissions/eorg-programs', [AccessPermissionsController::class,'getePrograms']);
    Route::get('/sysadmin/accesspermissions/eorg-divisions', [AccessPermissionsController::class,'geteDivisions']);
    Route::get('/sysadmin/accesspermissions/eorg-branches', [AccessPermissionsController::class,'geteBranches']);
    Route::get('/sysadmin/accesspermissions/eorg-level4', [AccessPermissionsController::class,'geteLevel4']);
    Route::get('/sysadmin/accesspermissions/job-titles', [AccessPermissionsController::class,'getJobTitles']);
    Route::get('/sysadmin/accesspermissions/employees/{id}', [AccessPermissionsController::class,'getEmployees']);
    Route::get('/sysadmin/accesspermissions/employee-list', [AccessPermissionsController::class, 'getDatatableEmployees'])->name('sysadmin.accesspermissions.employee.list');
    
    Route::get('sysadmin/accesspermissions/manageexistingaccess', [AccessPermissionsController::class, 'manageindex'])->name('sysadmin.accesspermissions.manageindex');
    Route::put('sysadmin/accesspermissions/manageexistingaccessupdate', [AccessPermissionsController::class, 'manageupdate']);
    
    // Route::get('sysadmin/access/manageexistingaccessdelete', [ManageExistingAccessController::class, 'destroy'])->name('sysadmin.access.manageexistingaccess');
    Route::get('sysadmin/accesspermissions/manageexistingaccessdelete/{model_id}', [AccessPermissionsController::class, 'managedestroy']);
    Route::delete('sysadmin/accesspermissions/manageexistingaccessdelete/{model_id}', [AccessPermissionsController::class, 'managedestroy'])->name('sysadmin.accesspermissions.manageexistingaccessdelete');
    
    Route::get('sysadmin/accesspermissions/get_access_entry/{role_id}/{model_id}', [AccessPermissionsController::class, 'get_access_entry']);
    Route::get('sysadmin/accesspermissions/manageexistingaccesslist', [AccessPermissionsController::class, 'getList'])->name('sysadmin.accesspermissions.manageexistingaccesslist');
    Route::get('sysadmin/accesspermissions/manageexistingaccessadmin/{user_id}', [AccessPermissionsController::class, 'getAdminOrgs'])->name('sysadmin.accesspermissions.manageexistingaccessadmin');
    // Route::get('sysadmin/accesspermissions/manageexistingaccessadmin', [AccessPermissionsController::class, 'getAdminOrgs'])->name('sysadmin.accesspermissions.manageexistingaccessadmin');
    Route::get('sysadmin/accesspermissions/accessedit/{id}', [AccessPermissionsController::class, 'manageedit'])->name('sysadmin.accesspermissions.accessedit');
    Route::post('sysadmin/accesspermissions/accessupdate/{id}', [AccessPermissionsController::class, 'manageupdate']);
    


});




});


Route::get('/sysadmin/org-organizations', [SysadminController::class,'getOrganizations']);
Route::get('/sysadmin/org-programs', [SysadminController::class,'getPrograms']);
Route::get('/sysadmin/org-divisions', [SysadminController::class,'getDivisions']);
Route::get('/sysadmin/org-branches', [SysadminController::class,'getBranches']);
Route::get('/sysadmin/org-level4', [SysadminController::class,'getLevel4']);


Route::get('sysadmin/level0', 'App\Http\Controllers\SysadminController@getOrgLevel0')->name('sysadmin.level0');
Route::get('sysadmin/level1/{id0}', 'App\Http\Controllers\SysadminController@getOrgLevel1')->name('sysadmin.level1');
Route::get('sysadmin/level2/{id0}/{id1}', 'App\Http\Controllers\SysadminController@getOrgLevel2')->name('sysadmin.level2');
Route::get('sysadmin/level3/{id0}/{id1}/{id2}', 'App\Http\Controllers\SysadminController@getOrgLevel3')->name('sysadmin.level3');
Route::get('sysadmin/level4/{id0}/{id1}/{id2}/{id3}', 'App\Http\Controllers\SysadminController@getOrgLevel4')->name('sysadmin.level4');
