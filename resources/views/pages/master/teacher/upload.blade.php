<div class="modal-content">
    <form id="formAction" action="{{ $data['action'] }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="teacherFile" class="form-label">File Import</label>
                        <input type="file" name="file" class="form-control" id="teacherFile">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Import Data</button>
        </div>
    </form>
</div>
