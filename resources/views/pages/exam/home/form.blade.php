<div class="modal-content">
    <form id="formAction" action="{{ $data['action'] }}" method="post">
        @csrf
        @if ($data['type'] == 'edit')
            @method('put')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="titleExam" class="form-label">Judul Ujian</label>
                        <input type="text" placeholder="Judul Ujian" value="{{ $exam->title }}" name="title" class="form-control" id="titleExam">
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="examSession" class="form-label">Sesi Ujian</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="session_id" id="examSession">
                        <option value="" selected disabled>Pilih Sesi Ujian</option>
                        @foreach ($data['sessions'] as $session)
                            <option value="{{ $session->id }}" {{ isset($exam->session_id) && $exam->session_id == $session->id ? 'selected' : '' }}>
                                {{ $session->name }} | {{ \Carbon\Carbon::parse($session->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->time_end)->format('H:i') }} WIB
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="examClass" class="form-label">Kelas</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="class_id" id="examClass">
                        <option value="" selected disabled>Pilih Kelas</option>
                        @foreach ($data['classes'] as $class)
                            <option value="{{ $class->id }}" {{ isset($exam->class_id) && $exam->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="subjects" class="form-label">Mata Pelajaran</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="subject_id" id="subjects">
                        <option value="" selected disabled>Pilih Mata Pelajaran</option>
                        @foreach ($data['subjects'] as $subject)
                            <option value="{{ $subject->id }}" {{ isset($exam->subject_id) && $exam->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="teachers" class="form-label">Tenaga Pengajar</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="teacher_id" id="teachers">
                        <option value="" selected disabled>Pilih Tenaga Pengajar</option>
                        @if ($exam->id)
                            @foreach ($data['teachers'] as $teacher)
                                <option value="{{ $teacher->id }}" {{ isset($exam->teacher_id) && $exam->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="examType" class="form-label">Tipe Ujian</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="type" id="examType">
                        <option value="" selected disabled>Pilih Tipe Ujian</option>
                        <option value="pts" {{ isset($exam->type) && $exam->type == 'pts' ? 'selected' : '' }}>{{ __('Ujian Tengah Semester (PTS)') }}</option>
                        <option value="pas" {{ isset($exam->type) && $exam->type == 'pas' ? 'selected' : '' }}>{{ __('Ujian Akhir Semester (PAS)') }}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="exam_date" class="form-label">Tanggal Ujian Dilaksanakan</label>
                    <div class="input-group input-append date" data-date-format="dd-mm-yyyy">
                        <input class="form-control" type="text" value="{{ $exam->date ? $exam->date->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y') }}" id="exam_date" name="date" readonly="" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="far fa-calendar-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="examAmount" class="form-label">Jumlah Soal</label>
                        <input type="number" min="0" value="{{ $exam->total_question }}" placeholder="Jumlah Soal" name="total_question" class="form-control" id="examAmount">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="examStep" class="form-label">Jumlah Soal Per Step</label>
                        <input type="number" min="0" value="{{ $exam->total_question_step }}" placeholder="Jumlah Soal Per Step" name="total_question_step" class="form-control" id="examStep">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="examScore" class="form-label">Nilai KKM</label>
                        <input type="number" min="0" value="{{ $exam->min_score }}" placeholder="Nilai KKM" name="min_score" class="form-control" id="examScore">
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
