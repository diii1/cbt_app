<div class="modal-content">
    <form id="formAction" action="{{ $data['action'] }}" method="post">
        @csrf
        @method('put')

        <div class="modal-header">
            <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="adminPassword" class="form-label">Kata Sandi</label>
                    <div class="input-group input-group-join mb-3">
                        <input type="password" placeholder="Kata Sandi" name="password" class="form-control" id="adminPassword">
                        <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="adminConfirmPassword" class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="input-group input-group-join mb-3">
                        <input type="password" placeholder="Konfirmasi Kata Sandi" name="password_confirmation" class="form-control" id="adminConfirmPassword">
                        <span class="input-group-text rounded-end password2 cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>
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
