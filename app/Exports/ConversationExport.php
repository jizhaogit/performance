<?php

namespace App\Exports;

use App\Models\Conversation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMapping;


class ConversationExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    
    protected $conversations;

    function __construct($conversations) {
        // $this->id = $id;
        $this->conversations = $conversations;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->conversations;
    }

      // map what a single member row should look like
    // this method will iterate over each collection item
    public function map($conversation): array
    {
        return [
            $conversation->id,
            $conversation->topic->name,
            $conversation->user->name,
            $conversation->user->email,
            $conversation->user->reportingManager->name,
            $conversation->sign_off_time,
            $conversation->supervisor_signoff_time,
        ];
    }


    public function headings(): array
    {
        return ["id", "Topic", "User Name", "User Email", 'Reporting To',  'sign off', 'supervisor signoff'];
    }
}
