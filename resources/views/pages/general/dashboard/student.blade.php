@extends('layouts.student.app')

@push('css')
    <style>
        .student-info tr td{
            padding: 10px
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>Informasi Data Diri</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="w-100 student-info">
                                    <tr>
                                        <td>NIS</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->nis }}</td>
                                    </tr>
                                    <tr>
                                        <td>NISN</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Lahir</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->birth_date->format('j F Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="w-100 student-info">
                                    <tr>
                                        <td>Alamat Email</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelas</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->class->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td> : </td>
                                        <td>{{ $data['student']->address ?? '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Ujian | <span class="text-secondary">{{ $data['date']->format('l, j F Y') }}</span></h4>
                </div>
                <div class="card-body">
                    <div class="accordion accordion-space" id="accordionExample2">
                        @if (count($data['exams']) >= 1)
                            @foreach ($data['exams'] as $item)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne1">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $item->exam->code }}"
                                            aria-expanded="true" aria-controls="{{ $item->exam->code }}">
                                            {{ $item->exam->code }} |
                                            {{ $item->exam->title }} |
                                            @php
                                                $time_start = \Carbon\Carbon::createFromTimeString($item->exam->session->first()->time_start, 'Asia/Bangkok');
                                                $time_end = \Carbon\Carbon::createFromTimeString($item->exam->session->first()->time_end, 'Asia/Bangkok');
                                                $exam_date = \Carbon\Carbon::parse($item->exam->date)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
                                            @endphp
                                            {{ $exam_date->format('d F Y') }} |
                                            {{ $time_start->format('g:i') }}-{{ $time_end->format('g:i A') }}
                                            @if ($item->is_submitted)
                                                | <span class="ms-1 text-success">Sudah Dikerjakan</span>
                                            @elseif ($data['date2']->format('H:i') >= $time_end->format('H:i'))
                                                | <span class="ms-1 text-danger">Tidak Dikerjakan</span>
                                            @else
                                                | <span class="ms-1 text-warning">Belum Dikerjakan</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="{{ $item->exam->code }}" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne1" data-bs-parent="#accordionExample2">
                                        <div class="accordion-body">
                                            <p>{{ $item->exam->type == 'pas' ? 'Penilaian Akhir Semester (PAS)' : 'Penilaian Tengah Semester (PTS)' }} | {{ $item->exam->subject->first()->name }} | {{ $item->exam->class->first()->name }}</p>
                                            <table>
                                                <tr>
                                                    <td class="pe-5">Guru Pengajar</td>
                                                    <td class="pe-1"> : </td>
                                                    <td>{{ $item->exam->teacher_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Ujian</td>
                                                    <td> : </td>
                                                    <td>{{ $exam_date->format('l, j F Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sesi Ujian</td>
                                                    <td> : </td>
                                                    <td>{{ $item->exam->session->first()->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Waktu Ujian</td>
                                                    <td> : </td>
                                                    <td>{{ $time_start->format('g:i').' - '.$time_end->format('g:i A') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Soal</td>
                                                    <td> : </td>
                                                    <td>{{ $item->exam->total_question }} Soal</td>
                                                </tr>
                                            </table>
                                            <hr/>
                                            @if (!$item->is_submitted)
                                                @if ($data['date2']->format('H:i') >= $time_start->format('H:i') && $data['date2']->format('H:i') <= $time_end->format('H:i'))
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" data="{{ $item->exam->code }}" class="btn btn-primary btn-start-exams">Kerjakan Ujian</button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row m-3">
                                <div class="col-md-12 text-center">
                                    <span class="text-secondary text-bold">
                                        Tidak Ada Ujian Yang Dapat Dikerjakan.
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalValidate" tabindex="-1" aria-labelledby="modalValidateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>

    <script type="text/javascript">
        const modal = new bootstrap.Modal($('#modalValidate'));

        $('.btn-start-exams').on('click', function(){
            const data = $(this).attr('data');

            $.ajax({
                method: "GET",
                url: "{{ route('api.exam.validate_token', ':id') }}".replace(':id', data),
                success: function(res){
                    $('#modalValidate').find('.modal-dialog').html(res);
                    modal.show();
                    validate();
                }
            });
        })

        function validate(){
            $('#formValidate').on('submit', function(e){
                e.preventDefault();
                const _form = this;
                const formData = new FormData(_form);

                const url = this.getAttribute('action');
                $.ajax({
                    method: "POST",
                    url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res){
                        modal.hide();
                        Toast.fire({
                            icon: res.status,
                            title: res.message
                        });
                        if('success' == res.status) window.location.replace(location_page(res.code));
                    }
                })
            });
        }

        function location_page(code){
            return "{{ route('api.exam.start', ':id') }}".replace(':id', code);
        }
    </script>
@endpush
