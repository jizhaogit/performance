<?php

namespace App\DataTables;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SwitchIdentityDataTable extends DataTable
{
    protected $id;
    protected $route;

    public function __construct($id = null) {
        $this->id = $id;
        if($this->id == null) {
            $this->id = Auth::id();
        }
        $this->route = null;
    }

    public function setRoute($route) {
        $this->route = $route;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return view('sysadmin.switch-identity.partials.action', compact(["row"])); // $row['id'];
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $reporting_users = User::find($this->id)->usersUserIds();
        //dd($reporting_users);
        return $model->newQuery()->whereIn('id', $reporting_users);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $route = $this->route;
        if (!$route) {
            $route = route('sysadmin.switch-identity-table');
        }
        error_log('$route = ' . $route);
        return $this->builder()
            ->setTableId('switch-identity-table')
            ->columns($this->getColumns())
            ->minifiedAjax($route)
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->searching(true)
            ->ordering(true)
            ->parameters([
                'autoWidth' => false
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            new Column([
                'title' => 'User ID',
                'data' => 'id',
                'name' => 'id'
            ]),
            new Column([
                'title' => 'Employee name',
                'data' => 'name',
                'name' => 'name'
            ]),
            new Column([
                'title' => 'Email',
                'data' => 'email',
                'name' => 'email'
            ]),
        ];
    }
}
