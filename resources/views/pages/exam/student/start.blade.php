@extends('layouts.student.app')

@section('content')
    <div class="container-fluid px-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>{{ $data['exam']->title }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
