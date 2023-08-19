<nav class="navbar navbar-expand-lg navbar-light bg-light px-5">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}"><img class="me-2" src="{{ asset('assets/images/logo-removebg.png') }}" alt="logo" style="width: 45px"/> CBT App | MTs Faqih Hasyim</a>
        <div id="clock" class="d-flex justify-content-between align-items-center h-100">
            <div id="date" class="me-3"></div>
            <div id="time"></div>
        </div>
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <div class="d-flex justify-content-end" id="navbarText">
                <span class="navbar-text">
                    <b class="me-3">Selamat datang, {{ auth()->user()->name }}</b>
                    <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                </span>
            </div>
        </form>
    </div>
</nav>
