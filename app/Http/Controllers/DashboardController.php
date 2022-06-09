<?php

namespace App\Http\Controllers;

use App\Models\SharedProfile;
use App\Models\DashboardNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index() {
        $greetings = "";

        /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");

        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");

        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            $greetings = "Good Morning";
        } else

        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            $greetings = "Good Afternoon";
        } else

        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            $greetings = "Good Evening";
        } else

        if ($time >= "19") {
            $greetings = "Hello";
        }

        // $tab = (Route::current()->getName() == 'dashboard.notifications') ? 'notifications' : 'todo';
        $tab = (Route::current()->getName() == 'dashboard.notifications') ? 'notifications' : 'notifications';
        // $notifications = DashboardNotification::where('user_id', Auth::id())->get();
        $notifications = DashboardNotification::where('user_id', Auth::id())->orderby('status', 'asc')->orderby('created_at', 'desc')->paginate(8);
        $notifications_unread = DashboardNotification::where('user_id', Auth::id())->where('status', null);
        $supervisorTooltip = 'If your current supervisor in the Performance Development Platform is incorrect, please have your supervisor submit an AskMyHR ticket and choose the category: <span class="text-primary">My Team or Organization > HR Software Systems Support > Position / Reporting Updates</span>';        
        $sharedList = SharedProfile::where('shared_id', Auth::id())->with('sharedWithUser')->get();
        $profilesharedTooltip = 'If this information is incorrect, please discuss with your supervisor first and escalate to your organization\'s Strategic Human Resources shop if you are unable to resolve.';
        return view('dashboard.index', compact('greetings', 'tab', 'supervisorTooltip', 'sharedList', 'profilesharedTooltip', 'notifications', 'notifications_unread'));
    }

    public function destroy($id)
    {
        $notification = DashboardNotification::where('id',$id)->delete();
        return redirect()->back();
    }

    public function destroyall(Request $request)
    {
        $ids = $request->ids;
        DashboardNotification::wherein('id',explode(",",$ids))->delete();
        return redirect()->back();
        // return redirect()->to('/route');
        // header("Refresh:0");
        // return back();
        // window.location.reload();
        //return route::get('dashboard.notifications');
        //return view('dashboard.partials.notifications');
        // return Redirect()->route('dashboard.notifications');
        // return response()->json(['success'=>"Notification(s) deleted successfully."]);
    }

    public function updatestatus(Request $request)
    {
        $ids = $request->ids;
        // var check = confirm($ids);
        // dd("RESULTS: " . $ids);
        DashboardNotification::wherein('id',explode(",",$ids))->update(['status' => 'R']);
        // return route::get('dashboard.notifications');
        return redirect()->back();
        // $notification = DashboardNotification::where('id', 4)->update(['status' => 'S']);
        // window.location.reload();
        // return redirect()->to('/route');
        // header("Refresh:0");
        // return back();
        // return response()->json(['success'=>"Notification(s) updated successfully."]);
    }

    public function resetstatus(request $request)
    {
        $ids = $request->ids;
        DashboardNotification::wherein('id',explode(",",$ids))->update(['status' => null]);
        // DashboardNotification::where('id',5)->update(['status' => 'D']);
        // header("Refresh:0");
        // return back();
        // window.location.reload();
        // return redirect()->to('/route');
        return redirect()->back();
        // return response()->json(['success'=>"Notification(s) updated successfully."]);
    }
    
    
    public function revertIdentity(Request $request) {
         $oldUserId = $request->session()->get('existing_user_id');
         Auth::loginUsingId($oldUserId);
         $request->session()->forget('existing_user_id');
         $request->session()->forget('user_is_switched');
         //return redirect()->back();
         return redirect()->to('/');

    }

}
