@extends('layout')

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/picker.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/picker.date.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/picker.time.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/legacy.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/home.js"></script>
@endsection

@section('homePageActive')
    class="active"
@endsection

@section('pageHeader')
    <!-- Page header -->
    <div class="page-header page-header-transparent">
        <div class="page-header-content">
            <div class="page-title">
                <h4> <span class="text-semibold">Početna strana</span> - prikaz/pretraga ugovora</h4>

                <ul class="breadcrumb position-left">
                    <li><a href="{{ url('/home') }}">Početna</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')

    <!-- Search field -->

    <form action="{{ route('search') }}" method="GET" class="form-horizontal">
        {{ csrf_field() }}
        <input type="hidden" name="view" id="view" value="{{ $homeType }}"/>
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Pretraga ugovora</h5>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div>
            </div>

            <div class="panel-body">

                <div class="input-group content-group">
                    <div class="has-feedback has-feedback-left">
                        <input type="text" name="pretraga" id="pretraga" class="form-control input-xlg" placeholder="Pretraži">
                        <div class="form-control-feedback">
                            <i class="icon-search4 text-muted text-size-base"></i>
                        </div>
                    </div>

                    <div class="input-group-btn">
                        <button type="submit" class="btn bg-telekom-slova btn-xlg">Pretraga</button>
                    </div>
                </div>

                <div class="row search-option-buttons">
                    <div class="col-sm-6">
                        <ul class="list-inline list-inline-condensed no-margin-bottom">
                            <li><a href="#" onclick="showFilters()" class="btn telekom-tekst btn-sm"><i class="icon-filter3 position-left"></i> Prikazi filtere</a></li>
                            <li><a href="{{ url('/export') }}" class="btn telekom-tekst btn-sm"><i class="icon-file-excel position-left"></i> Izvezi u .xsl formatu</a></li>
                            <li><a href="{{ url('/addnew') }}" class="btn telekom-tekst btn-sm"><i class="icon-plus3 position-left"></i> Dodaj novi ugovor</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="panel panel-flat" id="filteri" >
            <div class="panel-heading">
                <h5 class="panel-title"><i class="icon-filter3 position-left"></i> Filteri</h5>
            </div>

            <div class="panel-body">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <label for="name" class="col-lg-5 control-label text-right">Naziv ugovora:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="naziv_ugovora" name="naziv_ugovora" class="form-control" placeholder="Naziv ugovora">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="serviceName" class="col-lg-5 control-label text-right">Naziv servisa:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi naziv servisa" name="naziv_servisa" id="naziv_servisa">
                                            <option value="" selected></option>
                                            @if(isset($nazivi_servisa))
                                                @foreach($nazivi_servisa as $ns)
                                                    <option value="{{ $ns->id }}">{{ $ns->naziv }}</option>
                                                @endforeach
                                            @else
                                                <option>Desila se greska!</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="number" class="col-lg-5 control-label text-right">Broj ugovora:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="broj_ugovora" name="broj_ugovora" class="form-control" placeholder="Broj ugovora">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nazivKorisnika" class="col-lg-5 control-label text-right">Naziv korisnika:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="naziv_kupac" name="naziv_kupac" class="form-control" placeholder="Naziv korisnika">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="connPlan" class="col-lg-5 control-label text-right">Connectivity plan:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="connectivity_plan" name="connectivity_plan" class="form-control" placeholder="Connectivity plan">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <label for="type" class="col-lg-5 control-label text-right">Tip ugovora:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi tip ugovora" name="tip_ugovora" id="tip_ugovora">
                                            <option value="" selected></option>
                                            @if(isset($tipovi_ugovora))
                                                @foreach($tipovi_ugovora as $tu)
                                                    <option value="{{ $tu->id }}">{{ $tu->naziv }}</option>
                                                @endforeach
                                            @else
                                                <option>Desila se greska!</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="partner" class="col-lg-5 control-label text-right">Partner:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi partnera" name="partner" id="partner">
                                            <option value="" selected></option>
                                            @if(isset($partneri))
                                                @foreach($partneri as $p)
                                                    <option value="{{ $p->id }}">{{ $p->naziv }}</option>
                                                @endforeach
                                            @else
                                                <option>Desila se greska!</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="datum" class="col-lg-5 control-label text-right">Datum potpisa:</label>
                                    <div class="col-lg-7 input-group">
                                        <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                                        <input type="text" name="datum_potpisa" id="datum_potpisa" class="form-control pickadate-format" placeholder="Izaberi datum&hellip;">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="idKorisnika" class="col-lg-5 control-label text-right">ID korisnika:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="id_kupac" name="id_kupac" class="form-control" placeholder="ID korisnika:">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kam" class="col-lg-5 control-label text-right">KAM:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="kam" name="kam" class="form-control" placeholder="KAM">
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <label for="serviceType" class="col-lg-5 control-label text-right">Tip servisa:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi tip servisa" name="tip_servisa" id="tip_servisa">
                                            <option value="" selected></option>
                                            @if(isset($tipovi_servisa))
                                                @foreach($tipovi_servisa as $ts)
                                                    <option value="{{ $ts->id }}">{{ $ts->naziv }}</option>
                                                @endforeach
                                            @else
                                                <option>Desila se greska!</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="technology" class="col-lg-5 control-label text-right">Tip tehnologije:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi tip tehnologije" name="tehnologija" id="tehnologija">
                                            <option value="" selected></option>
                                            @if(isset($tipovi_tehnologije))
                                                @foreach($tipovi_tehnologije as $tt)
                                                    <option value="{{ $tt->id }}">{{ $tt->naziv }}</option>
                                                @endforeach
                                            @else
                                                <option>Desila se greska!</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="uo" class="col-lg-5 control-label text-right">Ugovorna obaveza:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi ugovornu obavezu" name="uo" id="uo">
                                            <option value="" selected></option>
                                            <option value="12">12</option>
                                            <option value="24">24</option>
                                            <option value="36">36</option>
                                            <option value="60">60</option>
                                            <option value="120">120</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pib" class="col-lg-5 control-label text-right">PIB:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="pib" name="pib" class="form-control" placeholder="PIB">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="segment" class="col-lg-5 control-label text-right">Segment:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="segment" name="segment" class="form-control" placeholder="Segment">
                                    </div>
                                </div>



                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn bg-telekom-slova">Primeni filtere <i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <!-- /search field -->

    <!-- Tabs -->
    <ul class="nav nav-lg nav-tabs nav-tabs-bottom search-results-tabs">

        <li @if($homeType == 1 || $homeType == null) class="active" @endif ><a href="{{ url('/home').'/1' }}"><i class="icon-list position-left telekom-tekst"></i> Lista</a></li>
        <li @if($homeType == 2) class="active" @endif><a href="{{ url('/home').'/2' }}"><i class="icon-grid3 position-left telekom-tekst"></i> Matrica</a></li>
    </ul>
    <!-- /tabs -->

    <!-- Search results -->
    <div class="content-group">
        <p class="text-muted text-size-small content-group">{{ count($sviUgovori) }} ugovora</p>

        <div class="search-results-list content-group">
            <div class="row">
                @if($homeType == 1 || $homeType == null)
                    <div class="panel panel-body">
                        <div class="table-responsive">
                            @if(isset($sviUgovori))
                                <table class="table">
                                    <thead class="bg-telekom-slova">
                                    <tr>
                                        <th>Naziv ugovora</th>
                                        <th>Naziv korisnika</th>
                                        <th>PIB</th>
                                        <th>ID Korisnika</th>
                                        <th>Connectivity plan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($sviUgovori) > 0)
                                        @foreach($sviUgovori as $ugovor)
                                            <tr>
                                                <td><a href="{{ url('/editcontract').'/'.$ugovor["id"] }}"> {{ $ugovor["naziv_ugovra"] }} </a></td>
                                                <td>{{ $ugovor["naziv_kupac"] }}</td>
                                                <td>{{ $ugovor["pib"] }}</td>
                                                <td>{{ $ugovor["id_kupac"] }}</td>
                                                <td>{{ $ugovor["connectivity_plan"] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">Nema zapisa u bazi!</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            @else
                                <p class="bg-danger-800">Desila se greska!</p>
                            @endif
                        </div>
                    </div>
                @else
                    @if(isset($sviUgovori))
                        @if(count($sviUgovori)>0)
                            @foreach($sviUgovori as $ugovor)
                                <div class="col-lg-3 col-sm-6">
                                    <div class="panel">
                                        <div class="panel-heading bg-telekom-slova">
                                            <h5 class="panel-title"><a href="{{ url('/editcontract').'/'.$ugovor["id"] }}"> {{ $ugovor["naziv_ugovra"] }} </a></h5>
                                        </div>
                                        <div class="panel-body">
                                            <p>Naziv korisnika: <strong>{{ $ugovor["naziv_kupac"] }} </strong></p>
                                            <p>PIB: <strong>{{ $ugovor["pib"] }} </strong></p>
                                            <p>ID Korisnika: <strong>{{ $ugovor["id_kupac"] }} </strong></p>
                                            <p>Connectivity plan: <strong>{{ $ugovor["connectivity_plan"] }} </strong></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-lg-3 col-sm-6">
                                <div class="panel">
                                    <div class="panel-heading bg-telekom-slova">
                                        <h5 class="panel-title"><a href="#">Nema zapisa u bazi!</a></h5>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @else
                        <div class="panel-danger">
                            <div class="panel-heading bg-telekom-slova">
                                <h5 class="panel-title"><a href="#">Desila se greska!</a></h5>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- <ul class="pagination pagination-flat text-center pagination-xs no-margin-bottom">
            Paginacija
        </ul> -->
    </div>
    <!-- /search results -->

@endsection
