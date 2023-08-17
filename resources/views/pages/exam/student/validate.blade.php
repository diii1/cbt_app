<div class="modal-content">
    <form id="formValidate" action="{{ route('api.exam.validate_exam') }}" method="post">
        @csrf
        <input type="hidden" name="code" value="{{ $data['exam']->code }}" />
        <div class="modal-header">
            <h5 class="modal-title" id="modalValidateLabel">{{ $data['title'] }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <h6>{{ $data['exam']->title }}</h6>
                    <p class="text-sm">Kode Ujian : {{ $data['exam']->code }}</p>
                </div>
                <div class="col-md-12 d-flex justify-content-center">
                    <div class="w-75">
                        <input type="text" placeholder="Token Ujian" name="token" class="form-control" id="examToken" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Validasi</button>
        </div>
    </form>
</div>
