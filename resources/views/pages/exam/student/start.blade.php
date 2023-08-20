@extends('layouts.student.app')

@section('content')
    <div class="container-fluid px-5 mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12 text-center py-3">
                                <h5>
                                    <b>
                                        {{ $data['exam']->type == 'pts' ? 'Penilaian Tengah Semester (PTS)' : 'Penilaian Akhir Semester (PAS)' }} -
                                        {{ $data['exam']->subject->name }} -
                                        {{ $data['exam']->class->name }}
                                    </b>
                                </h5>
                            </div>
                            <hr>
                            <div class="col-md-6 px-5 py-3">
                                <p>Pengajar : {{ $data['exam']->teacher_name }}</p>
                                <p>Jumlah Soal : {{ $data['exam']->total_question }}</p>
                                <p>Waktu Ujian : {{ $data['time_start']->format('H:i') }} - {{ $data['time_end']->format('H:i') }} WIB</p>
                                <p>Durasi : {{ $data['duration'] }} Menit</p>
                            </div>
                            <div class="col-md-6 px-5 py-3">
                                <p>Mata Pelajaran : {{ $data['exam']->subject->name }}</p>
                                <p>NIS : {{ $data['student']->nis }}</p>
                                <p>NISN : {{ $data['student']->nisn }}</p>
                                <p>Nama Lengkap : {{ $data['student']->user->name }}</p>
                            </div>
                            <hr>
                            <div class="col-md-12 d-flex justify-content-end">
                                <button class="btn btn-info w-100" id="exam-start"><i class="ti-shift-right"></i>&nbsp; Mulai Ujian</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $('#exam-start').on('click', function() {
            Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Ingin memulai ujian saat ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, mulai!',
                    cancelButtonText: 'Tidak, batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'POST',
                            url: "{{ route('api.exam.start.store') }}",
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {exam_id: {{ $data['exam']->id }} },
                            success: function(res) {
                                Toast.fire({
                                    icon: res.status,
                                    title: res.message
                                })

                                if (res.status == 'success') {
                                    const url = "{{ route('api.exam.get_question', [':code', 1]) }}".replace(':code', res.code);

                                    setTimeout(function() {
                                        window.location.href = url;
                                    }, 2000);
                                }
                            },
                            error: function(res) {
                                Toast.fire({
                                    icon: res.status,
                                    title: res.message
                                })
                            }
                        })
                    }
                })
        });
    </script>
@endpush
