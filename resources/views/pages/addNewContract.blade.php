@extends('layout')

@section('additionalCss')
    <link href="{{asset('/')}}pickadate/themes/classic.css" rel="stylesheet" type="text/css">
@endsection

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/stepy.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/uniform.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}pickadate/picker.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}pickadate/picker.date.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/addnewcontract.js"></script>
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
        const stavkeIzBaze = <?php if(isset($stavke_fakture)){echo json_encode($stavke_fakture);} ?>;
    </script>
@endsection

@section('addNewActive')
    class="active"
@endsection

@section('pageHeader')
    <!-- Page header -->
    <div class="page-header page-header-transparent">
        <div class="page-header-content">
            <div class="page-title">
                <h4> <span class="text-semibold">Dodavanje novog ugovora</span></h4>

                <ul class="breadcrumb position-left">
                    <li><a href="{{ url('/home') }}">Početna</a></li>
                    <li><a href="{{ url('/addnew') }}">Dodavanje novog ugovora</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')

    <div class="panel panel-white">
        <form class="stepy-callbacks form-horizontal" action="{{ route('addNewContract') }}" method="post">
            {{ csrf_field() }}
            <fieldset title="Podaci o ugovoru">
                <legend class="text-semibold">Podaci o ugovoru</legend>

                <fieldset>
                    <br>
                    <br>
                    <div class="collapse in">
                        <div class="form-group row">
                            <label class="col-lg-2 control-label" for="idKorisnika">Id korisnika:</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" placeholder="Id korisnika" name="id_kupac" id="id_kupac">
                                <label id="id_kupac_error" for="id_kupac" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn bg-telekom-slova" onclick="getSoapUser()" >Preuzmi podatke <i class="icon-download4 position-right"></i></button>
                            </div>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" readonly placeholder="Connectivity plan" id="connectivity_plan" name="connectivity_plan">
                                <label id="connectivity_plan_error" for="connectivity_plan" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-user position-left"></i>
                        Podaci o korinsiku
                        <a class="control-arrow" data-toggle="collapse" data-target="#userData">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </legend>

                    <div class="collapse in" id="userData">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Naziv korisnika:</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" placeholder="Naziv korisnika" id="naziv_kupac" name="naziv_kupac" readonly>
                                <label id="naziv_kupac_error" for="naziv_kupac" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="pib">PIB:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="PIB" id="pib" name="pib" readonly>
                                <label id="pib_error" for="pib" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-1 control-label" for="mbr">Matični broj:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Matični broj" id="mb" name="mb" readonly>
                                <label id="mb_error" for="mb" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="email">Email:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Glavna email adresa" id="email" name="email" readonly>
                                <label id="email_error" for="email" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-1 control-label" for="telefon">Telefon:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Kontakt telefon" id="telefon" name="telefon" readonly>
                                <label id="telefon_error" for="telefon" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="kam">KAM:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="KAM" id="kam" name="kam" readonly>
                                <label id="kam_error" for="kam" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-1 control-label" for="segment">Segment:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Segment" id="segment" name="segment" readonly>
                                <label id="segment_error" for="segment" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                    </div>
                </fieldset>

                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-clipboard3 position-left"></i>
                        Detalji ugovora
                        <a class="control-arrow" data-toggle="collapse" data-target="#conData">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </legend>

                    <div class="collapse in" id="conData">

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="partner">Partner:</label>
                            <div class="col-lg-4">
                                @if(isset($partneri) && count($partneri)>0)
                                    <select name="partner[]" multiple="multiple" id="partner" data-placeholder="Partner" class="select multiple-custom">
                                        <option></option>
                                        @foreach($partneri as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <h2 class="bg-danger custom_error_msg">Desila se sistemska greska!</h2>
                                @endif
                                <label id="partner_error" for="partner" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-2 control-label" for="naziv_ugovora">Naziv ugovora:</label>
                            <div class="col-lg-4">
                                <input type="text" name="naziv_ugovora" id="naziv_ugovora" class="form-control" placeholder="Naziv ugovora">
                                <label id="naziv_ugovora_error" for="naziv_ugovora" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="tip_servisa">Tip servisa:</label>
                            <div class="col-lg-4">
                                @if(isset($tipovi_servisa) && count($tipovi_servisa)>0)
                                    <select name="tip_servisa" id="tip_servisa" data-placeholder="Tip Servisa" class="select">
                                        <option></option>
                                        @foreach($tipovi_servisa as $ts)
                                            <option value="{{ $ts->id }}">{{ $ts->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <h2 class="bg-danger custom_error_msg">Desila se sistemska greska!</h2>
                                @endif
                                <label id="tip_servisa_error" for="tip_servisa" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-2 control-label" for="naziv_servisa">Naziv servisa:</label>
                            <div class="col-lg-4">
                                @if(isset($nazivi_servisa) && count($nazivi_servisa)>0)
                                    <select name="naziv_servisa" id="naziv_servisa" data-placeholder="Naziv servisa" class="select">
                                        <option></option>
                                        @foreach($nazivi_servisa as $ns)
                                            <option value="{{ $ns->id }}">{{ $ns->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger custom_error_msg">Desila se sistemska greska!</p>
                                @endif
                                <label id="naziv_servisa_error" for="naziv_servisa" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">

                            <label class="col-lg-2 control-label" for="tipUgovora">Tip ugovora:</label>
                            <div class="col-lg-4">
                                @if((isset($tipovi_ugovora)) && count($tipovi_ugovora)>0)
                                    <select name="tip_ugovora" id="tip_ugovora" data-placeholder="Tip ugovora" class="select">
                                        <option></option>
                                        @foreach($tipovi_ugovora as $tu)
                                            <option value="{{ $tu->id }}">{{ $tu->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger custom_error_msg">Desila se sistemska greska!</p>
                                @endif
                                <label id="tip_ugovora_error" for="tip_ugovora" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="brojUgovora">Broj ugovora:</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Broj ugovora" id="broj_ugovora" name="broj_ugovora">
                                <label id="broj_ugovora_error" for="broj_ugovora" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-2 control-label" for="datum">Datum potpisa:</label>
                            <div class="col-lg-4">
                                <input type="text" name="datum" id="datum" class="form-control pickadate-selectors" placeholder="Datum potpisa">
                                <label id="datum_error" for="datum" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="zbirni_racun">Zbirni račun:</label>
                            <div class="col-lg-4">
                                <select name="zbirni_racun" onchange="createConnPlan()" id="zbirni_racun" data-placeholder="Zbirni račun" class="select">
                                    <option></option>

                                </select>
                                <label id="zbirni_racun_error" for="zbirni_racun" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <label class="col-lg-2 control-label" for="uo">Ugovorna obaveza:</label>
                            <div class="col-lg-4">
                                <select name="uo" id="uo" data-placeholder="Ugovorna obaveza" class="select">
                                    <option></option>
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="36">36</option>
                                    <option value="60">60</option>
                                    <option value="120">120</option>
                                </select>
                                <label id="uo_error" for="uo" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                    </div>
                </fieldset>

                <fieldset>
                    <legend class="text-semibold">
                        <i class="icomoon icon-cogs"></i>
                        Tehničke informacije o servisu
                        <a class="control-arrow" data-toggle="collapse" data-target="#tehInfo">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </legend>

                    <div class="collapse in" id="tehInfo">


                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="tip_tehnologije">Tip tehnologije:</label>
                            <div class="col-lg-4">
                                @if(isset($tipovi_tehnologije) && count($tipovi_tehnologije)>0)
                                    <select name="tip_tehnologije[]" multiple="multiple" id="tip_tehnologije" data-placeholder="Tip tehnologije" class="select multiple-custom">
                                        <option></option>
                                        @foreach($tipovi_tehnologije as $tt)
                                            <option value="{{ $tt->id }}">{{ $tt->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger custom_error_msg">Desila se sistemska greska!</p>
                                @endif
                                <label id="tip_tehnologije_error" for="tip_tehnologije" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>


                            <label class="col-lg-2 control-label" for="tipSenzora">Tip senzora:</label>
                            <div class="col-lg-4">
                                @if(isset($vrste_senzora) && count($vrste_senzora)>0)
                                    <select name="vrsta_senzora[]" multiple="multiple" id="vrsta_senzora" data-placeholder="Tip senzora" class="select multiple-custom">
                                        <option></option>
                                        @foreach($vrste_senzora as $senzor)
                                            <option value="{{ $senzor->id }}">{{ $senzor->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger custom_error_msg">Desila se sistemska greska!</p>
                                @endif
                                <label id="vrsta_senzora_error" for="vrsta_senzora  " class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="lokacija_app">Lokacija aplikacije:</label>
                            <div class="col-lg-4">
                                @if(isset($lokacije_app) && count($lokacije_app)>0)
                                    <select name="lokacija_app" id="lokacija_app" onchange="lokacijaApp()" data-placeholder="Lokacija aplikacije" class="select">
                                        <option></option>
                                        @foreach($lokacije_app as $lok)
                                            <option value="{{ $lok->id }}">{{ $lok->naziv }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger custom_error_msg">Desila se sistemska greska!</p>
                                @endif
                                <label id="lokacija_app_error" for="lokacija_app" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>


                            <div id="nazivWrap" style="display: none;">
                                <label class="col-lg-2 control-label" for="naziv_servera">Naziv servera:</label>
                                <div class="col-lg-4">
                                    <input type="text" name="naziv_servera" id="naziv_servera" class="form-control" placeholder="Naziv servera">
                                    <label id="naziv_servera_error" for="naziv_servera" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="ipAddresaWrap" style="display: none;">
                            <label class="col-lg-2 control-label" for="ip_adresa">IP Adresa:</label>
                            <div class="col-lg-4">
                                <input type="text" name="ip_adresa" id="ip_adresa" class="form-control" placeholder="IP Adresa">
                                <label id="ip_adresa_error" for="ip_adresa" class="validation-error-label" style="display: none;">Obavezno polje, ili nepravilan oblik ip adrese!</label>
                            </div>


                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="napomena">Napomena:</label>
                            <div class="col-lg-10">
                                <textarea rows="5" class="form-control" placeholder="Napomena" id="napomena" name="napomena"></textarea>
                            </div>
                        </div>

                    </div>
                </fieldset>
            </fieldset>

            <!-- komercijalni uslovi -->
            <fieldset>
                <legend class="text-semibold">Komercijalni uslovi</legend>

                <fieldset>
                    <legend class="text-semibold">
                        <i class="icon-comments position-left"></i>
                        Komercijalni uslovi
                        <a class="control-arrow" data-toggle="collapse" data-target="#komercijalni_uslovi">
                            <i class="icon-circle-down2"></i>
                        </a>
                    </legend>

                    <div class="collapse in" id="komercijalni_uslovi">
                        <div class="row" id="row_0"> <!-- Legend -->
                            <div class="col-md-2 form-group">
                                <label><h4><strong>Stavka fakture:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Početak:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Kraj:</strong></h4></label>
                            </div>

                            <div class="col-md-2 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Iznos naknade:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Status:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Min:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Max:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>SIM:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Uređaj:</strong></h4></label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label><h4><strong>Akcije:</strong></h4></label>
                            </div>
                        </div>


                        <div class="row" id="row_1">
                            <div class="col-md-2 form-group">
                                <select name="stavka_fakture_1" id="stavka_fakture_1" data-placeholder="Stavka fakture" class="select stavka_select" onchange="stavkaChanged(1)">
                                    <option></option>
                                </select>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <input type="text" name="datum_pocetak_1" id="datum_pocetak_1" class="form-control pickadate-selectors" placeholder="Datum početak">
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <input type="text" name="datum_kraj_1" id="datum_kraj_1" class="form-control pickadate-selectors" placeholder="Datum kraj">
                            </div>

                            <div class="col-md-2 form-group" style="margin-left: 5px;">
                                <input type="number" step=".01" min="0" name="naknada_1" id="naknada_1" class="form-control" min="0" value="0">
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <select name="status_1" id="status_1" data-placeholder="Status" class="select">
                                    <option></option>
                                    <option value="1">Aktivni</option>
                                    <option value="2">Prijavljeni</option>
                                    <option value="3">N/A</option>
                                </select>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <input type="number" step="0.1" name="min_1" id="min_1" class="form-control" value="0" step="1">
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <input type="number" step="0.1" name="max_1" id="max_1" class="form-control" value="0" step="1">
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <div class="checkbox" style="margin-left: 40%;">
                                    <input type="checkbox" class="control-primary" name="sim_1" id="sim_1" value="0">
                                </div>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <div class="checkbox" style="margin-left: 40%;">
                                    <input type="checkbox" class="control-primary" name="uredjaj_1" id="uredjaj_1" value="0">
                                </div>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px;">
                                <ul class="icons-list text-center form-control" style="border: none;" id="link_brisanje_1">
                                    <li class="text-danger-800" style="padding-top: 6px;"><a href="#"  onclick="removeRow(1)" data-popup="tooltip" title="Obriši red: 1"><i style="font-size: 20px;" class="icon-trash"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="row" id="row_1_error" style="display: none;">
                            <div class="col-md-2 form-group">
                                <label id="stavka_fakture_1_error" for="stavka_fakture_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label id="datum_pocetak_1_error" for="datum_pocetak_1" class="validation-error-label" style="display: none;">Mora biti prvi dan u mesecu!</label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label id="datum_kraj_1_error" for="datum_kraj_1" class="validation-error-label" style="display: none;">Mora biti poslednji dan u mesecu!</label>
                            </div>

                            <div class="col-md-2 form-group" style="margin-left: 5px; ">
                                <label id="naknada_1_error" for="naknada_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>

                            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                <label id="status_1_error" for="status_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <a href="#" onclick="addRow()" class="button-back btn bg-telekom-slova">Dodaj novi red <i class="icon-plus3 position-right"></i></a>
            </fieldset>
            <input type="hidden" name="aktivne_stavke" id="aktivne_stavke" value="">
            <button type="submit" class="btn bg-telekom-slova stepy-finish">Sačuvaj <i class="icon-check position-right"></i></button>
        </form>
    </div>
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
