<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="titleExam" class="form-label">Judul Ujian</label>
                    <input type="text" value="{{ $exam->title }}" class="form-control" id="titleExam" disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="teacherExam" class="form-label">Tenaga Pengajar</label>
                    <input type="text" value="{{ $exam->teacher_name }}" class="form-control" id="teacherExam" disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="examType" class="form-label">Tipe Ujian</label>
                    <input type="text" value="{{ $exam->type == 'pas' ? 'Ujiang Akhir Semester (PAS)' : 'Ujian Tengah Semester (PTS)' }}" class="form-control" id="examType" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="codeExam" class="form-label">Kode Ujian</label>
                    <input type="text" value="{{ $exam->code }}" class="form-control" id="codeExam" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="tokenExam" class="form-label">Token Ujian</label>
                    <input type="text" value="{{ $exam->token }}" class="form-control" id="tokenExam" disabled>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="expiredToken" class="form-label">Masa Berlaku Token</label>
                <div class="input-group input-append date"data-date-format="dd-mm-yyyy">
                    <input class="form-control" id="expiredToken" type="text" value="{{  $exam->expired_token->format('j F Y,  H:i') }}" readonly="" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" disabled>
                        <i class="far fa-calendar-alt"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="examDate" class="form-label">Tanggal Ujian</label>
                <div class="input-group input-append date"data-date-format="dd-mm-yyyy">
                    <input class="form-control" type="text" id="examDate" value="{{ $exam->date->format('j F Y') }}" readonly="" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" disabled>
                        <i class="far fa-calendar-alt"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="timeExam" class="form-label">Sesi Ujian</label>
                    <input type="text" value="{{ $exam->session }}" class="form-control" id="timeExam" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="subjectExam" class="form-label">Mata Pelajaran</label>
                    <input type="text" value="{{ $exam->subject_name }}" class="form-control" id="subjectExam" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="classExam" class="form-label">Kelas</label>
                    <input type="text" value="{{ $exam->class_name }}" class="form-control" id="classExam" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="examStatus" class="form-label">Status Ujian</label>
                    <input type="text" value="{{ $exam->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}" class="form-control" id="examStatus" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="amount_questionExam" class="form-label">Jumlah Soal</label>
                    <input type="text" value="{{ $exam->total_question }}" class="form-control" id="amount_questionExam" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="examStep" class="form-label">Jumlah Soal Per Step</label>
                    <input type="text" value="{{ $exam->total_question_step }}" class="form-control" id="examStep" disabled>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="examMinScore" class="form-label">Nilai KKM</label>
                    <input type="text" value="{{ $exam->min_score }}" class="form-control" id="examMinScore" disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
