<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h5><b>{!! $question->question !!}</b></h5>
            </div>
            <div class="col-md-1 d-flex pe-0">A.</div>
            <div class="col-md-11 px-0">{!! $question->option_a !!}</div>
            <div class="col-md-1 d-flex pe-0">B.</div>
            <div class="col-md-11 px-0">{!! $question->option_b !!}</div>
            <div class="col-md-1 d-flex pe-0">C.</div>
            <div class="col-md-11 px-0">{!! $question->option_c !!}</div>
            <div class="col-md-1 d-flex pe-0">D.</div>
            <div class="col-md-11 px-0">{!! $question->option_d !!}</div>
            <div class="col-md-1 d-flex pe-0">E.</div>
            <div class="col-md-11 px-0">{!! $question->option_e !!}</div>
            <div class="col-md-12 mt-3">
                <h6><b>Kunci Jawaban :</b></h6>
                {!! $question->answer->value !!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
