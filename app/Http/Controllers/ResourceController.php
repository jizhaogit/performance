<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Tag;

class ResourceController extends Controller
{
    public function userguide()
    {
        return view('resource.user-guide');
    }
    public function goalsetting(Request $request)
    {
        //get goal tags
        $tags = Tag::all()->toArray();
        $t = $request->t;
        
        $data = [
            [
                'question' => 'What is goal setting?',
                'answer' => 'Goal setting is a process of working towards what you want to do or who you want to be.'
            ],
            [
                'question' => 'Why are goals important?',
                'answer_file' => '2'
            ],
            [
                'question' => 'Elements of effective goals(the Five C’s)',
                'answer_file' => '3'
            ],
            [
                'question' => 'What does a good goal statement look like?',
                'answer_file' => '4'
            ],
            [
                'question' => 'What are goal tags?',
                'answer_file' => '8'
            ],
            [
                'question' => 'Examples of Work Goals',
                'answer_file' => '5'
            ],
            [
                'question' => 'Examples of Learning Goals',
                'answer_file' => '6'
            ],
            [
                'question' => 'Examples of Career Goals',
                'answer_file' => '7'
            ],
        ];
        return view('resource.goal-setting', compact('data', 'tags', 't'));
    }
    public function conversations()
    {
      $data = [
          [
              'question' => 'What is a performance development conversation?',
              'answer' => 'Any conversation about an employee and their work can be considered a performance development conversation. They can be informal check-ins, regular 1-on-1’s, recognition for a job well done, feedback, or more formal conversations when trying to modify an employee’s behaviour.'
          ],
          [
              'question' => 'Why are conversations important?',
              'answer_file' => '2'
          ],
          [
              'question' => 'When are conversations effective?',
              'answer_file' => '3'
          ],
          [
              'question' => 'Elements of a meaningful conversation',
              'answer_file' => '4'
          ],
          [
              'question' => 'Elements of effective feedback',
              'answer_file' => '5'
          ],
      ];
         return view('resource.conversations', compact('data'));
    }
    public function contact()
    {
         return view('resource.contact');
    }
}
