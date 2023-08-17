<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Exam\ExamParticipantService;
use App\Services\Exam\ExamService;
use App\DataTables\ListExamDataTable;
use App\DataTables\ExamParticipantDataTable;
use DataTables;
use Illuminate\Support\Facades\Gate;
use App\Models\Classes;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamParticipant;
use App\Models\SchoolProfile;
use Carbon\Carbon;

class ExamParticipantController extends Controller
{
    private $service;
    private $examService;

    public function __construct(ExamParticipantService $service, ExamService $examService)
    {
        $this->service = $service;
        $this->examService = $examService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListExamDataTable $dataTables)
    {
        $this->authorize('list_participant');
        $data['nav_title'] = 'Exam | List Exam';
        $data['title'] = 'Data Ujian';
        // $data['exams'] = $this->examService->getExams();
        // $data['button_add'] = 'Tambah Data Peserta';
        // return view('pages.exam.participant.index', ['data' => $data]);
        return $dataTables->render('pages.exam.participant.index', ['data' => $data]);
    }

    public function participant_list(ExamParticipantDataTable $dataTables, $id)
    {
        // $participants = $this->service->getExamParticipantByExamID($id);
        // $dataTables = DataTables::of($participants)
        //     ->addIndexColumn()
        //     ->addColumn('nis', function($row){
        //         return $row->student_nis;
        //     })
        //     ->addColumn('nisn', function($row){
        //         return $row->student_nisn;
        //     })
        //     ->addColumn('name', function($row){
        //         return $row->student_name;
        //     })
        //     ->addColumn('class', function($row){
        //         return $row->class_name;
        //     })
        //     ->addColumn('action', function($row){
        //         $action = '<div class="text-center">';
        //         if(Gate::allows('delete_participant')){
        //             $action .= ' <button type="button" data-id='.$row->id.' data-type="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>';
        //         }
        //         $action .= '</div>';
        //         return $action;
        //     })
        //     ->rawColumns(['action'])
        //     ->make(true);
        // return $dataTables;
        $this->authorize('list_participant');
        $exam = $this->examService->getExamByID($id);
        $data['nav_title'] = 'Participants';
        $data['title'] = 'Data Peserta Ujian';
        $data['button_add'] = 'Tambah Data Peserta';
        $data['exam'] = $exam;
        return $dataTables->render('pages.exam.participant.list', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create_participant');
        $data['action'] = route('participants.store');
        $data['type'] = 'create';
        $data['title'] = 'Tambah Data Peserta Ujian';

        $exam = Exam::with('subject', 'class', 'session')->where('id', $request->exam_id)->first();
        $participants = ExamParticipant::where('exam_id', $request->exam_id)->get()->pluck('student_id')->toArray();
        $class_id = $exam->class->id;
        if(isset($participants)){
            $students = Student::with('user')->whereNotIn('user_id', $participants)->get();
        }else{
            $students = Student::with('user')->where('class_id', isset($class_id))->get();
        }

        $data['exam'] = $exam;
        $data['students'] = $students;
        return view('pages.exam.participant.form', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        if(is_array($request->student)){
            foreach ($request->student as $value) {
                $data[] = [
                    'exam_id' => $request->exam_id,
                    'student_id' => $value,
                ];
            }
        }

        $inserted = $this->service->insertExamParticipant($data);

        if($inserted != []){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Peserta Ujian berhasil disimpan.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Peserta Ujian gagal disimpan.'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete_participant');
        $deleted = $this->service->deleteExamParticipant($id);

        if($deleted){
            return response()->json([
                'status' => 'success',
                'message' => 'Data Peserta Ujian berhasil dihapus.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Peserta Ujian gagal dihapus.'
        ], 500);
    }

    public function print_cards($id)
    {
        $this->authorize('card_participant');
        $data['students'] = $this->service->getParticipantCards($id);
        $data['exam'] = $this->examService->getExamByID($id);
        $data['school'] = SchoolProfile::first();
        // dd($data['students']);
        return view('pages.exam.participant.print', ['data' => $data]);
    }
}
