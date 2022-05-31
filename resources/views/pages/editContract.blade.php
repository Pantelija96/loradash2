@extends('layout')

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
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
        const stavkeIzBaze = <?php if(isset($stavke_fakture)){echo json_encode($stavke_fakture);} ?>;
        const brojStavki = <?php echo count($komercijalni_uslovi); ?>;
        var brojRedova = brojStavki;

        var aktivneStavkeNaPocetku = [];
        var aktivneStavke = [];
        for(let i = 1; i <= brojStavki; i++){
            aktivneStavkeNaPocetku.push(i);
            aktivneStavke.push(i);
        }
    </script>
    <script type="text/javascript" src="{{ asset('/') }}js/editcontract.js"></script>
@endsection

@section('edictcontract')
    <li class="active" ><a href="#"><i class="icon-cog"></i> <span>Pregled postojeceg ugovora</span></a></li>
@endsection

@section('pageHeader')
    <!-- Page header -->
    <div class="page-header page-header-transparent">
        <div class="page-header-content">
            <div class="page-title">
                <h4> <span class="text-semibold">Podaci o ugovoru</span></h4>


                <ul class="breadcrumb position-left">
                    <li><a href="{{ url('/home') }}">Početna</a></li>
                    <li><a href="{{ url('/editcontract').'/'.$ugovor->id }}">Podaci o ugovoru</a></li>
                    <li><a href="#">{{ $ugovor->naziv_ugovra.(' - ').$ugovor->connectivity_plan }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-white">
        <form class="stepy-callbacks form-horizontal" action="{{ route('editCotract') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id_ugovor" id="id_ugovor" value="{{ $ugovor->id }}" />
            <input type="hidden" name="partneri_naziv" id="partneri_naziv" value="{{ $partneri_naziv }}"/>
            <input type="hidden" name="tehnologije_naziv" id="tehnologije_naziv" value="{{ $tehnologije_naziv }}"/>
            <input type="hidden" name="senzori_naziv" id="senzori_naziv" value="{{ $senzori_naziv }}"/>
            <input type="hidden" name="id_tip_servisa" id="id_tip_servisa" value="{{ $ugovor->id_tip_servisa }}"/>
            <input type="hidden" name="id_tip_ugovora" id="id_tip_ugovora" value="{{ $ugovor->id_tip_ugovora }}"/>
            <input type="hidden" name="datum_potpisa" id="datum_potpisa" value="{{ $ugovor->datum_potpisivanja }}"/>
            <input type="hidden" name="ugovorna_obaveza" id="ugovorna_obaveza" value="{{ $ugovor->ugovorna_obaveza }}"/>
            <input type="hidden" name="id_lokacija_aplikacije" id="id_lokacija_aplikacije" value="{{ $ugovor->id_lokacija_app }}"/>
            <input type="hidden" name="id_naziv_servisa" id="id_naziv_servisa" value="{{$ugovor->id_naziv_servisa}}">

            <fieldset title="Podaci o ugovoru">
                <legend class="text-semibold">Podaci o ugovoru</legend>

                <fieldset>
                    <br>
                    <br>
                    <div class="collapse in">
                        <div class="form-group row">
                            <label class="col-lg-2 control-label" for="id_kupac">Id korisnika:</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" placeholder="Id korisnika" name="id_kupac" id="id_kupac" value="{{ $ugovor->id_kupac }}" readonly>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn bg-telekom-slova"  disabled >Preuzmi podatke <i class="icon-download4 position-right"></i></button>
                            </div>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" id="connectivity_plan" name="connectivity_plan"value="{{ $ugovor->connectivity_plan }}" readonly>
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
                                <input type="text" class="form-control" placeholder="Naziv korisnika" id="nazivFirme" name="nazivFirme" readonly value="{{ $ugovor->naziv_kupac }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="pib">PIB:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="PIB" id="pib" name="pib" readonly value="{{ $ugovor->pib }}">
                            </div>

                            <label class="col-lg-1 control-label" for="mbr">Matični broj:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Matični broj" id="mbr" name="mbr" readonly value="{{ $ugovor->mb }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="email">Email:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Glavna email adresa" id="email" name="email" readonly value="{{ $ugovor->email }}">
                            </div>

                            <label class="col-lg-1 control-label" for="telefon">Telefon:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Kontakt telefon" id="telefon" name="telefon" readonly value="{{ $ugovor->telefon }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="kam">KAM:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="KAM" id="kam" name="kam" readonly value="{{ $ugovor->kam }}">
                            </div>

                            <label class="col-lg-1 control-label" for="segment">Segment:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="Segment" id="segment" name="segment" readonly value="{{ $ugovor->segment }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-1 control-label" for="id_linije">ID linije:</label>
                            <div class="col-lg-5">
                                <input type="text" class="form-control" placeholder="ID linije" id="id_linije" name="id_linije" readonly value="{{ $ugovor->id_linije }}">
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
                                @if(isset($partneri))
                                    <select name="partner[]" multiple="multiple" id="partner" data-placeholder="Partner" class="select" disabled>
                                        <option></option>
                                        @foreach($partneri as $pa)
                                            @if(in_array($pa->id, $partneri_ugovora))
                                                <option value="{{ $pa->id }}" selected>{{ $pa->naziv }}</option>
                                            @else
                                                <option value="{{ $pa->id }}">{{ $pa->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                            </div>

                            <label class="col-lg-2 control-label" for="naziv_ugovora">Naziv ugovora:</label>
                            <div class="col-lg-4">
                                <input type="text" name="naziv_ugovora" id="naziv_ugovora" class="form-control" placeholder="Naziv ugovora" value="{{ $ugovor->naziv_ugovra }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="tipServisa">Tip servisa:</label>
                            <div class="col-lg-4">
                                @if(isset($tipovi_servisa) )
                                    <select name="tipServisa" id="tipServisa" data-placeholder="Tip Servisa" class="select" disabled>
                                        <option></option>
                                        @foreach($tipovi_servisa as $ts)
                                            @if($ugovor->id_tip_servisa == $ts->id)
                                                <option value="{{ $ts->id }}" selected>{{ $ts->naziv }}</option>
                                            @else
                                                <option value="{{ $ts->id }}">{{ $ts->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                            </div>

                            <label class="col-lg-2 control-label" for="nazivServisa">Naziv servisa:</label>
                            <div class="col-lg-4">
                                @if(isset($nazivi_servisa))
                                    <select name="nazivServisa" id="nazivServisa" data-placeholder="Naziv servisa" class="select" disabled>
                                        <option></option>
                                        @foreach($nazivi_servisa as $ns)
                                            @if($ugovor->id_naziv_servisa == $ns->id)
                                                <option value="{{ $ns->id }}" selected>{{ $ns->naziv }}</option>
                                            @else
                                                <option value="{{ $ns->id }}">{{ $ns->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">

                            <label class="col-lg-2 control-label" for="tipUgovora">Tip ugovora:</label>
                            <div class="col-lg-4">
                                @if((isset($tipovi_ugovora)))
                                    <select name="tipUgovora" id="tipUgovora" data-placeholder="Tip ugovora" class="select" disabled>
                                        <option></option>
                                        @foreach($tipovi_ugovora as $tu)
                                            @if($ugovor->id_tip_ugovora == $tu->id)
                                                <option value="{{ $tu->id }}" selected>{{ $tu->naziv }}</option>
                                            @else
                                                <option value="{{ $tu->id }}">{{ $tu->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                                <label id="tipUgovoraError" for="tipUgovora" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="brojUgovora">Broj ugovora:</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Broj ugovora" id="broj_ugovora" name="broj_ugovora" value="{{ $ugovor->broj_ugovora }}" readonly>
                            </div>

                            <label class="col-lg-2 control-label" for="datum">Datum potpisa:</label>
                            <div class="col-lg-4">
                                <input type="text" name="datum" id="datum" class="form-control" value="{{ date("d.m.Y", strtotime($ugovor->datum_potpisivanja)) }}" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="zbirniRacun">Zbirni račun:</label>
                            <div class="col-lg-4">
                                <input type="text" name="zbirni_racun" id="zbirni_racun" class="form-control" value="{{ $ugovor->zbirni_racun }}" readonly>
                            </div>

                            <label class="col-lg-2 control-label" for="uo">Ugovorna obaveza:</label>
                            <div class="col-lg-4">
                                <select name="uo" id="uo" data-placeholder="Ugovorna obaveza" class="select" disabled>
                                    <option></option>
                                    <option value="12" @if($ugovor->ugovorna_obaveza == 12) selected @endif>12</option>
                                    <option value="24" @if($ugovor->ugovorna_obaveza == 24) selected @endif>24</option>
                                    <option value="36" @if($ugovor->ugovorna_obaveza == 36) selected @endif>36</option>
                                    <option value="60" @if($ugovor->ugovorna_obaveza == 60) selected @endif>60</option>
                                    <option value="120" @if($ugovor->ugovorna_obaveza == 120) selected @endif>120</option>
                                </select>
                                <label id="uoError" for="uo" class="validation-error-label" style="display: none;">Obavezno polje!</label>
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
                            <label class="col-lg-2 control-label" for="tipTehnologije">Tip tehnologije:</label>
                            <div class="col-lg-4">
                                @if(isset($tipovi_tehnologije))
                                    <select name="tipTehnologije[]" multiple="multiple" id="tipTehnologije" data-placeholder="Tip tehnologije" class="select" disabled>
                                        <option></option>
                                        @foreach($tipovi_tehnologije as $tt)
                                            @if(in_array($tt->id, $tehnologije_ugovora))
                                                <option value="{{ $tt->id }}" selected>{{ $tt->naziv }}</option>
                                            @else
                                                <option value="{{ $tt->id }}">{{ $tt->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                            </div>


                            <label class="col-lg-2 control-label" for="tipSenzora">Tip senzora:</label>
                            <div class="col-lg-4">
                                @if(isset($vrste_senzora))
                                    <select name="tip_senzora[]" multiple="multiple" id="tip_senzora" data-placeholder="Tip senzora" class="select" disabled>
                                        <option></option>
                                        @foreach($vrste_senzora as $senzor)
                                            @if(in_array($senzor->id, $vrste_senzora_ugovor))
                                                <option value="{{ $senzor->id }}" selected>{{ $senzor->naziv }}</option>
                                            @else
                                                <option value="{{ $senzor->id }}">{{ $senzor->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="lokacijaAplikacije">Lokacija aplikacije:</label>
                            <div class="col-lg-4">
                                @if(isset($lokacije_app))
                                    <select name="lokacijaAplikacije" id="lokacijaAplikacije" onchange="promenaLokacije()" data-placeholder="Lokacija aplikacije" class="select" disabled>
                                        <option></option>
                                        @foreach($lokacije_app as $lok)
                                            @if($ugovor->id_lokacija_app == $lok->id)
                                                <option value="{{ $lok->id }}" selected>{{ $lok->naziv }}</option>
                                            @else
                                                <option value="{{ $lok->id }}">{{ $lok->naziv }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <p class="bg-danger">Desila se sistemska greska!</p>
                                @endif
                            </div>


                            <div id="nazivWrap" style="display: none;">
                                <label class="col-lg-2 control-label" for="nazivServera">Naziv servera:</label>
                                <div class="col-lg-4">
                                    <input type="text" name="nazivServera" id="nazivServera" class="form-control" placeholder="Naziv servera" value="{{ $ugovor->naziv_servera }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="ipAddresaWrap" style="display: none;">
                            <label class="col-lg-2 control-label" for="ipAdresa">IP Adresa:</label>
                            <div class="col-lg-4">
                                <input type="text" name="ipAdresa" id="ipAdresa" class="form-control" placeholder="IP Adresa" value="{{ $ugovor->ip_adresa }}" disabled>
                            </div>


                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label" for="napomena">Napomena:</label>
                            <div class="col-lg-10">
                                <textarea rows="5" class="form-control" placeholder="Napomena" id="napomena" name="napomena" value="{{ $ugovor->napomena }}" disabled></textarea>
                            </div>
                        </div>

                    </div>
                </fieldset>

            </fieldset>

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

                        @if(isset($komercijalni_uslovi))
                            @foreach($komercijalni_uslovi as $kom_us)
                                <div class="row" id="row_{{ $loop->iteration }}">
                                    <input type="hidden" name="id_komercijalni_uslov_{{ $loop->iteration }}" id="id_komercijalni_uslov_{{ $loop->iteration }}" value="{{ $kom_us['id'] }}" />

                                    <div class="col-md-2 form-group">
                                        <select name="stavka_fakture_{{ $loop->iteration }}" id="stavka_fakture_{{ $loop->iteration }}" class="select">
                                            <option>{{ $kom_us['stavka_fakture']->naziv }}{{ $kom_us['vrsta_senzora'] != null ? $kom_us['vrsta_senzora']->naziv : '' }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                        <input type="text" name="datum_pocetak_{{ $loop->iteration }}" id="datum_pocetak_{{ $loop->iteration }}" class="form-control" value="{{ $kom_us['datum_pocetak'] }}" disabled>
                                        <input type="hidden" name="datum_pocetak_hidden_{{ $loop->iteration }}" id="datum_pocetak_hidden_{{ $loop->iteration }}" value="{{ $kom_us['datum_pocetak'] }}" />
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <input type="text" onchange="izmenaDatuma()" name="datum_kraj_{{ $loop->iteration }}" id="datum_kraj_{{ $loop->iteration }}" class="form-control pickadate-selectors" value="{{ $kom_us['datum_kraj'] }}" @if(!(date('Y', strtotime($kom_us['datum_kraj'])) >= date('Y') && date('m', strtotime($kom_us['datum_kraj'])) >= date('m'))) disabled @endif>
                                    </div>

                                    <div class="col-md-2 form-group" style="margin-left: 5px;">
                                        <input type="number" name="naknada_{{ $loop->iteration }}" id="naknada_{{ $loop->iteration }}" class="form-control" readonly value="{{ $kom_us['naknada'] }}">
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <select name="status_{{ $loop->iteration }}" id="status_{{ $loop->iteration }}" data-placeholder="Status" class="select">
                                            @switch(intval($kom_us['status']))
                                                @case(1)
                                                    <option value="1">Aktivni</option>
                                                    @break
                                                @case(2)
                                                    <option value="2">Prijavljeni</option>
                                                    @break
                                                @case(3)
                                                    <option value="3">N/A</option>
                                                    @break
                                            @endswitch
                                        </select>
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <input type="number" name="min_{{ $loop->iteration }}" id="min_{{ $loop->iteration }}" class="form-control" value="{{ $kom_us['min'] }}" readonly>
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <input type="number" name="max_{{ $loop->iteration }}" id="max_{{ $loop->iteration }}" class="form-control" value="{{ $kom_us['max'] }}" readonly>
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <div class="checkbox" style="margin-left: 40%;">
                                            <input type="checkbox" class="control-primary" name="sim_{{ $loop->iteration }}" id="sim_{{ $loop->iteration }}" {{ $kom_us['sim'] != null ? 'checked' : '' }} disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <div class="checkbox" style="margin-left: 40%;">
                                            <input type="checkbox" class="control-primary" name="uredjaj_{{ $loop->iteration }}" id="uredjaj_{{ $loop->iteration }}" {{ $kom_us['uredjaj'] != null ? 'checked' : '' }} disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px;">
                                        <ul class="icons-list text-center form-control" style="border: none;" id="link_brisanje_{{ $loop->iteration }}">
                                            @if(($kom_us['stavka_fakture']->tip_naknade == 2) && (date("Y", strtotime($kom_us['datum_pocetak'])) >= date("Y") && date('m', strtotime($kom_us['datum_pocetak'])) >= date('m')))
                                                <li class="text-danger-800" style="padding-top: 6px;"><a href="#"  onclick="removeActiveRow({{ $loop->iteration }})" data-popup="tooltip" title="Obriši red: {{ $loop->iteration }}"><i style="font-size: 20px;" class="icon-trash"></i></a></li>
                                            @else
                                                <li class="text-danger-800" style="padding-top: 6px;"><a href="#"  onclick="return;" ><i style="font-size: 20px;" class="icon-close2"></i></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="row" id="row_{{ $loop->iteration }}_error" style="display: none;">
                                    <div class="col-md-2 form-group">
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                        <label id="datum_kraj_{{ $loop->iteration }}_error" for="datum_kraj_{{ $loop->iteration }}" class="validation-error-label" style="display: none;">Mora biti poslednji dan u mesecu!</label>
                                    </div>

                                    <div class="col-md-2 form-group" style="margin-left: 5px; ">
                                    </div>

                                    <div class="col-md-1 form-group" style="margin-left: 5px; ">
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </fieldset>
                <a href="#" onclick="addRow()" class="button-back btn bg-telekom-slova">Dodaj novi red <i class="icon-plus3 position-right"></i></a>
                <a href="#" onclick="deaktivirajUgovor({{$ugovor->id}})" class="button-back btn bg-telekom-slova">Deaktivacija ugovora <i class="icon-trash position-right"></i></a>
            </fieldset>

            <input type="hidden" name="aktivne_stavke" id="aktivne_stavke" value="">
            <button type="submit" class="btn bg-telekom-slova stepy-finish" id="submit_izmene" disabled>Sačuvaj <i class="icon-check position-right"></i></button>
        </form>
    </div>
@endsection
