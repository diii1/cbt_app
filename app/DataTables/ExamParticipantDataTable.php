<?php

namespace App\DataTables;

use App\Models\ExamParticipant;
use App\Models\Exam;
use App\Models\Student;
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

class ExamParticipantDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // $exam = Exam::where('id', $row->exam_id);
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
                $student_name = User::where('id', $row->student_id)->first()->name;
                return $student_name;
            })
            ->editColumn('class', function($row) {
                $student_class = Student::where('user_id', $row->student_id)->with('class')->first()->class->name;
                return $student_class;
            })
            ->addColumn('action', function($row) {
                $action = '';

                if(Gate::allows('delete_participant')){
                    $action .= ' <button type="button" data-id='.$row->id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
                }
                return $action;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ExamParticipant $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ExamParticipant $model): QueryBuilder
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
                    ->setTableId('examparticipant-table')
                    ->parameters(['searchDelay' => 1500, 'responsive' => true, 'autoWidth' => false ])
                    ->columns($this->getColumns())
                    ->minifiedAjax();
                    // ->orderBy(1)
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
            Column::make('DT_RowIndex')->title('No')
                ->width(15)
                ->searchable(false)
                ->orderable(false),
            Column::make('nis')->title('NIS'),
            Column::make('nisn')->title('NISN'),
            Column::make('name')->title('Nama'),
            Column::make('class')->title('Kelas'),
            Column::computed('action')
                ->title('Aksi')
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
        return 'ExamParticipant_' . date('YmdHis');
    }
}
