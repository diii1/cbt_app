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
                        <label for="subjectName" class="form-label">Nama Mata Pelajaran</label>
                        <input type="text" placeholder="Nama Mata Pelajaran" value="{{ $subject->name }}" name="name" class="form-control" id="subjectName">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="subjectDescription" class="form-label">Deskripsi Mata Pelajaran<small> *(opsional)</small></label>
                        <textarea class="form-control" name="description" id="subjectDescription" rows="4">{{ $subject->description }}</textarea>
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
