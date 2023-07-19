<?php

namespace App\DataTables;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Gate;

class SubjectDataTable extends DataTable
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
        ->addColumn('action', function($row){
            $action = '';
            if(Gate::allows('read_subject')){
                $action = '<button type="button" data-id='.$row->id.' data-type="detail" class="btn btn-primary btn-sm action"><i class="ti-eye"></i></button>';
            }
            if(Gate::allows('update_subject')){
                $action .= ' <button type="button" data-id='.$row->id.' data-type="edit" class="btn btn-warning btn-sm action"><i class="ti-pencil"></i></button>';
            }
            if(Gate::allows('delete_subject')){
                $action .= ' <button type="button" data-id='.$row->id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
            }
            return $action;
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Subject $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Subject $model): QueryBuilder
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
                    ->setTableId('subject-table')
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
            Column::make('name')->title('Mata Pelajaran'),
            Column::make('code')->title('Kode Mata Pelajaran'),
            Column::make('description')->title('Deskripsi'),
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
        return 'SubjectDataTables_' . date('YmdHis');
    }
}
