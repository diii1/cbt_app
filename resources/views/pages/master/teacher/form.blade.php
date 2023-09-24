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
                    <div class="d-flex justify-content-center">
                        <img src="{{ ($teacher->user && $teacher->user->profile) ? asset('storage/'.$teacher->user->profile) : asset('assets/images/avatar1.png') }}" class="rounded-circle" width="150" height="150" alt="Foto Profil">
                    </div>
                    <h6 class="text-center mt-3">{{ $data['type'] == 'edit' ? ($teacher->user->profile ? $teacher->user->profile : 'Belum Ada Profil') : '' }}</h6>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="teacherProfile" class="form-label">Foto Profil</label>
                        <input type="file" name="profile" id="profile" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="teacherName" class="form-label">Nama</label>
                        <input type="text" placeholder="Tuliskan nama disini ..." value="{{ $teacher->user->name ?? '' }}" name="name" class="form-control" id="teacherName">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="teacherEmail" class="form-label">Email Pengguna</label>
                        <input type="email" placeholder="Tuliskan email disini ..." value="{{ $teacher->user->email ?? '' }}" name="email" class="form-control" id="teacherEmail">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="teacherNIP" class="form-label">NIP</label>
                        <input type="text" placeholder="Tuliskan NIP disini ..." value="{{ $teacher->nip ?? '' }}" name="nip" class="form-control" id="teacherNIP">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="teacherPhone" class="form-label">No. Handphone</label>
                        <input type="number" placeholder="Tuliskan no handphone disini ..." value="{{ $teacher->phone ?? '' }}" name="phone" class="form-control" id="teacherPhone">
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="teacherSubject" class="form-label">Mata Pelajaran</label>
                    <select class="js-example-basic-single form-select form-select-sm" name="subject_id" required>
                        <option value="" selected disabled>Pilih Mata Pelajaran</option>
                        @foreach ($data['subjects'] as $subject)
                            <option value="{{ $subject->id }}" {{isset($teacher->subject->id) && $teacher->subject->id == $subject->id ? 'selected' : ''}}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="teacherAddress" class="form-label">Alamat</label>
                        <textarea class="form-control" id="teacherAddress" name="address" rows="3" placeholder="Tuliskan alamat disini ...">{{ $teacher->address ?? '' }}</textarea>
                    </div>
                </div>
                @if ($data['type'] == 'create')
                    <div class="col-md-6">
                        <label for="teacherPassword" class="form-label">Kata Sandi</label>
                        <div class="input-group input-group-join mb-3">
                            <input type="password" placeholder="Kata Sandi" name="password" class="form-control" id="teacherPassword">
                            <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="teacherConfirmPassword" class="form-label">Konfirmasi Kata Sandi</label>
                        <div class="input-group input-group-join mb-3">
                            <input type="password" placeholder="Konfirmasi Kata Sandi" name="password_confirmation" class="form-control" id="teacherConfirmPassword">
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
