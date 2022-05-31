
@extends('layout')

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/moment.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/picker.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/picker.date.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/picker.time.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/legacy.js"></script>
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
                        <input type="text" name="pretraga" id="pretraga" class="form-control input-xlg" placeholder="Pretraži" @if($pretraga != null) value="{{ $pretraga }}" @endif>
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
                            <li><a href="{{ url('/export')."/".$search_obj }}" class="btn telekom-tekst btn-sm"><i class="icon-file-excel position-left"></i> Izvezi u .xsl formatu</a></li>
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
                                        <input type="text" id="naziv_ugovora" name="naziv_ugovora" class="form-control" placeholder="Naziv ugovora" @if($naziv_ugovora != null) value="{{ $naziv_ugovora }}" @endif>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="serviceName" class="col-lg-5 control-label text-right">Naziv servisa:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi naziv servisa" name="naziv_servisa" id="naziv_servisa">
                                            <option value="" selected disabled></option>
                                            @if(isset($nazivi_servisa))
                                                @foreach($nazivi_servisa as $ns)
                                                    @if($ns->id == $naziv_servisa)
                                                        <option value="{{ $ns->id }}" selected>{{ $ns->naziv }}</option>
                                                    @else
                                                        <option value="{{ $ns->id }}">{{ $ns->naziv }}</option>
                                                    @endif
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
                                        <input type="text" id="broj_ugovora" name="broj_ugovora" class="form-control" placeholder="Broj ugovora" @if($broj_ugovora != null) value="{{ $broj_ugovora }}" @endif>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nazivKorisnika" class="col-lg-5 control-label text-right">Naziv korisnika:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="naziv_kupac" name="naziv_kupac" class="form-control" placeholder="Naziv korisnika" @if($naziv_kupac != null) value="{{ $naziv_kupac }}" @endif>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="connPlan" class="col-lg-5 control-label text-right">Connectivity plan:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="connectivity_plan" name="connectivity_plan" class="form-control" placeholder="Connectivity plan" @if($connectivity_plan != null) value="{{ $connectivity_plan }}" @endif>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <label for="type" class="col-lg-5 control-label text-right">Tip ugovora:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi tip ugovora" name="tip_ugovora" id="tip_ugovora">
                                            <option value="" selected disabled></option>
                                            @if(isset($tipovi_ugovora))
                                                @foreach($tipovi_ugovora as $tu)
                                                    @if($tu->id == $tip_ugovora)
                                                        <option value="{{ $tu->id }}" selected>{{ $tu->naziv }}</option>
                                                    @else
                                                        <option value="{{ $tu->id }}">{{ $tu->naziv }}</option>
                                                    @endif
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
                                            <option value="" selected disabled></option>
                                            @if(isset($partneri))
                                                @foreach($partneri as $p)
                                                    @if($p->id == $partner)
                                                        <option value="{{ $p->id }}" selected>{{ $p->naziv }}</option>
                                                    @else
                                                        <option value="{{ $p->id }}">{{ $p->naziv }}</option>
                                                    @endif
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
                                        <input type="text" name="datum_potpisa" id="datum_potpisa" class="form-control pickadate-format" placeholder="Izaberi datum&hellip;" @if($datum_potpisa != null) value="{{ $datum_potpisa }}" @endif>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="idKorisnika" class="col-lg-5 control-label text-right">ID korisnika:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="id_kupac" name="id_kupac" class="form-control" placeholder="ID korisnika" @if($id_kupac != null) value="{{ $id_kupac }}" @endif>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kam" class="col-lg-5 control-label text-right">KAM:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="kam" name="kam" class="form-control" placeholder="KAM" @if($kam != null) value="{{ $kam }}" @endif>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">

                                <div class="form-group">
                                    <label for="serviceType" class="col-lg-5 control-label text-right">Tip servisa:</label>
                                    <div class="col-lg-7">
                                        <select class="select" data-placeholder="Izaberi tip servisa" name="tip_servisa" id="tip_servisa">
                                            <option value="" selected disabled></option>
                                            @if(isset($tipovi_servisa))
                                                @foreach($tipovi_servisa as $ts)
                                                    @if($ts->id == $tip_servisa)
                                                        <option value="{{ $ts->id }}" selected>{{ $ts->naziv }}</option>
                                                    @else
                                                        <option value="{{ $ts->id }}">{{ $ts->naziv }}</option>
                                                    @endif
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
                                            <option value="" selected disabled></option>
                                            @if(isset($tipovi_tehnologije))
                                                @foreach($tipovi_tehnologije as $tt)
                                                    @if($tt->id == $tehnologija)
                                                        <option value="{{ $tt->id }}" selected>{{ $tt->naziv }}</option>
                                                    @else
                                                        <option value="{{ $tt->id }}">{{ $tt->naziv }}</option>
                                                    @endif
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
                                            <option value="" selected disabled></option>
                                            <option value="12" @if($uo == 12) selected @endif>12</option>
                                            <option value="24" @if($uo == 24) selected @endif>24</option>
                                            <option value="36" @if($uo == 36) selected @endif>36</option>
                                            <option value="60" @if($uo == 60) selected @endif>60</option>
                                            <option value="120" @if($uo == 120) selected @endif>120</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pib" class="col-lg-5 control-label text-right">PIB:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="pib" name="pib" class="form-control" placeholder="PIB" @if($pib != null) value="{{ $pib }}" @endif>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="segment" class="col-lg-5 control-label text-right">Segment:</label>
                                    <div class="col-lg-7">
                                        <input type="text" id="segment" name="segment" class="form-control" placeholder="Segment" @if($segment != null) value="{{ $segment }}" @endif>
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

        <li @if($homeType == 1 || $homeType == null) class="active" @endif ><a href="{{ url('/search').'/1?_token='.csrf_token().'&pretraga='.$pretraga.'&naziv_ugovora='.$naziv_ugovora.'&naziv_servisa='.$naziv_servisa.'&broj_ugovora='.$broj_ugovora.'&tip_ugovora='.$tip_ugovora.'&partner='.$partner.'&datum_potpisa='.$datum_potpisa.'&datum_potpisa_submit='.$datum_potpisa.'&tip_servisa='.$tip_servisa.'&tehnologija='.$tehnologija.'&uo='.$uo.'&naziv_kupac='.$naziv_kupac.'&connectivity_plan='.$connectivity_plan.'&id_kupac='.$id_kupac.'&kam='.$kam.'&pib='.$pib.'&segment='.$segment }}"><i class="icon-list position-left telekom-tekst"></i> Lista</a></li>
        <li @if($homeType == 2) class="active" @endif><a href="{{ url('/search').'/2?_token='.csrf_token().'&pretraga='.$pretraga.'&naziv_ugovora='.$naziv_ugovora.'&naziv_servisa='.$naziv_servisa.'&broj_ugovora='.$broj_ugovora.'&tip_ugovora='.$tip_ugovora.'&partner='.$partner.'&datum_potpisa='.$datum_potpisa.'&datum_potpisa_submit='.$datum_potpisa.'&tip_servisa='.$tip_servisa.'&tehnologija='.$tehnologija.'&uo='.$uo.'&naziv_kupac='.$naziv_kupac.'&connectivity_plan='.$connectivity_plan.'&id_kupac='.$id_kupac.'&kam='.$kam.'&pib='.$pib.'&segment='.$segment }}"><i class="icon-grid3 position-left telekom-tekst"></i> Matrica</a></li>
    </ul>
    <!-- /tabs -->


    <!-- Search results -->
    <div class="content-group">
        <p class="text-muted text-size-small content-group">{{ count($ugovori) }} broj ugovora za zadate filtere</p>

        <div class="search-results-list content-group">
            <div class="row">
                @if($homeType == 1 || $homeType == null)
                    <div class="panel panel-body">
                        <div class="table-responsive">
                            @if(isset($ugovori))
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
                                    @if(count($ugovori) > 0)
                                        @foreach($ugovori as $ugovor)
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

                    @if(isset($ugovori))
                        @if(count($ugovori)>0)
                            @foreach($ugovori as $ugovor)
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
