<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    <img src="{{ ($admin->user && $admin->user->profile) ? asset('storage/'.$admin->user->profile) : asset('assets/images/avatar1.png') }}" class="rounded-circle" width="150" height="150" alt="Foto Profil">
                </div>
                <h6 class="text-center mt-3">{{ $admin->user->profile ? $admin->user->profile : 'Belum Ada Profil' }}</h6>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="adminName" class="form-label">Nama</label>
                    <input type="text" placeholder="Tuliskan nama disini ..." value="{{ $admin->user->name ?? '' }}" name="name" class="form-control" id="adminName" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="adminEmail" class="form-label">Email Pengguna</label>
                    <input type="email" placeholder="Tuliskan email disini ..." value="{{ $admin->user->email ?? '' }}" name="email" class="form-control" id="adminEmail" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="adminNIP" class="form-label">NIP</label>
                    <input type="text" placeholder="Tuliskan NIP disini ..." value="{{ $admin->nip ?? '' }}" name="nip" class="form-control" id="adminNIP" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="adminPhone" class="form-label">No. Handphone</label>
                    <input type="text" placeholder="Tuliskan no handphone disini ..." value="{{ $admin->phone ?? '' }}" name="phone" class="form-control" id="adminPhone" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="adminAddress" class="form-label">Alamat</label>
                    <textarea class="form-control" id="adminAddress" name="address" rows="3" placeholder="Tuliskan alamat disini ..." readonly>{{ $admin->address ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
