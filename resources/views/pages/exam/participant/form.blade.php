<div class="modal-content">
    <form id="formAction" action="{{ $data['action'] }}" method="post">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="titleExam" class="form-label">Judul Ujian</label>
                        <input type="hidden" name="exam_id" value="{{ $data['exam']->id }}">
                        <input type="text" placeholder="Judul Ujian" value="{{ $data['exam']->title.' - '.$data['exam']->class_name.' | '.$data['exam']->session->name }}" name="title" class="form-control" id="titleExam" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                </tr>
                            </thead>
                            @foreach ($data['students'] as $key => $student)
                                <tr>
                                    <td><input type="checkbox" name="student[]" value="{{ $student->user_id }}" id="student{{$key}}"></td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->nisn }}</td>
                                    <td>{{ $student->user->name }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </div>
    </form>
</div>
