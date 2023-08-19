<?php

namespace App\DataTables;

use App\Models\Session;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class SessionDataTable extends DataTable
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
        ->editColumn('time_start', function($row){
            $time = Carbon::createFromTimeString($row->time_start, 'Asia/Jakarta');
            return $time->format('g:i A');
        })
        ->editColumn('time_end', function($row){
            $time = Carbon::createFromTimeString($row->time_end, 'Asia/Jakarta');
            return $time->format('g:i A');
        })
        ->addColumn('action', function($row){
            $action = '';
            if(Gate::allows('read_session')){
                $action = '<button type="button" data-id='.$row->id.' data-type="detail" class="btn btn-primary btn-sm action"><i class="ti-eye"></i></button>';
            }
            if(Gate::allows('update_session')){
                $action .= ' <button type="button" data-id='.$row->id.' data-type="edit" class="btn btn-warning btn-sm action"><i class="ti-pencil"></i></button>';
            }
            if(Gate::allows('delete_session')){
                $action .= ' <button type="button" data-id='.$row->id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
            }
            return $action;
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Session $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Session $model): QueryBuilder
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
                    ->setTableId('session-table')
                    ->parameters(['searchDelay' => 1500, 'responsive' => true, 'autoWidth' => false ])
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
                    //->dom('Bfrtip')
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
            Column::make('DT_RowIndex')
                ->title('No')
                ->width(15)
                ->searchable(false)
                ->orderable(false),
            Column::make('name')
                ->width(250)
                ->title('Nama Sesi'),
            Column::make('time_start')
                ->width(150)
                ->title('Waktu Mulai'),
            Column::make('time_end')
                ->width(150)
                ->title('Waktu Selesai'),
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
        return 'Session_' . date('YmdHis');
    }
}
