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
            'overdue' => [0,0],
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
        array_push($reportee_ids, Auth::id());

        array_push($reportee_ids, 120053);
        array_push($reportee_ids, 120007);
        array_push($reportee_ids, 120002);
        array_push($reportee_ids, 120011);

        $sql = Conversation::selectRaw('* , 
                        DATEDIFF (
                            COALESCE (
                                (select joining_date from users where id = conversations.user_id),
                                (select GREATEST( max(sign_off_time) , max(supervisor_signoff_time) )  
                                    from conversations A 
                                where A.user_id = conversations.user_id
                                    and signoff_user_id is not null      
                                    and supervisor_signoff_id is not null) 
                                ) 
                        , DATE_ADD(SYSDATE(), INTERVAL -120 day) ) * -1
                    as overdue_in_days') 
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
        $data = array();

        // Chart1 -- Overdue
        $data['chart1']['chart_id'] = 1;
        $data['chart1']['title'] = 'Next Conversation Due';
        $data['chart1']['legend'] = array_keys($this->overdue_groups);
        $data['chart1']['groups'] = array();
        foreach($this->overdue_groups as $key => $range)
        {
            $subset = $conversations->whereBetween('overdue_in_days', $range );
            array_push( $data['chart1']['groups'],  [ 'name' => $key, 'value' => $subset->count(), 
                            'ids' => $subset ? $subset->pluck('id')->toArray() : [] ]);
        }

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

        $sql = Conversation::whereIn('id', $selected_ids)
                ->with('topic');

        $conversations = $sql->get();

        $filename = 'Conversations.xlsx';
        switch ($request->chart) {
            case 1:
                $filename = 'Next Conversation Due.xlsx';
                break;
            case 2:
                $filename = 'Open Conversions By Topic.xlsx';
                break;
            case 3:
                $filename = 'Completed Conversions By Topic.xlsx';
                break;    
        }
        
        return Excel::download(new ConversationExport($conversations), $filename);
    }


}
