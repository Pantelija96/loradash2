@extends('layout')

@section('additionalCss')
    <link href="{{asset('/')}}css/extras/animate.min.css" rel="stylesheet" type="text/css">
@endsection

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/systemmanaging.js"></script>
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
    </script>
@endsection

@section('systemmanaging')
    class="active"
@endsection

@section('pageHeader')
    <!-- Page header -->
    <div class="page-header page-header-transparent">
        <div class="page-header-content">
            <div class="page-title">
                <h4> <span class="text-semibold">Menadžment sistema</span></h4>

                <ul class="breadcrumb position-left">
                    <li><a href="{{ url('/home') }}">Početna</a></li>
                    <li><a href="#">Menadžment sistema</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="tabbable tab-content-bordered">
                <ul class="nav nav-tabs nav-justified bg-telekom-slova">
                    @yield('navigation')
                </ul>

                <div class="tab-content">
                    @yield('form')
                </div>
            </div>
        </div>
    </div>
    @if($errors->any())
        <script>
            new PNotify({
                title: 'Greška!',
                text: 'Ispravite greške za nastavak!',
                addclass: 'bg-telekom-slova',
                hide: false,
                buttons: {
                    sticker: false
                }
            });
        </script>
    @endif
    @empty(!session('greska'))
        <script>
            $( document ).ready(function() {
                new PNotify({
                    title: 'Greška!',
                    text: '{{ session('greska') }}',
                    addclass: 'bg-telekom-slova',
                    hide: false,
                    buttons: {
                        sticker: false
                    }
                });
            });
        </script>
        @php
            Illuminate\Support\Facades\Session::forget('greska');
        @endphp
    @endempty
    @empty(!session('success'))
        <script>
            $( document ).ready(function() {
                new PNotify({
                    title: 'Uspeh!',
                    text: '{{ session('success') }}',
                    addclass: 'bg-success'
                });
            });
        </script>
        @php
            Illuminate\Support\Facades\Session::forget('success');
        @endphp
    @endempty
@endsection
