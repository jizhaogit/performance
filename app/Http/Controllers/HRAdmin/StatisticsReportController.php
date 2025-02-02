<?php

namespace App\Http\Controllers\HRAdmin;

use App\Models\Goal;
use App\Models\User;
use App\Models\GoalType;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Models\ConversationTopic;
use Illuminate\Support\Facades\DB;
use App\Exports\ConversationExport;
use App\Exports\UserGoalCountExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SharedEmployeeExport;
use App\Exports\ExcusedEmployeeExport;

class StatisticsReportController extends Controller
{
    //
    private $groups;
    
    public function __construct()
    {
        $this->groups = [
            '0' => [0,0],
            '1-5' => [1,5],
            '6-10' => [6,10],
            '>10' => [11,99999],
        ];

        $this->overdue_groups = [
            'overdue' => [-999999,0],
            '< 1 week' => [1,7],
            '< 1 month' => [8,30],
            '> 1 month' => [31,999999],
        ];
    
    }

    public function goalSummary(Request $request)
    {
       
        $types = GoalType::orderBy('id')->get();
        $types->prepend( new GoalType()  ) ;

        $data = array();

        $reportee_ids = User::where('id', Auth::id() )->first()->allreportees->pluck('id')->toArray();

        foreach($types as $type)
        {
            $goal_id = $type->id ? $type->id : '';

            $sql = User::select('id')
                    ->whereIn('id', $reportee_ids) 
                    ->where('acctlock', 0)
                    ->withCount(['goals' => function($q) use($type, $reportee_ids) {
                        $q->where('status', 'active')
                            ->whereIn('user_id', $reportee_ids) 
                            ->when( $type->id, function ($query) use ($type) {
                                return $query->where('goal_type_id', $type->id);
                            });
            }]);

            $users = $sql->get();
            // $total_count = $users->count();

            $data[$goal_id] = [ 
                'name' => $type->name ? ' ' . $type->name : '',
                'goal_type_id' => $goal_id,
                'average' => $users->avg('goals_count'),
                'groups' => []
            ];

            // each group 
            foreach($this->groups as $key => $range)
            {
                $subset = $users->whereBetween('goals_count', $range );
                array_push( $data[$goal_id]['groups'], [ 'name' => $key, 'value' => $subset->count(), 
                             'goal_id' => $goal_id, 
                             'ids' => $subset ? $subset->pluck('id')->toArray() : []
                ]);
            }
        }

        return view('hradmin.statistics.goalsummary',compact('data'));
    }

    public function goalSummaryExport(Request $request)
    {

        $selected_ids = $request->ids ? explode(',', $request->ids) : [];

        $sql = User::whereIn('id', $selected_ids)
                ->withCount(['goals' => function($q) use($request, $selected_ids) {
                        $q->where('status', 'active')
                            ->whereIn('user_id', $selected_ids) 
                            ->when( $request->goal  , function ($query) use ($request) {
                                return $query->where('goal_type_id', $request->goal);
                            });
                }]);

        $users = $sql->get();

        // filter if the 'range' pass 
        if (array_key_exists($request->range, $this->groups)) {
                $range = $this->groups[$request->range];
                $users =  $users->whereBetween('goals_count', $range );
        }

        $filename = 'Active Goals Per Employee.xlsx';
        if ($request->goal) {        
            $type = GoalType::where('id', $request->goal)->first();
            $filename = 'Active ' . ($type ? $type->name . ' ' : '') . 'Goals Per Employee.xlsx';
        }

        return Excel::download(new UserGoalCountExport($users), $filename);

    }

    public function conversationSummary(Request $request)
    {
        $reportee_ids = User::where('id', Auth::id() )->first()->allreportees->pluck('id')->toArray();
        // array_push($reportee_ids, Auth::id());

        // array_push($reportee_ids, 120053);
        // array_push($reportee_ids, 120007);
        // array_push($reportee_ids, 120002);
        // array_push($reportee_ids, 120011);

        // Chart1 -- Overdue
        $sql_2 = User::selectRaw("* , 
                        DATEDIFF (
                            COALESCE (
                                (select GREATEST( max(sign_off_time) , max(supervisor_signoff_time) )  
                                    from conversations A 
                                where A.user_id = users.id
                                    and signoff_user_id is not null      
                                    and supervisor_signoff_id is not null)
                                , (joining_date) 
                            ) 
                        , DATE_ADD( DATE_FORMAT(sysdate(), '%Y-%m-%d'), INTERVAL -122 day) )
                    as overdue_in_days") 
            ->whereIn('id', $reportee_ids);
            
        $next_due_users = $sql_2->get();
        $data = array();

        // Chart1 -- Overdue
        $data['chart1']['chart_id'] = 1;
        $data['chart1']['title'] = 'Next Conversation Due';
        $data['chart1']['legend'] = array_keys($this->overdue_groups);
        $data['chart1']['groups'] = array();
        foreach($this->overdue_groups as $key => $range)
        {
            $subset = $next_due_users->whereBetween('overdue_in_days', $range );
            array_push( $data['chart1']['groups'],  [ 'name' => $key, 'value' => $subset->count(), 
                            'ids' => $subset ? $subset->pluck('id')->toArray() : [] ]);
        }

        // SQL for Chart 2 & 3
        $sql = Conversation::selectRaw("* , 
                DATEDIFF (
                    COALESCE (
                            (select GREATEST( max(sign_off_time) , max(supervisor_signoff_time) )  
                                from conversations A 
                            where A.user_id = conversations.user_id
                                and signoff_user_id is not null      
                                and supervisor_signoff_id is not null),
                            (select joining_date from users where id = conversations.user_id)
                        ) 
                , DATE_ADD( DATE_FORMAT(sysdate(), '%Y-%m-%d'), INTERVAL -122 day) ) * -1
            as overdue_in_days") 
        ->where(function($query) use ($reportee_ids) {
                $query->whereIn('user_id', $reportee_ids)
                    ->orWhereHas('conversationParticipants', function($query) use ($reportee_ids) {
                        $query->whereIn('participant_id', $reportee_ids);
                    });
        })
        //->whereIn('user_id', $reportee_ids)
        ->where(function ($query)  {
        return $query->whereNull('signoff_user_id')
                    ->orWhereNull('supervisor_signoff_id');
        });
        $conversations = $sql->get();


        // Chart2 -- Open Conversation
        $topics = ConversationTopic::select('id','name')->get();
        $data['chart2']['chart_id'] = 2;
        $data['chart2']['title'] = 'Topic: Open Conversations';
        $data['chart2']['legend'] = $topics->pluck('name')->toArray();
        $data['chart2']['groups'] = array();

        $open_conversations = $conversations;
        foreach($topics as $topic)
        {
            $subset = $open_conversations->where('conversation_topic_id', $topic->id );
            array_push( $data['chart2']['groups'],  [ 'name' => $topic->name, 'value' => $subset->count(),
                            'ids' => $subset ? $subset->pluck('id')->toArray() : []
                        ]);
        }    

        // Chart 3 -- Completed Conversation by Topics
        $data['chart3']['chart_id'] = 3;
        $data['chart3']['title'] = 'Topic: Completed Conversations';
        $data['chart3']['legend'] = $topics->pluck('name')->toArray();
        $data['chart3']['groups'] = array();
        $completed_conversations = Conversation::where(function($query) use ($reportee_ids) {
            $query->whereIn('user_id', $reportee_ids)
                ->orWhereHas('conversationParticipants', function($query) use ($reportee_ids) {
                    $query->whereIn('participant_id', $reportee_ids);
                });
        })
        ->where(function ($query)  {
                return $query->whereNotNull('signoff_user_id')
                             ->whereNotNull('supervisor_signoff_id');
        })->get();

        foreach($topics as $topic)
        {
            $subset = $completed_conversations->where('conversation_topic_id', $topic->id );
            array_push( $data['chart3']['groups'],  [ 'name' => $topic->name, 'value' => $subset->count(), 
                    'ids' => $subset ? $subset->pluck('id')->toArray() : []
                ]);
        }    

        return view('hradmin.statistics.conversationsummary',compact('data'));

    }


    public function conversationSummaryExport(Request $request)
    {
        // dd($request);
        $selected_ids = $request->ids ? explode(',', $request->ids) : [];

        // Chart1 -- Overdue
        // $sql = User::selectRaw('*,
        //         DATE_ADD (
        //             COALESCE (                       
        //                 (select GREATEST( max(sign_off_time) , max(supervisor_signoff_time) )
        //                     from conversations A
        //                 where A.user_id = users.id 
        //                     and signoff_user_id is not null 
        //                     and supervisor_signoff_id is not null),   joining_date 

        //             ), INTERVAL 123 day) as next_due_date')
        //         ->whereIn('id', $selected_ids);
        $sql = User::selectRaw("*,
                    DATE_ADD( DATE_FORMAT( COALESCE (                       
                       (select GREATEST( max(sign_off_time) , max(supervisor_signoff_time) )
                            from conversations A
                        where A.user_id = users.id 
                            and signoff_user_id is not null 
                            and supervisor_signoff_id is not null)
                            ,joining_date), '%Y-%m-%d'), INTERVAL 122 day) as next_due_date")
                ->whereIn('id', $selected_ids);

        $next_due_users = $sql->get();

        // Chart 2 and 3
        $sql = Conversation::selectRaw('* , 
                    DATE_ADD(
                        COALESCE (
                            (select GREATEST( max(sign_off_time) , max(supervisor_signoff_time) )  
                                from conversations A 
                            where A.user_id = conversations.user_id
                                and signoff_user_id is not null      
                                and supervisor_signoff_id is not null), 
                            (select joining_date from users where id = conversations.user_id)
                        ), INTERVAL 122 day)
                    as next_due_date')
                ->whereIn('id', $selected_ids)
                ->with('topic');

        $conversations = $sql->get();

        $data = null;
        $filename = 'Conversations.xlsx';
        switch ($request->chart) {
            case 1:
                $filename = 'Next Conversation Due.xlsx';
                $data = $next_due_users;
                break;
            case 2:
                $filename = 'Open Conversation By Topic.xlsx';
                $data = $conversations;
                break;
            case 3:
                $filename = 'Completed Conversation By Topic.xlsx';
                $data = $conversations;
                break;    
        }
        
        return Excel::download(new ConversationExport($request->chart, $data), $filename);

    }

    public function sharedsummary(Request $request) 
    {


        $reportee_ids = User::where('id', Auth::id() )->first()->allreportees->pluck('id')->toArray();

        $sql = User::selectRaw("* ,
                case when (select count(*) from shared_profiles A where A.shared_id = users.id) > 0 then 'Yes' else 'No' end as shared")
            ->whereIn('id', $reportee_ids);

        $users = $sql->get();

        // Chart2 -- Open Conversation
        $legends = ['Yes', 'No'];
        $data['chart1']['chart_id'] = 1;
        $data['chart1']['title'] = 'Shared Status';
        $data['chart1']['legend'] = $legends;
        $data['chart1']['groups'] = array();

        //$open_conversations = $conversations;
        foreach($legends as $legend)
        {
            $subset = $users->where('shared', $legend);
            array_push( $data['chart1']['groups'],  [ 'name' => $legend, 'value' => $subset->count(),
                            'ids' => $subset ? $subset->pluck('id')->toArray() : []
                        ]);
        }    

        // dd([ $users , $data ]);

        return view('hradmin.statistics.sharedsummary',compact('data'));

    } 

    public function sharedSummaryExport(Request $request) 
    {

      $selected_ids = $request->ids ? explode(',', $request->ids) : [];

      $sql = User::selectRaw("* ,
          case when (select count(*) from shared_profiles A where A.shared_id = users.id) > 0 then 'Yes' else 'No' end as shared")
              ->whereIn('id', $selected_ids);

      $users = $sql->get();

      $filename = 'Shared Employees.xlsx';
      
      return Excel::download(new SharedEmployeeExport($users), $filename);


    }


    public function excusedsummary(Request $request) {

        $reportee_ids = User::where('id', Auth::id() )->first()->allreportees->pluck('id')->toArray();

        $sql = User::selectRaw("*, case when excused_start_date is not null then 'Yes' else 'No' end as excused")
            ->whereIn('id', $reportee_ids);

        $users = $sql->get();

        // Chart2 -- Open Conversation
        $legends = ['Yes', 'No'];
        $data['chart1']['chart_id'] = 1;
        $data['chart1']['title'] = 'Excused Status';
        $data['chart1']['legend'] = $legends;
        $data['chart1']['groups'] = array();

        //$open_conversations = $conversations;
        foreach($legends as $legend)
        {
            $subset = $users->where('excused', $legend);
            array_push( $data['chart1']['groups'],  [ 'name' => $legend, 'value' => $subset->count(),
                            'ids' => $subset ? $subset->pluck('id')->toArray() : []
                        ]);
        }    

        // dd([$sql->toSql(), $users, $data]);        

        return view('hradmin.statistics.excusedsummary',compact('data'));


    } 


    public function excusedSummaryExport(Request $request) {


      // dd($request);
      $selected_ids = $request->ids ? explode(',', $request->ids) : [];

      $sql = User::selectRaw("*, case when excused_start_date is not null then 'Yes' else 'No' end as excused")
              ->whereIn('id', $selected_ids);

      $users = $sql->get();

      $filename = 'Excused Employees.xlsx';
      
      return Excel::download(new ExcusedEmployeeExport($users), $filename);


    }

}
