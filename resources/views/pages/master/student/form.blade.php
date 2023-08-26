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
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Nama</label>
                        <input type="text" placeholder="Tuliskan nama disini ..." value="{{ $student->user->name ?? '' }}" name="name" class="form-control" id="studentName">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" name="gender" id="gender">
                            <option value="" selected disabled>Pilih Jenis Kelamin</option>
                            <option value="L" {{ $student->gender == 'L' ? 'selected' : ''}}>Laki - Laki</option>
                            <option value="P" {{ $student->gender == 'P' ? 'selected' : ''}}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="studentEmail" class="form-label">Email Pengguna</label>
                        <input type="email" placeholder="Tuliskan email disini ..." value="{{ $student->user->email ?? '' }}" name="email" class="form-control" id="studentEmail">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="studentNIS" class="form-label">NIS</label>
                        <input type="text" placeholder="Tuliskan NIS disini ..." value="{{ $student->nis ?? '' }}" name="nis" class="form-control" id="studentNIS">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="studentNISN" class="form-label">NISN</label>
                        <input type="text" placeholder="Tuliskan NISN disini ..." value="{{ $student->nisn ?? '' }}" name="nisn" class="form-control" id="studentNISN">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="studentClass" class="form-label">Kelas</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="class_id">
                        <option value="" selected disabled>Pilih Kelas</option>
                        @foreach ($data['classes'] as $class)
                            <option value="{{ $class->id }}" {{isset($student->class->name) && $student->class->name == $class->name ? 'selected' : ''}}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="datepicker-icon" class="form-label">Tanggal Lahir Siswa</label>
                    <div class="input-group input-append date"data-date-format="dd-mm-yyyy">
                        <input class="form-control" type="text" value="{{ $student->birth_date }}" name="birth_date" readonly="" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="far fa-calendar-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="studentAddress" class="form-label">Alamat</label>
                        <textarea class="form-control" id="studentAddress" name="address" rows="3" placeholder="Tuliskan alamat disini ...">{{ $student->address ?? '' }}</textarea>
                    </div>
                </div>
                @if ($data['type'] == 'create')
                    <div class="col-md-6">
                        <label for="studentPassword" class="form-label">Kata Sandi</label>
                        <div class="input-group input-group-join mb-3">
                            <input type="password" placeholder="Kata Sandi" name="password" class="form-control" id="studentPassword">
                            <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="studentConfirmPassword" class="form-label">Konfirmasi Kata Sandi</label>
                        <div class="input-group input-group-join mb-3">
                            <input type="password" placeholder="Konfirmasi Kata Sandi" name="password_confirmation" class="form-control" id="studentConfirmPassword">
                            <span class="input-group-text rounded-end password2 cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </div>
    </form>
</div>
