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
                                @can('create_question')
                                    <a href="{{ route('exam.create_question', $data['exam']->id) }}" class="btn btn-sm btn-success mb-3 me-3"><i class="ti ti-plus"></i> {{ __($data['button_add']) }}</a>
                                @endcan
                                @can('create_question')
                                    <button type="button" class="btn btn-sm btn-primary mb-3" id="transfer_question"><i class="ti ti-shift-right"></i>&nbsp; Transfer Soal</button>
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
    {{ $dataTable->scripts() }}

    <script type="text/javascript">
        $('#transfer_question').click(function(){
            $.ajax({
                method: 'GET',
                url: '{{ route('api.question.table', $data['exam']->id) }}',
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    $('#modalAction .modal-dialog').html(res);
                    $('#modalAction').modal('show');
                    $('#exam-table').on('click', '.transfer', function(){
                        let data = $(this).data();

                        $.ajax({
                            method: 'POST',
                            url: '{{ route('api.question.transfer') }}',
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                exam_id: `{{ $data['exam']->id }}`,
                                to_exam_id: data.exam_id
                            },
                            success: function(res) {
                                $('#modalAction').modal('hide');
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
                    });
                },
                error: function(res) {
                    Toast.fire({
                        icon: res.status,
                        title: res.message
                    })
                }
            });
        });

        $('#question-table').on('click', '.action', function(){
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
                            url: '{{ route('questions.destroy', ':id') }}'.replace(':id', id),
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                window.LaravelDataTables["question-table"].ajax.reload();
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
                window.location.href = '{{ route('questions.edit', ':id') }}'.replace(':id', id);
            }

            if(type == 'detail'){
                // window.location.href = '{{ route('questions.show', ':id') }}'.replace(':id', id);
                $.ajax({
                    method: 'GET',
                    url: '{{ route('questions.show', ':id') }}'.replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('#modalAction .modal-dialog').html(res);
                        $('#modalAction').modal('show');
                    },
                    error: function(res) {
                        Toast.fire({
                            icon: res.status,
                            title: res.message
                        })
                    }
                })
            }
        });
    </script>
@endpush
