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
                            <div class="row">
                                <div class="col-md-12 mt-2 d-flex justify-content-end">
                                    <a href="{{ route('school_profile.edit', $profile->id) }}" class="btn btn-warning">Edit Profil</a>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolName" class="form-label">Nama Sekolah</label>
                                        <input type="text" name="name" class="form-control" id="schoolName" value="{{ $profile->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolContact" class="form-label">Kontak Sekolah</label>
                                        <input type="text" name="contact" class="form-control" id="schoolContact" value="{{ $profile->contact }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolEmail" class="form-label">Email Sekolah</label>
                                        <input type="email" name="email" class="form-control" id="schoolEmail" value="{{ $profile->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="schoolAddress" class="form-label">Alamat Sekolah</label>
                                        <textarea name="address" class="form-control" id="schoolAddress" cols="30" rows="10" readonly>{{ $profile->address }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolDistrict" class="form-label">Kecamatan</label>
                                        <input type="text" name="district" class="form-control" id="schoolDistrict" value="{{ $profile->district }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolRegency" class="form-label">Kabupaten/Kota</label>
                                        <input type="text" name="regency" class="form-control" id="schoolRegency" value="{{ $profile->regency }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolProvince" class="form-label">Provinsi</label>
                                        <input type="text" name="province" class="form-control" id="schoolProvince" value="{{ $profile->province }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="schoolAcreditation" class="form-label">Akreditasi</label>
                                        <input type="text" name="acreditation" class="form-control" id="schoolAcreditation" value="{{ $profile->acreditation }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="schoolLogo" class="form-label">Logo Sekolah</label>
                                        {{-- <img src="{{ $profile->logo ? asset($profile->logo) : asset('assets/images/logo-removebg.png') }}" alt="logo" id="logo" class="img-fluid"> --}}
                                        <input type="text" class="form-control" value="{{ $profile->logo }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
