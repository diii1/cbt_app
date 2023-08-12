<nav class="main-sidebar ps-menu">
    <div class="sidebar-toggle action-toggle">
        <a href="#">
            <i class="fas fa-bars"></i>
        </a>
    </div>
    <div class="sidebar-opener action-toggle">
        <a href="#">
            <i class="ti-angle-right"></i>
        </a>
    </div>
    <div class="d-flex justify-content-center mt-3">
        <img src="{{ asset('assets/images/logo-removebg.png') }}" style="width:50px">
    </div>
    <div class="close-sidebar action-toggle pt-5">
        <i class="ti-close"></i>
    </div>
    <div class="sidebar-header">
    </div>
    <div class="sidebar-content">
        <ul>
            <li class="{{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="link">
                    <i class="ti-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @can('master')
                <li class="{{ request()->segment(1) == 'master' ? 'active open' : '' }}">
                    <a href="#" class="main-menu has-dropdown">
                        <i class="ti-server"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="sub-menu {{ request()->segment(1) == 'master' ? 'expand' : '' }}">
                        @can('list_subject')<li class="{{ request()->segment(1) == 'master' && request()->segment(2) == 'subjects' ? 'active' : '' }}"><a href="{{ route('subjects.index')}}" class="link"><span>Mata Pelajaran</span></a></li>@endcan
                        @can('list_class')<li class="{{ request()->segment(1) == 'master' && request()->segment(2) == 'classes' ? 'active' : '' }}"><a href="{{ route('classes.index')}}" class="link"><span>Kelas</span></a></li>@endcan
                    </ul>
                </li>
            @endcan

            @can('user')
                <li class="{{ request()->segment(1) == 'user' ? 'active open' : '' }}">
                    <a href="#" class="main-menu has-dropdown">
                        <i class="ti-user"></i>
                        <span>Data Pengguna</span>
                    </a>
                    <ul class="sub-menu {{ request()->segment(1) == 'user' ? 'expand' : '' }}">
                        @can('list_admin')<li class="{{ request()->segment(1) == 'user' && request()->segment(2) == 'admins' ? 'active' : '' }}"><a href="{{ route('admins.index')}}" class="link"><span>Administrator</span></a></li>@endcan
                        @can('list_teacher')<li class="{{ request()->segment(1) == 'user' && request()->segment(2) == 'teachers' ? 'active' : '' }}"><a href="{{ route('teachers.index')}}" class="link"><span>Guru</span></a></li>@endcan
                        @can('list_student')<li class="{{ request()->segment(1) == 'user' && request()->segment(2) == 'students' ? 'active' : '' }}"><a href="{{ route('students.index')}}" class="link"><span>Siswa</span></a></li>@endcan
                    </ul>
                </li>
            @endcan

            @can('exam')
            <li class="{{ request()->segment(1) == 'exams' ? 'active open' : '' }}">
                <a href="#" class="main-menu has-dropdown">
                    <i class="ti-book"></i>
                    <span>Data Ujian</span>
                </a>
                <ul class="sub-menu {{ request()->segment(1) == 'exams' ? 'expand' : '' }}">
                    @can('list_session')<li class="{{ request()->segment(1) == 'exams' && request()->segment(2) == 'sessions' ? 'active' : '' }}"><a href="{{ route('sessions.index')}}" class="link"><span>Sesi Ujian</span></a></li>@endcan
                    @can('list_exam')<li class="{{ request()->segment(1) == 'exams' && request()->segment(2) == null ? 'active' : '' }}"><a href="{{ route('exams.index')}}" class="link"><span>Daftar Ujian</span></a></li>@endcan
                </ul>
            </li>
            @endcan

            {{-- @can('read exam')
                <li class="{{ request()->segment(1) == 'exams' || request()->segment(1) == 'exam' ? 'active open' : '' }}">
                    <a href="#" class="main-menu has-dropdown">
                        <i class="ti-book"></i>
                        <span>Data Ujian</span>
                    </a>
                    <ul class="sub-menu {{ request()->segment(1) == 'exams' || request()->segment(1) == 'exam' ? 'expand' : '' }}">
                        @can('read exam_session')<li class="{{ request()->segment(1) == 'exam' && request()->segment(2) == 'sessions' ? 'active' : '' }}"><a href="{{ route('sessions.index')}}" class="link"><span>Sesi Ujian</span></a></li>@endcan
                        @can('read exams')<li class="{{ request()->segment(1) == 'exams' ? 'active' : '' }}"><a href="{{ route('exams.index')}}" class="link"><span>Daftar Ujian</span></a></li>@endcan
                        @can('read participant')<li class="{{ request()->segment(1) == 'exam' && request()->segment(2) == 'participants' ? 'active' : '' }}"><a href="{{ route('participants.index')}}" class="link"><span>Peserta Ujian</span></a></li>@endcan
                        @can('read exam_session')<li class="{{ request()->segment(1) == 'exam' && request()->segment(2) == 'sessions' ? 'active' : '' }}"><a href="#" class="link"><span>Sesi Ujian</span></a></li>@endcan
                        @can('read exams')<li class="{{ request()->segment(1) == 'exams' ? 'active' : '' }}"><a href="#" class="link"><span>Daftar Ujian</span></a></li>@endcan
                        @can('read participant')<li class="{{ request()->segment(1) == 'exam' && request()->segment(2) == 'participants' ? 'active' : '' }}"><a href="#" class="link"><span>Peserta Ujian</span></a></li>@endcan
                    </ul>
                </li>
            @endcan

            @can('read question')
                <li class="{{ request()->segment(1) == 'questions' ? 'active' : '' }}">
                    <a href="{{ route('questions.index') }}" class="link">
                        <i class="ti-clipboard"></i>
                        <span>Bank Soal</span>
                    </a>
                    <a href="#" class="link">
                        <i class="ti-clipboard"></i>
                        <span>Bank Soal</span>
                    </a>
                </li>
            @endcan --}}
        </ul>
    </div>
</nav>
