<?php

namespace App\DataTables;

use App\Models\Exam;
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
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamDataTable extends DataTable
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
			->editColumn('type', function($row){
				return $row->type == 'pts' ? 'PTS' : 'PAS';
			})
            ->editColumn('date', function($row){
                $date = Carbon::parse($row->date)->locale('id');
                $date->settings(['formatFunction' => 'translatedFormat']);
                return $date->format('d F Y');
            })
            ->editColumn('session_id', function($row){
                $session = Session::find($row->session_id);
                $time_start = Carbon::createFromTimeString($session->time_start, 'Asia/Jakarta');
                $time_end = Carbon::createFromTimeString($session->time_end, 'Asia/Jakarta');

                return $time_start->format('H:i') . ' - ' . $time_end->format('H:i').' WIB';
            })
            ->addColumn('action', function($row){
                $action = '';
                if(Gate::allows('update_exam')){
                    $button = $row->is_active == 1 ? 'btn-success' : 'btn-secondary';
                    $title = $row->is_active == 1 ? 'Aktif' : 'Tidak Aktif';
                    $action .= '<button type="button" data-id='.$row->id.' data-type="update_status" class="btn '.$button.' btn-sm action">'.$title.'</button>';
                }
                if(Gate::allows('read_exam')){
                    $action .= ' <button type="button" data-id='.$row->id.' data-type="detail" class="btn btn-primary btn-sm action"><i class="ti-eye"></i></button>';
                }
                if(Gate::allows('update_exam')){
                    $action .= ' <button type="button" data-id='.$row->id.' data-type="edit" class="btn btn-warning btn-sm action"><i class="ti-pencil"></i></button>';
                }
                if(Gate::allows('delete_exam')){
                    $action .= ' <button type="button" data-id='.$row->id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
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
        $query = $model->newQuery();

        $user = Auth::user();
        if($user && $user->hasRole('teacher')){
            $query->where('teacher_id', $user->id)->where('is_active', 1);
        }

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
                    ->setTableId('exam-table')
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
        $user = Auth::user();
        $columns = [
            Column::make('DT_RowIndex')->title('No')
                ->width(15)
                ->searchable(false)
                ->orderable(false),
            Column::make('title')
                ->title('Judul Ujian'),
			Column::make('type')
                ->title('Tipe Ujian'),
            Column::make('date')
                ->title('Tanggal'),
            Column::make('class_name')
                ->title('Kelas'),
            Column::make('session_id')
                ->title('Sesi Ujian'),
            Column::make('subject_name')
                ->title('Mata Pelajaran'),
        ];

        if($user && $user->hasRole('admin')){
            $columns[] = Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Exam_' . date('YmdHis');
    }
}
