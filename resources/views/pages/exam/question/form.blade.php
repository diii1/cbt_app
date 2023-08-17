@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="title">
            {{ __($data['title']) }}
        </div>
        <div class="content-wrapper">
            <div class="row same-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ $data['action'] }}" method="post" id="formAction">
                                @csrf
                                @if ($data['type'] == 'edit')
                                    @method('put')
                                @endif
                                <input type="hidden" name="exam_id" value="{{ $data['exam']->id ?? $question->exam_id }}">
                                <input type="hidden" name="subject_id" value="{{ $data['exam']->subject_id ?? $question->subject_id }}">
                                <div class="row">
                                    <div class="col-md-12 mb-5">
                                        <label for="question" class="form-label">
                                            <h5>Soal Ujian</h5>
                                        </label>
                                        <textarea name="question" id="question">{!! $question->question !!}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="questionOptionsA" class="form-label">
                                            <h5>Pilihan Jawaban A</h5>
                                        </label>
                                        <div class="input-group d-block">
                                            <div class="input-group-text light">
                                                <input class="form-check-input mt-0" type="radio" name="answer" value="A"
                                                    aria-label="Radio button for following text input" @if ($question->answer->option == 'a') checked @endif>
                                            </div>
                                            <textarea name="option_a" id="questionOptionsA">{!! $question->option_a !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="questionOptionsB" class="form-label">
                                            <h5>Pilihan Jawaban B</h5>
                                        </label>
                                        <div class="input-group d-block">
                                            <div class="input-group-text light">
                                                <input class="form-check-input mt-0" type="radio" name="answer" value="B"
                                                    aria-label="Radio button for following text input" @if ($question->answer->option == 'b') checked @endif>
                                            </div>
                                            <textarea name="option_b" id="questionOptionsB">{!! $question->option_b !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="questionOptionsC" class="form-label">
                                            <h5>Pilihan Jawaban C</h5>
                                        </label>
                                        <div class="input-group d-block">
                                            <div class="input-group-text light">
                                                <input class="form-check-input mt-0" type="radio" name="answer" value="C"
                                                    aria-label="Radio button for following text input" @if ($question->answer->option == 'c') checked @endif>
                                            </div>
                                            <textarea name="option_c" id="questionOptionsC">{!! $question->option_c !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="questionOptionsD" class="form-label">
                                            <h5>Pilihan Jawaban D</h5>
                                        </label>
                                        <div class="input-group d-block">
                                            <div class="input-group-text light">
                                                <input class="form-check-input mt-0" type="radio" name="answer" value="D"
                                                    aria-label="Radio button for following text input" @if ($question->answer->option == 'd') checked @endif>
                                            </div>
                                            <textarea name="option_d" id="questionOptionsD">{!! $question->option_d !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-5">
                                        <label for="questionOptionsE" class="form-label">
                                            <h5>Pilihan Jawaban E</h5>
                                        </label>
                                        <div class="input-group d-block">
                                            <div class="input-group-text light">
                                                <input class="form-check-input mt-0" type="radio" name="answer" value="E"
                                                    aria-label="Radio button for following text input" @if ($question->answer->option == 'e') checked @endif>
                                            </div>
                                            <textarea name="option_e" id="questionOptionsE">{!! $question->option_e !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Simpan Data</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script type="text/javascript">
        const uploadImage = (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("api.tinymce.upload") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            xhr.upload.onprogress = (e) => {
                progress(e.loaded / e.total * 100);
            };

            xhr.onload = () => {
                if (xhr.status === 403) {
                    reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                    return;
                }

                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('HTTP Error: ' + xhr.status);
                    return;
                }

                const json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    reject('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                resolve(json.location);
            };

            xhr.onerror = () => {
                reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        });

        tinymce.init({
            selector: '#question',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: uploadImage
        });

        tinymce.init({
            selector: '#questionOptionsA',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: uploadImage
        });

        tinymce.init({
            selector: '#questionOptionsB',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: uploadImage
        });

        tinymce.init({
            selector: '#questionOptionsC',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: uploadImage
        });

        tinymce.init({
            selector: '#questionOptionsD',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: uploadImage
        });

        tinymce.init({
            selector: '#questionOptionsE',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: uploadImage
        });
    </script>
@endpush
