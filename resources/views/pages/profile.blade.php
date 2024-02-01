@extends('layout')

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/profile.js"></script>
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
    </script>
@endsection

@section('pageHeader')
    <!-- Page header -->
    <div class="page-header page-header-transparent">
        <div class="page-header-content">
            <div class="page-title">
                <h4> <span class="text-semibold">@if(isset($profile)) Profil @else  @endif</span></h4>

                <ul class="breadcrumb position-left">
                    <li><a href="{{ url('/home') }}">Početna</a></li>
                    <li><a href="{{ url('/#') }}">Profil </a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection


@section('content')
    <form class="form-horizontal" @if(isset($profile)) action="{{ route('profileEdit') }}"  @endif method="POST" id="forma">
        {{ csrf_field() }}
        @if(isset($profile))
            <input type="hidden" name="id_korisnik" id="id_korinsik" value="{{ $profile['id'] }}" />
        @endif
        <div class="panel panel-flat">
            <div class="panel-body">
                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-user position-left"></i>
                        Profil korisnika {{ $profile->ime.' '.$profile->prezime }}
                        <a class="control-arrow" data-toggle="collapse" data-target="#novKorisnik">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </legend>

                    <div class="collapse in" id="novKorisnik">
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="ime">Ime:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" placeholder="Ime" name="ime" id="ime" @if(isset($profile)) value="{{ $profile['ime'] }}" @else value="{{ old('ime') }}" @endif disabled>
                                <label id="ime_error" for="ime" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                @if($errors->any() && $errors->has('ime'))
                                    <label id="ime_error_2" for="ime" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="prezime">Prezime:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" placeholder="Prezime" name="prezime" id="prezime" @if(isset($profile)) value="{{ $profile['prezime'] }}" @else value="{{ old('prezime') }}" @endif disabled>
                                <label id="prezime_error" for="prezime" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                @if($errors->any() && $errors->has('prezime'))
                                    <label id="prezime_error_2" for="prezime" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="email">Email:</label>
                            <div class="col-lg-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" @if(isset($profile)) value="{{ $profile['email'] }}" @else value="{{ old('email') }}" @endif disabled>
                                <label id="email_error" for="email" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                @if($errors->any() && $errors->has('email'))
                                    <label id="email_error_2" for="email" class="validation-error-label" style="display: block;">Postoji korisnik sa zadatim emailom!</label>
                                @endif
                            </div>
                        </div>

                        @if(!isset($korisnik))
                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="lozinka">Lozinka:</label>
                                <div class="col-lg-9">
                                    <input type="password" name="lozinka" id="lozinka" class="form-control"  placeholder="Lozinka" @if(isset($profile)) value="" @else value="{{ old('lozinka') }}" @endif>
                                    <label id="lozinka_error" for="lozinka" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                    @if($errors->any() && $errors->has('lozinka'))
                                        <label id="lozinka_error_2" for="lozinka" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="lozinkaPonovo">Lozinka ponovo:</label>
                                <div class="col-lg-9">
                                    <input type="password" name="lozinka_ponovo" id="lozinka_ponovo" class="form-control" onblur="proveraLozinke()" placeholder="Lozinka ponovo" @if(isset($profile)) value="" @endif>
                                    <label id="lozinka_ponovo_error" for="lozinka_ponovo" class="validation-error-label" style="display: none;">Obavezno polje, i lozinka i lozinka ponovo moraju imati istu vrednost!</label>
                                    @if($errors->any() && $errors->has('lozinka_ponovo'))
                                        <label id="lozinka_ponovo_error_2" for="lozinka_ponovo" class="validation-error-label" style="display: block;">Obavezno polje, i lozinka i lozinka ponovo moraju imati istu vrednost!</label>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </fieldset>

                <div class="text-right">
                    <button type="button" class="btn bg-telekom-slova" onclick="proveri()" id="dugme">@if(isset($profile)) Izmeni @endif <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
@endsection
