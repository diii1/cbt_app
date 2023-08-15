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
                            <textarea name="editor" id="editor"></textarea>
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
        tinymce.init({
            selector: 'textarea#editor',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_url: '{{ route("api.tinymce.upload") }}',
            images_upload_credentials: true,
            images_upload_handler: async function (blobInfo, success, failure) {
                try {
                    const response = await uploadImage(blobInfo.blob(), blobInfo.filename());
                    const { location } = response;
                    const alt = 'image-questions'; // Set the alt text here
                    const defaultDimensions = {
                        width: '300', // Default width in pixels
                        height: '200' // Default height in pixels
                    };

                    const imageInfo = {
                        src : {
                            meta : {
                                url: location
                            }
                        },
                        alt: alt,
                        dimensions : defaultDimensions,
                        fileinput : [{}]
                    };

                    console.log(imageInfo);
                    success(imageInfo);
                } catch (error) {
                    console.log(error);
                    failure('Image upload failed: ' + error);
                }
            },
        });

        async function uploadImage(blob, filename) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("api.tinymce.upload") }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        const json = JSON.parse(xhr.responseText);
                        resolve(json);
                    } else {
                        reject('HTTP Error: ' + xhr.status);
                    }
                };

                xhr.onerror = () => {
                    reject('Image upload failed');
                };

                const formData = new FormData();
                formData.append('file', blob, filename);
                xhr.send(formData);
            });
        }
    </script>
@endpush
