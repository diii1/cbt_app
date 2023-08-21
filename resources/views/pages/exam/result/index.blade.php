@extends('layouts.app')

@push('css')
    <link href="{{ asset('vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="main-content">
        <div class="title">
            {{ __($data['title']) }}
        </div>
        <div class="content-wrapper">
            <div class="row same-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                {{-- @can('create_session')
                                    <button type="button" class="btn mb-3 btn-success btn-sm btn-add"><i class="ti ti-plus"></i> {{ __($data['button_add']) }}</button>
                                @endcan --}}
                            </div>
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="modalActionLabel" aria-hidden="true">
        <div class="modal-dialog">

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
    {{ $dataTable->scripts() }}
@endpush
