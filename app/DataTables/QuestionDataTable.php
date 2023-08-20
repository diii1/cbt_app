<?php

namespace App\DataTables;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class QuestionDataTable extends DataTable
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
            ->addColumn('question', function ($row){
                return new HtmlString(html_entity_decode($row->question));
            })
            ->addColumn('option', function ($row){
                $options = [
                    'A.' => $row->option_a,
                    'B.' => $row->option_b,
                    'C.' => $row->option_c,
                    'D.' => $row->option_d,
                    'E.' => $row->option_e,
                ];

                $optionList = '<div class="row">';
                foreach ($options as $key => $option) {
                    $optionList .= '<div class="col-md-2 text-center">' . $key . '</div>';
                    $optionList .= '<div class="col-md-10">' . $option . '</div>';
                }
                $optionList .= '</div>';

                return new HtmlString(html_entity_decode($optionList));
            })
            ->addColumn('answer', function ($row){
                $answer = json_decode($row->answer);
                return new HtmlString(html_entity_decode($answer->value));
            })
            ->addColumn('action', function ($row){
                $action = '';
                if(Gate::allows('read_question')){
                    $action .= '<button type="button" data-id='.$row->id.' data-type="detail" class="btn btn-primary btn-sm action"><i class="ti-eye"></i></button>';
                }
                if(Gate::allows('update_question')){
                    $action .= ' <button type="button" data-id='.$row->id.' data-type="edit" class="btn btn-warning btn-sm action"><i class="ti-pencil"></i></button>';
                }
                if(Gate::allows('delete_question')){
                    $action .= ' <button type="button" data-id='.$row->id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
                }
                return $action;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Question $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Question $model): QueryBuilder
    {
        $query = $model->newQuery();

        $exam_id = request()->route('exam_id');
        if($exam_id){
            $query->where('exam_id', $exam_id);
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
                    ->setTableId('question-table')
                    ->parameters(['searchDelay' => 1500, 'responsive' => true, 'autoWidth' => false, 'searching' => false, ])
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
        $user = Auth::user();
        $columns = [
            Column::make('DT_RowIndex')->title('No')
                ->width(15)
                ->searchable(false)
                ->orderable(false),
            Column::computed('question')
                ->title('Pertanyaan'),
            Column::computed('option')
                ->title('Pilihan')
                ->width(300),
            Column::computed('answer')
                ->title('Jawaban')
                ->width(250),
        ];

        if($user && $user->hasRole('teacher')){
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
        return 'Question_' . date('YmdHis');
    }
}
