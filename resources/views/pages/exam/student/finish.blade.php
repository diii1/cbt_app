@extends('layouts.student.app')

@section('content')
    <div class="container-fluid px-5 mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>Terimakasih Telah Melaksanakan Ujian.</h5>
                    </div>
                    <div class="card-body text-center">
                        <span>Semoga Sukses dan Sehat Selalu !!!</span>
                    </div>
                    <div class="footer d-flex justify-content-center my-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-info"><i class="ti-home"></i> Halaman Utama</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
