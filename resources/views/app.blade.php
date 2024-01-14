<!DOCTYPE html>
<html lang="en">

@include('sections.head')

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
         @if (Auth::check())
         <app-component ruta="{{route('basepath')}}" :usuario="{{Auth::user()->load('file')}}" ></app-component>
        @else
            <auth-component ruta="{{ route('basepath') }}"></auth-component>
        @endif



    </div>
    <!-- ./wrapper -->

    @include('sections.script')
</body>

</html>
