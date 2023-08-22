<?php

namespace App\DataTables;

use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ExamResultDataTable extends DataTable
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
            ->editColumn('nis', function($row) {
                $student_nis = Student::where('user_id', $row->student_id)->first()->nis;
                return $student_nis;
            })
            ->editColumn('nisn', function($row) {
                $student_nisn = Student::where('user_id', $row->student_id)->first()->nisn;
                return $student_nisn;
            })
            ->editColumn('name', function($row) {
                $student_name = Student::where('user_id', $row->student_id)->with('user')->first()->user->name;
                return $student_name;
            })
            ->editColumn('class', function($row) {
                $student_class = Student::where('user_id', $row->student_id)->with('class')->first()->class->name;
                return $student_class;
            })
            ->editColumn('score', function($row) {
                $score = $row->score;
                return $score;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ExamResult $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ExamResult $model): QueryBuilder
    {
        $query = $model->newQuery();

        $exam_id = request()->route('exam_id');
        if($exam_id) $query->where('exam_id', $exam_id);

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('examresult-table')
                    ->parameters(['searchDelay' => 1500, 'responsive' => true, 'autoWidth' => false ])
                    ->columns($this->getColumns())
                    ->minifiedAjax();
                    //->dom('Bfrtip')
                    // ->orderBy(1)
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
            // Column::computed('action')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('DT_RowIndex')->title('No')
                ->width(15)
                ->searchable(false)
                ->orderable(false),
            Column::make('nis')->title('NIS'),
            Column::make('nisn')->title('NISN'),
            Column::make('name')->title('Nama'),
            Column::make('class')
                ->title('Kelas')
                ->addClass('text-center'),
            Column::make('score')
                ->title('Nilai')
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
        return 'ExamResult_' . date('YmdHis');
    }
}
