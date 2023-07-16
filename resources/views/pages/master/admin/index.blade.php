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
                                @if (auth()->user()->can('create_admin'))
                                    <button type="button" class="btn mb-3 btn-primary btn-sm btn-add">{{ __($data['button_add']) }}</button>
                                @endif
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
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        const modal = new bootstrap.Modal($('#modalAction'))

        $('.btn-add').on('click', function() {
            $.ajax({
                url: '{{ route('admins.create') }}',
                type: 'GET',
                success: function(res) {
                    $('#modalAction').find('.modal-dialog').html(res);
                    modal.show();
                    store();

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
                        window.LaravelDataTables["admin-table"].ajax.reload();
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

        $('#admin-table').on('click', '.action', function(){
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
                            url: '{{ route('admins.destroy', ':id') }}'.replace(':id', id),
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                window.LaravelDataTables["admin-table"].ajax.reload();
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
                    url: `{{ route('admins.edit', ':id') }}`.replace(':id', id),
                    type: 'GET',
                    success: function(res) {
                        $('#modalAction').find('.modal-dialog').html(res);
                        modal.show();
                        store();
                    }
                })
            }
        })
    </script>
@endpush
