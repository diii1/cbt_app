@extends('layouts.app')

@push('css')
    <link href="{{ asset('vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
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
                                @can('import_student')
                                    <a target="_blank" href="{{ route('api.student.template') }}" class="btn btn-sm mb-3 me-2 btn-secondary"><i class="ti ti-download"></i> &nbsp;{{ __(' Download Template') }}</a>
                                    <button type="button" id="import" class="btn btn-sm mb-3 me-2 btn-primary"><i class="ti ti-upload"></i> &nbsp;{{ __(' Import Data') }}</button>
                                @endcan
                                @can('export_student')
                                    <a target="_blank" href="{{ route('api.student.export') }}" class="btn btn-sm mb-3 me-2 btn-primary"><i class="ti ti-export"></i> &nbsp;{{ __(' Export Data') }}</a>
                                @endcan
                                @can('create_student')
                                    <button type="button" class="btn mb-3 btn-success btn-sm btn-add"><i class="ti ti-plus"></i> &nbsp;{{ __($data['button_add']) }}</button>
                                @endcan
                            </div>
                            {{ $dataTable->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="modalActionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        function seePassword(){
            const seePassword = document.querySelectorAll('.password')
            seePassword.forEach(function (pass) {
                pass.addEventListener('click', function () {
                    if (this.children[0].classList.contains('fa-eye')) {
                        this.children[0].classList.remove('fa-eye')
                        this.children[0].classList.add('fa-eye-slash')
                        this.parentElement.children[0].setAttribute('type', 'text')
                        return false;
                    }
                    this.children[0].classList.add('fa-eye')
                    this.children[0].classList.remove('fa-eye-slash')
                    this.parentElement.children[0].setAttribute('type', 'password')
                })
            });

            const seePassword2 = document.querySelectorAll('.password2')
            seePassword2.forEach(function (pass) {
                pass.addEventListener('click', function () {
                    if (this.children[0].classList.contains('fa-eye')) {
                        this.children[0].classList.remove('fa-eye')
                        this.children[0].classList.add('fa-eye-slash')
                        this.parentElement.children[0].setAttribute('type', 'text')
                        return false;
                    }
                    this.children[0].classList.add('fa-eye')
                    this.children[0].classList.remove('fa-eye-slash')
                    this.parentElement.children[0].setAttribute('type', 'password')
                })
            });
        }

        const modal = new bootstrap.Modal($('#modalAction'));

        $('.btn-add').on('click', function() {
            $.ajax({
                url: '{{ route('students.create') }}',
                type: 'GET',
                success: function(res) {
                    $('#modalAction').find('.modal-dialog').html(res);
                    modal.show();
                    store();
                    seePassword();
                    $('.date').datepicker({
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd-mm-yyyy'
                    });
                }
            })
        });

        $('#import').on('click', function() {
            $.ajax({
                url: '{{ route('api.student.create') }}',
                type: 'GET',
                success: function(res) {
                    $('#modalAction').find('.modal-dialog').html(res);
                    modal.show();
                    store();
                }
            })
        })

        function store(){
            $('#formAction').on('submit', function(e){
                e.preventDefault();

                const _form = this;
                const formData = new FormData(_form);
                const url = this.getAttribute('action');

                $.ajax({
                    method: 'POST',
                    url,
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        window.LaravelDataTables["student-table"].ajax.reload();
                        modal.hide();
                        Toast.fire({
                            icon: res.status,
                            title: res.message
                        })
                    },
                    error: function(res) {
                        let errors = res.responseJSON?.errors;
                        $(_form).find('.text-danger.text-small').remove();
                        if(errors) {
                            for (const [key, value] of Object.entries(errors)) {
                                $(`[name='${key}']`).parent().append(`<span class="text-danger text-small">*${value}</span>`);
                            }
                        }
                    }
                })
            })
        }

        $('#student-table').on('click', '.action', function(){
            let data = $(this).data();
            let id = data.id;
            let type = data.type;

            if(type == 'delete'){
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Tidak, batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'DELETE',
                            url: '{{ route('students.destroy', ':id') }}'.replace(':id', id),
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                window.LaravelDataTables["student-table"].ajax.reload();
                                Toast.fire({
                                    icon: res.status,
                                    title: res.message
                                })
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
            }

            if(type == 'edit'){
                $.ajax({
                    url: `{{ route('students.edit', ':id') }}`.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                        store();
                        $('.date').datepicker({
                            autoclose: true,
                            todayHighlight: true,
                            format: 'dd-mm-yyyy'
                        });
                    }
                })
            }

            if(type == 'change_password'){
                $.ajax({
                    url: `{{ route('api.user.change_password.edit', ':id') }}`.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                        store();
                        seePassword();
                    }
                })
            }

            if(type == 'detail'){
                $.ajax({
                    url: `{{ route('students.show', ':id') }}`.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                    }
                })
            }
        });
    </script>
@endpush
