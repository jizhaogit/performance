<?php

namespace App\Http\Controllers;

use App\DataTables\MyEmployeesDataTable;
use App\DataTables\SharedEmployeeDataTable;
use App\Http\Requests\Goals\AddGoalToLibraryRequest;
use App\Http\Requests\MyTeams\ShareProfileRequest;
use App\Http\Requests\MyTeams\UpdateProfileSharedWithRequest;
use App\Http\Requests\ShareMyGoalRequest;
use App\Models\ConversationTopic;
use App\Models\Goal;
use App\Models\GoalType;
use App\Models\Participant;
use App\Models\SharedProfile;
use App\Models\User;
use App\Models\ExcusedReasons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class MyTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myEmployees(MyEmployeesDataTable $myEmployeesDataTable, SharedEmployeeDataTable $sharedEmployeeDataTable)
    {
        $goaltypes = GoalType::all();
        $eReasons = ExcusedReasons::all();
        $conversationTopics = ConversationTopic::all();
        $participants = Participant::all();

        $goals = Goal::where('user_id', Auth::id())
            ->with('user')
            ->with('sharedWith')
            ->with('goalType')->get();
        $employees = $this->myEmployeesAjax();
        // dd($goals[0]->sharedWith);
        $type = '';
        $myEmpTable = $myEmployeesDataTable->html();
        $sharedEmpTable = $sharedEmployeeDataTable->html();
        return view('my-team/my-employees',compact('goals', 'employees', 'goaltypes', 'conversationTopics', 'participants', 'type', 'myEmpTable', 'sharedEmpTable', 'eReasons'));
        // return $myEmployeesDataTable->render('my-team/my-employees',compact('goals', 'employees', 'goaltypes', 'conversationTopics', 'participants', 'type'));
    }

    public function myEmployeesTable(MyEmployeesDataTable $myEmployeesDataTable) {
        return $myEmployeesDataTable->render('my-team/my-employees');
    }

    public function sharedEmployeesTable(SharedEmployeeDataTable $sharedEmployeeDataTable) {
        return $sharedEmployeeDataTable->render('my-team/my-employees');
    }

    public function myEmployeesAjax() {
        // TODO: Map Once we have relationship

        return User::whereIn("id", [1,2,3])
                    /* ->orWhere('name', 'LIKE', 'PSA%') */
                    ->get();
    }

    public function getProfileSharedWith($user_id) {
        $sharedProfiles = SharedProfile::where('shared_id', $user_id)->with(['sharedWith' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        return view('my-team.partials.profile-shared-with', compact('sharedProfiles'));
        // return $this->respondeWith($sharedProfiles);
    }

    public function updateProfileSharedWith($shared_profile_id, UpdateProfileSharedWithRequest $request) {
        $sharedProfile = SharedProfile::findOrFail($shared_profile_id);
        $input = $request->validated();
        $update = [];
        if ($input['action'] !== 'stop') {
            if($input['action'] === 'comment') {
                $update['comment'] = $input['comment'];
            }
            else if ($input['action'] === 'items') {
                $update['shared_item'] = $input['shared_item'];
            }
            $sharedProfile->update($update);
            /// $sharedProfile->save();
            return $this->respondeWith($sharedProfile);
        }

        $sharedProfile->delete();
        return $this->respondeWith('');
    }

    public function getProfileExcused($user_id) {
        $excusedreasons = ExcusedReasons::all();
        $excusedprofile = DB::table(users)
            ->where('id', $user_id)
            ->select('id', 'name', 'excused_start_date', 'excused_end_date')
            ->get();
        return view('my-team.partials.employee-excused-modal', $excusedreasons);
        // return $this->respondeWith($sharedProfiles);
    }

    public function updateExcuseDetails(Request $request, $id)
    {
        $this->validate($request, [
            'excused_start_date' => 'nullable|date_format:Y-m-d',
            'excused_end_date' => 'nullable|date_format:Y-m-d'
            // if ($this->excused_start_date) {
            //   'excused_end_date' .= '|after_or_equal:excused_start_date';
            // }
        ]);

        $excused = User::find('id', $id);
        $excused->excused_start_date = $request->excused_start_date;
        $excused->excused_end_date = $request->excused_end_date;
        $excused->excused_reason_id = $request->excused_reason_id;
        $excused->save();

        return response()->json(['success' => true, 'message' => 'Participant Excused settings updated successfully']);
    }

    public function shareProfile(ShareProfileRequest $request) {
        $input = $request->validated();
        // dd($input);

        $insert = [
            'shared_by' => Auth::id(),
            'shared_item' => $input['items_to_share'],
            'shared_id' => $input['shared_id'],
            'comment' => $input['reason']
        ];

        $sharedProfile = [];
        DB::beginTransaction();
        foreach ($input['share_with_users'] as $user_id) {
            $insert['shared_with'] = $user_id;
            array_push($sharedProfile, SharedProfile::updateOrCreate($insert));
        }

        DB::commit();
        return $this->respondeWith($sharedProfile);
    }

    public function userList(Request $request) {
        $search = $request->search;
        return $this->respondeWith(User::where('name', 'LIKE', "%{$search}%")->paginate());
    }

    public function performanceStatistics()
    {
        $goaltypes = GoalType::all();
        $goals = Goal::where('user_id', Auth::id())
            ->with('user')
            ->with('goalType')->get();
        $employees = $this->myEmployeesAjax();
        return view('my-team/performance-statistics', compact('goals','employees', 'goaltypes'));
    }
    public function goalsHierarchy()
    {
        $goals = Goal::where('user_id', Auth::id())
            ->with('user')
            ->with('goalType')->get();
        $employees = $this->myEmployeesAjax();

        return view('my-team/goals-hierarchy', compact('goals','employees', 'goaltypes'));
    }

    public function syncGoals(ShareMyGoalRequest $request) {
        $input = $request->validated();
        if($request->has("share_with")) {
            $shareWith = $request->share_with;
            foreach ($shareWith as $goalId => $userIds) {
                $goal = Goal::find($goalId);
                $goal->sharedWith()->sync(array_filter($userIds));
            }
        }
        if($request->has("is_shared")) {
            $isSharedArray = $input['is_shared'];
            foreach ($isSharedArray as $goalId => $isShared) {
                if (!(bool) $isShared) {
                    $goal = Goal::find($goalId);
                    $goal->sharedWith()->detach();
                }
            }
            // dd((bool)$input['is_shared'][995]);
        }
        return redirect()->back();
    }

    public function viewProfileAs($id, Request $request) {
        // TODO: Get it from Database.
        $reporting_users = [1,2,3];
        if (session()->has('original-auth-id') ? session()->get('original-auth-id') : Auth::id() === 1901) {
            $reporting_users = [998, 999];
        }
        if(in_array($id, $reporting_users)) {
            session()->put('view-profile-as', $id);
            if (!session()->has('original-auth-id')) {
                session()->put('original-auth-id', Auth::id());
            }
            Auth::loginUsingId($id);
        }
        return (url()->previous() === Route('my-team.my-employee')) ? redirect()->route('goal.current') : redirect()->back();
    }
    public function viewDirectReport($id, Request $request) {
        $myEmployeesDataTable = new MyEmployeesDataTable($id);
        $myEmployeesDataTable->setRoute(route('my-team.view-profile-as.direct-report', $id));
        if ($request->ajax()) {
            return $myEmployeesDataTable->render('my-team/my-employees');
        }
        $supervisorList = [];
        if (in_array($id, [1,2,3])) {
            array_push($supervisorList, 'Supervisor');
        }
        $directReports = $myEmployeesDataTable->html();
        $userName = User::find($id)->name;
        return view('my-team.direct-report', compact('directReports', 'userName', 'supervisorList'));
    }

    public function returnToMyProfile() {
        Auth::loginUsingId(session()->get('original-auth-id'));
        session()->forget('original-auth-id');
        session()->forget('view-profile-as');
        return redirect()->route('my-team.my-employee');
    }

    public function addGoalToLibrary(AddGoalToLibraryRequest $request) {
        $input = $request->validated();
        $input['user_id'] = Auth::id();
        $input['is_library'] = true;
        $share_with = $input['share_with'];

        unset($input['share_with']);
        DB::beginTransaction();
        $goal = Goal::create($input);
        $goal = $goal->sharedWith()->sync($share_with);
        DB::commit();
        return response()->json(['success' => true, 'message' => 'Goal added to library successfully']);
        // return redirect()->back();
    }

}
