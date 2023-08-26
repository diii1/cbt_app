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
                            <form action="{{ $data['action'] }}" enctype="multipart/form-data" method="post">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolName" class="form-label">Nama Sekolah</label>
                                            <input type="text" name="name" placeholder="Tuliskan nama sekolah disini..." class="form-control" id="schoolName" value="{{ $profile->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolContact" class="form-label">Kontak Sekolah</label>
                                            <input type="text" name="contact" placeholder="Tuliskan kontak sekolah disini..." class="form-control" id="schoolContact" value="{{ $profile->contact }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolEmail" class="form-label">Email Sekolah</label>
                                            <input type="email" name="email" placeholder="Tuliskan email sekolah disini..." class="form-control" id="schoolEmail" value="{{ $profile->email }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="schoolAddress" class="form-label">Alamat Sekolah</label>
                                            <textarea name="address" placeholder="Tuliskan alamat sekolah disini..." class="form-control" id="schoolAddress" cols="30" rows="10">{{ $profile->address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolDistrict" class="form-label">Kecamatan</label>
                                            <input type="text" name="district" placeholder="Tuliskan kecamatan sekolah disini..." class="form-control" id="schoolDistrict" value="{{ $profile->district }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolRegency" class="form-label">Kabupaten/Kota</label>
                                            <input type="text" name="regency" placeholder="Tuliskan kabupaten/kota sekolah disini..." class="form-control" id="schoolRegency" value="{{ $profile->regency }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolProvince" class="form-label">Provinsi</label>
                                            <input type="text" name="province" placeholder="Tuliskan provinsi sekolah disini..." class="form-control" id="schoolProvince" value="{{ $profile->province }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="schoolAcreditation" class="form-label">Akreditasi</label>
                                            <input type="text" name="acreditation" placeholder="Tuliskan akreditasi sekolah disini..." class="form-control" id="schoolAcreditation" value="{{ $profile->acreditation }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="schoolLogo" class="form-label">Logo Sekolah</label>
                                            <input type="file" name="logo" class="form-control" id="schoolLogo" value="{{ $profile->logo }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ti ti-save"></i> Simpan Informasi Sekolah
                                            </button>
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
@endsection
