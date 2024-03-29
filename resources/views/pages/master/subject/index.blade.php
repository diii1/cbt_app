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
                                @can('create_subject')
                                    <button type="button" class="btn mb-3 btn-success btn-sm btn-add"><i class="ti ti-plus"></i> {{ __($data['button_add']) }}</button>
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

    <script type="text/javascript">
        const modal = new bootstrap.Modal($('#modalAction'));

        $('.btn-add').on('click', function () {
            $.ajax({
                url: '{{ route('subjects.create') }}',
                type: 'GET',
                success: function (res) {
                    $('#modalAction').find('.modal-dialog').html(res);
                    modal.show();
                    store();
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
                        window.LaravelDataTables["subject-table"].ajax.reload();
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

        $('#subject-table').on('click', '.action', function(){
            let data = $(this).data();
            let id = data.id;
            let type = data.type;

            if(type == 'edit') {
                $.ajax({
                    url: '{{ route('subjects.edit', ':id') }}'.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                        store();
                    }
                })
            }

            if(type == 'delete') {
                Swal.fire({
                    title: `{{ __('Apakah anda yakin?') }}`,
                    text: `{{ __('Data yang dihapus tidak dapat dikembalikan!') }}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: `{{ __('Ya, hapus!') }}`,
                    cancelButtonText: `{{ __('Tidak, Batalkan!') }}`,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('subjects.destroy', ':id') }}`.replace(':id', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                window.LaravelDataTables["subject-table"].ajax.reload();
                                Toast.fire({
                                    icon: res.status,
                                    title: res.message
                                })
                            }
                        })
                    }
                })
            }

            if(type == 'detail'){
                $.ajax({
                    url: `{{ route('subjects.show', ':id') }}`.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                    }
                })
            }
        })
    </script>
@endpush
