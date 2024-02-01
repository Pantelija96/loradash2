@extends('layout')

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/datatables.min.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/addnewuser.js"></script>
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
    </script>
@endsection

@section('addNewUser')
    class="active"
@endsection

@section('pageHeader')
    <!-- Page header -->
    <div class="page-header page-header-transparent">
        <div class="page-header-content">
            <div class="page-title">
                <h4> <span class="text-semibold">@if(isset($korisnik)) Izmena korisnika portala @else Dodavanje novog korisnika portala @endif</span></h4>

                <ul class="breadcrumb position-left">
                    <li><a href="{{ url('/home') }}">Početna</a></li>
                    <li><a href="{{ url('/addnewuser') }}">Dodavanje novog korisnika portala </a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Korisnici portala</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <table class="table table-bordered table-hover datatable-highlight">
                <thead class="bg-telekom-slova">
                <tr>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Email</th>
                    <th>Uloga</th>
                    <th>Poslednje logovanje</th>
                    <th class="text-center">Akcije</th>
                </tr>
                </thead>
                <tbody>
                @foreach($korisnici as $ks)
                    <tr>
                        <td>{{ $ks['ime'] }}</td>
                        <td>{{ $ks['prezime'] }}</td>
                        <td>{{ $ks['email'] }}</td>
                        <td>{{ $ks['uloga']['naziv'] }}</td>
                        <td>{{ date('d/m/Y', strtotime($ks['last_login']))." u ".date("H:i", strtotime($ks['last_login'])) }}</td>
                        <td>
                            <ul class="icons-list text-center">
                                <li class="text-danger-800"><a href="{{ url('/addnewuser/'.$ks['id']) }}" data-popup="tooltip" title="Izmeni"><i class="icon-pencil7"></i></a></li>
                                <li> | </li>
                                <li class="text-danger-800"><a href="#" onclick="brisanjeUsera({{ $ks['id'] }})" data-popup="tooltip" title="Obriši"><i class="icon-trash"></i></a></li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <form class="form-horizontal" @if(isset($korisnik)) action="{{ route('editUser') }}" @else action="{{ route('addNewUser') }}" @endif method="POST" id="forma">
        {{ csrf_field() }}
        @if(isset($korisnik))
            <input type="hidden" name="id_korisnik" id="id_korinsik" value="{{ $korisnik['id'] }}" />
        @endif
        <div class="panel panel-flat">
            <div class="panel-body">
                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-user position-left"></i>
                        Podaci o @if(!isset($korisnik)) novom @endif korisniku portala
                        <a class="control-arrow" data-toggle="collapse" data-target="#novKorisnik">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </legend>

                    <div class="collapse in" id="novKorisnik">
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="ime">Ime:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" placeholder="Ime" name="ime" id="ime" @if(isset($korisnik)) value="{{ $korisnik['ime'] }}" @else value="{{ old('ime') }}" @endif>
                                <label id="ime_error" for="ime" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                @if($errors->any() && $errors->has('ime'))
                                    <label id="ime_error_2" for="ime" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="prezime">Prezime:</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" placeholder="Prezime" name="prezime" id="prezime" @if(isset($korisnik)) value="{{ $korisnik['prezime'] }}" @else value="{{ old('prezime') }}" @endif>
                                <label id="prezime_error" for="prezime" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                @if($errors->any() && $errors->has('prezime'))
                                    <label id="prezime_error_2" for="prezime" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="email">Email:</label>
                            <div class="col-lg-9">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" @if(isset($korisnik)) value="{{ $korisnik['email'] }}" @else value="{{ old('email') }}" @endif>
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
                                    <input type="password" name="lozinka" id="lozinka" class="form-control"  placeholder="Lozinka" @if(isset($korisnik)) value="" @else value="{{ old('lozinka') }}" @endif>
                                    <label id="lozinka_error" for="lozinka" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                    @if($errors->any() && $errors->has('lozinka'))
                                        <label id="lozinka_error_2" for="lozinka" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="lozinkaPonovo">Lozinka ponovo:</label>
                                <div class="col-lg-9">
                                    <input type="password" name="lozinka_ponovo" id="lozinka_ponovo" class="form-control" onblur="proveraLozinke()" placeholder="Lozinka ponovo" @if(isset($korisnik)) value="" @else value="{{ old('lozinkaPonovo') }}" @endif>
                                    <label id="lozinka_ponovo_error" for="lozinka_ponovo" class="validation-error-label" style="display: none;">Obavezno polje, i lozinka i lozinka ponovo moraju imati istu vrednost!</label>
                                    @if($errors->any() && $errors->has('lozinka_ponovo'))
                                        <label id="lozinka_ponovo_error_2" for="lozinka_ponovo" class="validation-error-label" style="display: block;">Obavezno polje, i lozinka i lozinka ponovo moraju imati istu vrednost!</label>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="uloga">Uloga:</label>
                            <div class="col-lg-9">
                                <select name="uloga" id="uloga" data-placeholder="Uloga novog korisnika portala" class="select">
                                    <option></option>
                                    <option value="1" @if(isset($korisnik) && $korisnik['id_uloga'] == 1) selected @endif>Administrator</option>
                                    <option value="2" @if(isset($korisnik) && $korisnik['id_uloga'] == 2) selected @endif>Administrator podrške</option>
                                    <option value="3" @if(isset($korisnik) && $korisnik['id_uloga'] == 3) selected @endif>Korisnik portala</option>
                                </select>
                                <label id="uloga_error" for="uloga" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                @if($errors->any() && $errors->has('uloga'))
                                    <label id="uloga_error_2" for="uloga" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                @endif
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="text-right">
                    <button type="button" class="btn bg-telekom-slova" onclick="proveri()" id="dugme">@if(isset($korisnik)) Izmeni @else Sačuvaj  @endif <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
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
@endsection
