<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="teacherName" class="form-label">Nama</label>
                    <input type="text" placeholder="Tuliskan nama disini ..." value="{{ $teacher->user->name ?? '' }}" name="name" class="form-control" id="teacherName" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="teacherEmail" class="form-label">Email Pengguna</label>
                    <input type="email" placeholder="Tuliskan email disini ..." value="{{ $teacher->user->email ?? '' }}" name="email" class="form-control" id="teacherEmail" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="teacherNIP" class="form-label">NIP</label>
                    <input type="text" placeholder="Tuliskan NIP disini ..." value="{{ $teacher->nip ?? '' }}" name="nip" class="form-control" id="teacherNIP" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="teacherPhone" class="form-label">No. Handphone</label>
                    <input type="text" placeholder="Tuliskan no handphone disini ..." value="{{ $teacher->phone ?? '' }}" name="phone" class="form-control" id="teacherPhone" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="teacherSubject" class="form-label">Mata Pelajaran</label>
                    <input type="text" placeholder="Tuliskan Mata Pelajaran disini ..." value="{{ $teacher->subject->name ?? '' }}" name="subject" class="form-control" id="teacherSubject" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="teacherAddress" class="form-label">Alamat</label>
                    <textarea class="form-control" id="teacherAddress" name="address" rows="3" placeholder="Tuliskan alamat disini ..." readonly>{{ $teacher->address ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
