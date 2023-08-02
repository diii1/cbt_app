<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="studentNIS" class="form-label">NIS</label>
                    <input type="text" value="{{ $student->nis ?? '' }}" class="form-control" id="studentNIS" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="studentNISN" class="form-label">NISN</label>
                    <input type="text" value="{{ $student->nisn ?? '' }}" class="form-control" id="studentNISN" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="studentName" class="form-label">Nama</label>
                    <input type="text" value="{{ $student->user->name ?? '' }}" class="form-control" id="studentName" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="studentEmail" class="form-label">Email Pengguna</label>
                    <input type="email" value="{{ $student->user->email ?? '' }}" class="form-control" id="studentEmail" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="studentBirthDate" class="form-label">Tanggal Lahir</label>
                    <input type="text" value="{{ $student->birth_date ?? '' }}" class="form-control" id="studentBirthDate" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="studentPassword" class="form-label">Password</label>
                    <input type="text" value="{{ $student->password ?? '' }}" class="form-control" id="studentPassword" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="studentAddress" class="form-label">Alamat</label>
                    <textarea class="form-control" id="studentAddress" name="address" rows="3" readonly>{{ $student->address ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
