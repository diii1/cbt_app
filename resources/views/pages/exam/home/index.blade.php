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
                                @can('create_exam')
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
        const modal = new bootstrap.Modal($('#modalAction'));

        $('.btn-add').on('click', function() {
            $.ajax({
                url: '{{ route('exams.create') }}',
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

                    $('#subjects').on('change', function(e){
                        e.preventDefault();
                        const subject_id = $('#subjects option:selected').val();
                        $.ajax({
                            method: "GET",
                            url: "{{ route('api.teachers.subjectID', ':id') }}".replace(':id', subject_id),
                            success: function(res){
                                const teachers = JSON.parse(res);
                                if(teachers.length > 0){
                                    $('#teachers').html(
                                        teachers.map((item) => {
                                            return `<option value="${item.id}"> ${item.name} </option>`;
                                        })
                                    );
                                }else{
                                    return $('#teachers').html(`<option value="" selected disabled>Pilih Tenaga Pengajar</option>`);
                                }
                            }
                        })
                    });
                }
            })
        });

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
                        window.LaravelDataTables["exam-table"].ajax.reload();
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

        $('#exam-table').on('click', '.action', function(){
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
                            url: '{{ route('exams.destroy', ':id') }}'.replace(':id', id),
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                window.LaravelDataTables["exam-table"].ajax.reload();
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
                    url: `{{ route('exams.edit', ':id') }}`.replace(':id', id),
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

                        $('#subjects').on('change', function(e){
                            e.preventDefault();
                            const subject_id = $('#subjects option:selected').val();
                            $.ajax({
                                method: "GET",
                                url: "{{ route('api.teachers.subjectID', ':id') }}".replace(':id', subject_id),
                                success: function(res){
                                    const teachers = JSON.parse(res);
                                    if(teachers.length > 0){
                                        $('#teachers').html(
                                            teachers.map((item) => {
                                                return `<option value="${item.id}"> ${item.name} </option>`;
                                            })
                                        );
                                    }else{
                                        return $('#teachers').html(`<option value="" selected disabled>Pilih Tenaga Pengajar</option>`);
                                    }
                                }
                            })
                        });
                    }
                })
            }

            if(type == 'detail'){
                $.ajax({
                    url: `{{ route('exams.show', ':id') }}`.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                    }
                })
            }

            if(type == 'update_status'){
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Ingin mengubah status ujian ini.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Saya yakin!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "PUT",
                            url: `{{ route('api.exam.is_active', ':id') }}`.replace(':id', id),
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res){
                                window.LaravelDataTables["exam-table"].ajax.reload();
                                Toast.fire({
                                    icon: res.status,
                                    title: res.message
                                });
                            }
                        })
                    }
                })
            }
        });
    </script>
@endpush
