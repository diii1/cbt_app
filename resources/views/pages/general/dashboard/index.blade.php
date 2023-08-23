@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="title">{{ $data['title'] }}</div>
        <div class="content-wrapper">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-4">
                            <div class="card text-info border-info mb-3">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="card-header py-1 border-bottom-1 border-info">
                                            <h5 class="card-title text-center">Jumlah Guru</h5>
                                        </div>
                                        <hr>
                                        <h5 class="card-title text-center">{{ $data['teacher'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-info border-info mb-3">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="card-header py-1 border-bottom-1 border-info">
                                            <h5 class="card-title text-center">Jumlah Siswa</h5>
                                        </div>
                                        <hr>
                                        <h5 class="card-title text-center">{{ $data['student'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-info border-info mb-3">
                                <div class="card-body">
                                    <div class="row d-flex align-items-center">
                                        <div class="card-header py-1 border-bottom-1 border-info">
                                            <h5 class="card-title text-center">Jumlah Ujian</h5>
                                        </div>
                                        <hr>
                                        <h5 class="card-title text-center">{{ $data['exam'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/page/index.js') }}"></script>
@endpush
