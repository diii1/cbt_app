<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="sessionName" class="form-label">Nama Sesi Ujian</label>
                    <input type="text" placeholder="Nama Sesi Ujian" value="{{ $session->name }}" name="name" class="form-control" id="sessionName" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="timeStart" class="form-label">Waktu Mulai</label>
                    <input type="text" placeholder="Waktu Mulai" value="{{ $session->time_start }}" name="time_start" class="form-control" id="timeStart" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="timeEnd" class="form-label">Waktu Selesai</label>
                    <input type="text" placeholder="Waktu Selesai" value="{{ $session->time_end }}" name="time_end" class="form-control" id="timeEnd" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
