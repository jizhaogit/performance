<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserGoalCountExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{

    protected $users;

    function __construct($users) {
        // $this->id = $id;
        $this->users = $users;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return User::select('id','name','email','reporting_to')->get();
        
        return $this->users;

    }

     // map what a single member row should look like
    // this method will iterate over each collection item
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->reportingManager->name,
            $user->goals_count,
        ];
    }


    public function headings(): array
    {
        return ["id", "Name", "Email", "Reporting To", 'Active Goals Count'];
    }
}
