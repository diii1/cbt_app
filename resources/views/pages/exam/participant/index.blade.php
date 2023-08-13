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
                            <div class="row select-exams">
                                <div class="col-md-6">
                                    <label for="exam" class="form-label">Ujian telah didaftarkan</label>
                                    <select class="js-example-basic-single form-select form-select-sm" name="exam" id="exam">
                                        <option value="" selected disabled>Pilih Ujian</option>
                                        @foreach ($data['exams'] as $exam )
                                            <option value="{{ $exam->id}}">
                                                {{ $exam->teacher_name }} :
                                                {{ $exam->title }} -
                                                {{ $exam->session_name }}
                                                ( {{ \Carbon\Carbon::parse($exam->session_time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->session_time_end)->format('H:i') }} ) - {{ $exam->class_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end">
                                        @can('card_participant')
                                            <button type="button" class="btn mb-3 me-3 btn-secondary btn-sm btn-print" style="display:none"><i class="fa fa-print"></i> &nbsp;{{ __('Kartu Peserta') }}</button>
                                            {{-- <button type="button" class="btn btn-secondary btn-sm btn-print" style="display:none"><i class="ti ti-print"></i> &nbsp;{{ __('Kartu Peserta') }}</button> --}}
                                        @endcan
                                        @can('create_participant')
                                            <button type="button" class="btn mb-3 btn-success btn-sm btn-add" style="display:none"><i class="ti ti-plus"></i> &nbsp;{{ $data['button_add'] }}</button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="dataTable">
                                        <table class="table" id="participants-table" style="display:none">
                                            <thead>
                                                <th class="row-index" title="No">No</th>
                                                <th title="NIS">NIS</th>
                                                <th title="NISN">NISN</th>
                                                <th title="Name">Nama Siswa</th>
                                                <th title="Class">Kelas</th>
                                                <th class="text-center" title="Aksi">Aksi</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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

    <script type="text/javascript">
        $('#exam').on('change', function(e){
            const exam_id = $(this).val();
            $('#participants-table').DataTable().destroy();
            let table = $('#participants-table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 1500,
                ajax: "{{ route('api.exam.participants_table', ':id') }}".replace(':id', exam_id),
                order: false,
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable:false},
                    {data: 'nis', name: 'nis'},
                    {data: 'nisn', name: 'nisn'},
                    {data: 'name', name: 'name'},
                    {data: 'class', name: 'class'},
                    {data: 'action', name: 'action', orderable: false, searchable:false},
                ]
            });
            $('.select-exams').addClass('mb-4');
            $('#participants-table').show();
            $('.btn-add')?.show();
            $('.btn-print')?.show();
        });

        const modal = new bootstrap.Modal($('#modalAction'));

        $('.btn-add')?.on('click', function(){
            const exam_id = $('#exam option:selected').val();
            $.ajax({
                method: "GET",
                url: "{{ route('participants.create') }}",
                data: {exam_id: exam_id},
                success: function(res){
                    $('#modalAction').find('.modal-dialog').html(res);
                    modal.show();
                    store();
                }
            })
        });

        $('.btn-print').click(function() {
            const exam_id = $('#exam option:selected').val();
            window.open("{{ route('api.exam.participants_cards', ':id') }}".replace(':id', exam_id), '_blank');
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
                        $('#participants-table').DataTable().ajax.reload();
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

        $('#participants-table').on('click', '.action', function(){
            let data = $(this).data();
            let id = data.id;
            let type = data.type;

            if(type == 'delete'){
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus data ini!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: "DELETE",
                            url: "{{ route('participants.destroy', ':id') }}".replace(':id', id),
                            headers: {
                                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res){
                                $('#participants-table').DataTable().ajax.reload();
                                Toast.fire({
                                    icon: res.status,
                                    title: res.message
                                });
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
        });
    </script>
@endpush
