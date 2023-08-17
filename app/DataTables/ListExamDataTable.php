<?php

namespace App\DataTables;

use App\Models\Exam;
use App\Models\Session;
use App\Models\Teacher;
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
use Illuminate\Support\Facades\Auth;

class ListExamDataTable extends DataTable
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
            ->editColumn('session_id', function($row){
                $session = Session::find($row->session_id);
                $time_start = Carbon::createFromTimeString($session->time_start, 'Asia/Jakarta');
                $time_end = Carbon::createFromTimeString($session->time_end, 'Asia/Jakarta');

                return $time_start->format('g:i') . ' - ' . $time_end->format('g:i').' WIB';
            })
            ->editColumn('date', function($row){
                $date = Carbon::parse($row->date)->locale('id');
                $date->settings(['formatFunction' => 'translatedFormat']);
                return $date->format('d F Y');
            })
            ->addColumn('action', function($row){
                $user = Auth::user();
                $action = '';

                if($user && $user->hasRole('teacher')){
                    if(Gate::allows('list_question')){
                        $action .= '<a href="'. route('questions.list', $row->id) .'" class="btn btn-sm btn-primary"><i class="ti-list"></i>&nbsp; Data Soal</a>';
                    }
                }

                if($user && $user->hasRole('admin')){
                    if(Gate::allows('list_participant')){
                        $action .= '<a href="'. route('questions.list', $row->id) .'" class="btn btn-sm btn-primary m-1"><i class="ti-list"></i>&nbsp; Data Soal</a>';
                        $action .= '<a href="'. route('participants.list', $row->id) .'" class="btn btn-sm btn-primary m-1"><i class="ti-list"></i>&nbsp; Data Peserta</a>';
                    }
                }

                return $action;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Exam $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Exam $model): QueryBuilder
    {
        if(auth()->user()->hasRole('guru')){
            $teacher_id = Teacher::where('user_id', auth()->user()->id)->first()->id;
            return $model->newQuery()->where('teacher_id', $teacher_id)->where('is_active', 1);
        }

        return $model->newQuery()->where('is_active', 1);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('listexam-table')
                    ->parameters(['searchDelay' => 1500, 'responsive' => true, 'autoWidth' => false ])
                    ->columns($this->getColumns())
                    ->minifiedAjax();
                    // ->orderBy(1);
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
            Column::make('title')
                ->title('Judul Ujian'),
            Column::make('session_id')
                ->width(150)
                ->title('Sesi Ujian'),
            Column::make('date')
                ->width(200)
                ->addClass('text-center')
                ->title('Tanggal'),
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
        return 'ListExam_' . date('YmdHis');
    }
}
