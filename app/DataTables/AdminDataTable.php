<?php

namespace App\DataTables;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Gate;

class AdminDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return User::find($row->user_id)->name;
            })
            ->addColumn('email', function($row){
                return User::find($row->user_id)->email;
            })
            ->addColumn('action', function($row){
                $action = '';
                if(Gate::allows('change_password')){
                    $action = '<button type="button" data-id='.$row->user_id.' data-type="change_password" class="btn btn-secondary btn-sm action"><i class="ti-lock"></i></button>';
                }
                if(Gate::allows('update_admin')){
                    $action .= ' <button type="button" data-id='.$row->user_id.' data-type="edit" class="btn btn-warning btn-sm action"><i class="ti-pencil"></i></button>';
                }
                if(Gate::allows('delete_admin')){
                    if(auth()->user()->id == $row->user_id){
                        $action .= ' <button type="button" data-id='.$row->user_id.' data-type="delete" class="btn btn-danger btn-sm action" disabled><i class="ti-trash"></i></button>';
                    }else{
                        $action .= ' <button type="button" data-id='.$row->user_id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
                    }
                }
                return $action;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Admin $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Admin $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->parameters(['searchDelay' => 1500, 'responsive' => true, 'autoWidth' => false ])
                    ->setTableId('admin-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
                    // ->selectStyleSingle()
                    // ->buttons([
                    //     Button::make('excel'),
                    //     Button::make('csv'),
                    //     Button::make('pdf'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload')
                    // ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')
                ->width(15)
                ->searchable(false)
                ->orderable(false),
            Column::make('nip')->title('NIP'),
            Column::make('name')->title('Nama'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('No. HP'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(200)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Admin_' . date('YmdHis');
    }
}
