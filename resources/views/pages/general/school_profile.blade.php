<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>CBT App &mdash; Profil Sekolah</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
            integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
            crossorigin="anonymous" />

        <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/themify-icons/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/perfect-scrollbar/css/perfect-scrollbar.css') }}">

        <!-- CSS for this page only -->
        <!-- End CSS  -->

        <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-override.min.css') }}">
        <link rel="stylesheet" id="theme-color" href="{{ asset('assets/css/dark.min.css') }}">

    </head>
    <body>
        <div class="container-fluid">
            <div class="row d-flex justify-content-center mt-5">
                <div class="col-md-10">
                    <div class="title fs-2">
                        {{ __('Data dan Informasi Sekolah') }}
                    </div>
                    <div class="content-wrapper mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Formulir Informasi Data Sekolah') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('school_profile.store') }}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolName" class="form-label">Nama Sekolah</label>
                                                <input type="text" name="name" placeholder="Tuliskan nama sekolah disini..." class="form-control" id="schoolName" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolContact" class="form-label">Kontak Sekolah</label>
                                                <input type="text" name="contact" placeholder="Tuliskan kontak sekolah disini..." class="form-control" id="schoolContact" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolEmail" class="form-label">Email Sekolah</label>
                                                <input type="email" name="email" placeholder="Tuliskan email sekolah disini..." class="form-control" id="schoolEmail" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="schoolAddress" class="form-label">Alamat Sekolah</label>
                                                <textarea name="address" placeholder="Tuliskan alamat sekolah disini..." class="form-control" id="schoolAddress" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolDistrict" class="form-label">Kecamatan</label>
                                                <input type="text" name="district" placeholder="Tuliskan kecamatan sekolah disini..." class="form-control" id="schoolDistrict">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolRegency" class="form-label">Kabupaten/Kota</label>
                                                <input type="text" name="regency" placeholder="Tuliskan kabupaten/kota sekolah disini..." class="form-control" id="schoolRegency">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolProvince" class="form-label">Provinsi</label>
                                                <input type="text" name="province" placeholder="Tuliskan provinsi sekolah disini..." class="form-control" id="schoolProvince">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="schoolAcreditation" class="form-label">Akreditasi</label>
                                                <input type="text" name="acreditation" placeholder="Tuliskan akreditasi sekolah disini..." class="form-control" id="schoolAcreditation" required>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="schoolLogo" class="form-label">Logo Sekolah</label>
                                                <input type="file" name="logo" class="form-control" id="schoolLogo" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="mb-3">
                                                <input type="submit" class="btn btn-primary w-100" value="Tambahkan Informasi Sekolah">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
