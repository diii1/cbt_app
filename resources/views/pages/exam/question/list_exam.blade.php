<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="modalActionLabel">{{ $data['title'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <table class="table table-striped" id="exam-table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" style="max-width: 35%">Judul Ujian</th>
                    <th scope="col">Kode Ujian</th>
                    <th scope="col">Tanggal dan Waktu</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $loops = 1;
                @endphp
                @foreach ($data['exam'] as $exam)
                    <tr>
                        <th scope="col">{{ $loops++ }}</th>
                        <td>{{ $exam->title }}</td>
                        <td>{{ $exam->code }}</td>
                        <td>{{ $exam->date->format('d F Y') }}, {{ $exam->session_time_start->format('H:i') }} - {{ $exam->session_time_end->format('H:i') }} WIB</td>
                        <td><button type="button" class="btn btn-sm btn-primary transfer" data-exam_id="{{ $exam->id }}">Transfer</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
</div>
