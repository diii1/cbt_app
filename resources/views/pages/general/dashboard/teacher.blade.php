@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/chart.js/dist/Chart.min.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="title">{{ $data['title'] }}</div>
        <div class="content-wrapper">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-5">
                            <div class="card text-warning border-warning mb-3">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-md-10">
                                            <h5 class="card-title">Ujian Sedang Berlangsung</h5>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-warning w-100 btn-exam-table" data-teacher_id="{{ $data['teacher_id'] }}">
                                                <h3 class="m-0"><i class="ti ti-list"></i></h3>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-5">
                            <div class="card text-secondary border-secondary mb-3">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="col-md-10">
                                            <h5 class="card-title">Histori Hasil Ujian</h5>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center">
                                            <button class="btn btn-sm btn-outline-secondary w-100">
                                                <h3 class="m-0"><i class="ti ti-list"></i></h3>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalExamTable" tabindex="-1" aria-labelledby="modalExamTableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/chart.js/dist/Chart.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('assets/js/page/index.js') }}"></script>

    <script type="text/javascript">
        const modal = new bootstrap.Modal($('#modalExamTable'));

        $('.btn-exam-table').on('click', function () {
            const data = $(this).data();

            $.ajax({
                url: "{{ route('api.dashboard.exam.table', ':teacher_id') }}".replace(':teacher_id', data.teacher_id),
                type: 'GET',
                success: function (res) {
                    $('#modalExamTable').find('.modal-dialog').html(res);
                    modal.show();
                }
            })
        });
    </script>
@endpush
