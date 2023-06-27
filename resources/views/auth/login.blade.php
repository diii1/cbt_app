<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>App CBT &mdash; {{ $profile->name ?? '' }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
            integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
            crossorigin="anonymous" />
        <link rel="icon" type="image/png" href="{{ isset($profile->logo) ? asset('storage/' . $profile->logo) : asset('assets/images/logo-removebg.png') }}" />

        <link rel="stylesheet" href="{{ asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <!-- <link rel="stylesheet" href="../vendor/themify-icons/themify-icons.css"> -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-override.css') }}">
    </head>

    <body style="background-color: lightgray">
        <section class="container h-100">
            <div class="row justify-content-sm-center h-100 align-items-center">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-8">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">
                            <h1 class="fs-4 text-center fw-bold mb-4">Masuk Aplikasi CBT</h1>
                            <div class="row justify-content-center mb-3">
                                <img src="{{ isset($profile->logo) ? asset('storage/' . $profile->logo) : asset('assets/images/logo.png') }}" alt="logo" style="width:200px">
                            </div>
                            <h3 class="fs-5 text-center fw-bold mb-4">{{ $profile->name }}</h3>
                            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="" autocomplete="off">
                                @csrf
                                <div class="mb-3">
                                    <label class="mb-2 text-muted" for="email" :value="__('Email')">Alamat E-Mail</label>
                                    <div class="input-group input-group-join mb-3">
                                        <input id="email" type="email" placeholder="Masukkan Alamat Email" class="form-control" name="email" :value="old('email')" required autofocus>
                                        <span class="input-group-text rounded-end">&nbsp<i class="fa fa-envelope"></i>&nbsp</span>
                                        <div class="invalid-feedback">
                                            Format email salah.
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted" for="password" :value="__('Password')">Kata Sandi</label>
                                    <div class="input-group input-group-join mb-3">
                                        <input type="password" class="form-control" name="password" placeholder="Masukkan Kata Sandi" required>
                                        <span class="input-group-text rounded-end password cursor-pointer">&nbsp<i class="fa fa-eye"></i>&nbsp</span>
                                        <div class="invalid-feedback">
                                            Kata sandi tidak boleh kosong.
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-5">
                                    <button class="btn btn-primary icon-left" type="submit" style="width:40%"><b>Masuk &nbsp;<i class="fa fa-lock"></i></b></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-4 text-muted">
                        Copyright &copy; {{ date('Y') }} &mdash; {{ $profile->name }}
                    </div>
                </div>
            </div>
        </section>

        @include('sweetalert::alert')
        <script src="{{ asset('assets/js/login.js') }}"></script>
    </body>
</html>
