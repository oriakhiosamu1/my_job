{{-- header goes here --}}
@include('layouts.admin.header')

    <body class="sb-nav-fixed">
        {{-- nav bar goes here --}}
        @include('layouts.admin.nav')


        <div id="layoutSidenav">
            {{-- side bar goes here --}}
            @include('layouts.admin.sidebar')

            <div id="layoutSidenav_content">
                {{-- main goes here --}}
                @include('layouts.admin.main')

                {{-- sweet alert here --}}
                @include('sweetalert::alert')

                {{-- yield comes here --}}
                @yield('content')
                {{-- yield ends here --}}

                {{-- footer goes here --}}
                @include('layouts.admin.footer')
            </div>
        </div>

        {{-- ckeditor scripts goes here --}}
        @yield('scripts')
    </body>
</html>
