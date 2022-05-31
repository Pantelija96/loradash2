@extends('layout')

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/uniform.min.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/stavkafakture.js"></script>
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
    </script>
@endsection

@section('systemmanaging')
    class="active"
@endsection

@section('stavkafakture')
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
                    <li><a href="#">Stavka fakture</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /page header -->
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-body">

            <div class="table-responsive">
                <table class="table table-bordered table-framed">
                    <thead class="bg-telekom-slova">
                    <tr>
                        <th>#</th>
                        <th>Naziv</th>
                        <th>Tip naknade</th>
                        <th>Mesečna naknada</th>
                        <th>Zavisi od vrste senzora</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($stavke_fakture) > 0)
                        @foreach($stavke_fakture as $sf)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sf->naziv }}</td>
                                <td>@if($sf->tip_naknade==1) Mesečna @else Jednokratna @endif</td>
                                <td>{{ $sf->naknada }}</td>
                                <td>
                                    <input type="checkbox" class="control-primary" @if($sf->zavisi_od_vrste_senzora == 1) checked @endif disabled />
                                </td>
                                <td>
                                    <ul class="icons-list text-center">
                                        <li class="text-danger-800"><a href="{{ url('/menage/stavkafakture/'.$sf->id) }}" data-popup="tooltip" title="Izmeni"><i class="icon-pencil7"></i></a></li>
                                        <li> | </li>
                                        <li class="text-danger-800"><a href="#" onclick="deleteRecord({{ $sf->id }})" data-popup="tooltip" title="Obriši"><i class="icon-trash"></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    @endif

                    </tbody>
                </table>
            </div>

            <form class="form-horizontal" @if(isset($stavka_fakture))  action="{{ route('editStavkaFakture') }}" @else  action="{{ route('insertStavkaFakture') }}" @endif method="POST" id="form_stavka_fakture">
                {{ csrf_field() }}
                @if(isset($stavka_fakture))
                    <input type="hidden" name="id_stavka_fakture" id="id_stavka_fakture" value="{{ $stavka_fakture->id }}" />
                @endif
                <div class="panel panel-flat" style="border: none;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                @if(isset($stavka_fakture))
                                    <h5 class="panel-title">Izmeni stavku fakture</h5>
                                @else
                                    <h5 class="panel-title">Dodaj novu stavku fakture</h5>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="naziv">Naziv:</label>
                                    <div class="col-lg-9">
                                        <input type="text" name="naziv" id="naziv" class="form-control" placeholder="Naziv stavke fakture" @if(isset($stavka_fakture)) value="{{ $stavka_fakture->naziv }}" @else value="{{ old('naziv') }}" @endif>
                                        <label id="naziv_error" for="naziv" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                        @if($errors->has('naziv') && $errors->any())
                                            <label id="naziv_error_2" for="naziv_error_2" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="naknada">Naknada:</label>
                                    <div class="col-lg-9">
                                        <input type="number" name="naknada" id="naknada" class="form-control" step="0.1" min="0" @if(isset($stavka_fakture)) value="{{ $stavka_fakture->naknada }}" @else value="0" @endif>
                                        <label id="naknada_error" for="naknada" class="validation-error-label" style="display: none;">Mora biti vece od 0!</label>
                                        @if($errors->has('naknada') && $errors->any())
                                            <label id="naknada_error_2" for="naknada_error_2" class="validation-error-label" style="display: block;">Mora biti vece od 0!</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="tip_naknade">Tip naknade:</label>
                                    <div class="col-lg-9">
                                        <select name="tip_naknade" id="tip_naknade" data-placeholder="Tip naknade" class="select">
                                            <option></option>
                                            <option value="1" @if(isset($stavka_fakture) && $stavka_fakture->tip_naknade == 1) selected @endif>Mesečna</option>
                                            <option value="2" @if(isset($stavka_fakture) && $stavka_fakture->tip_naknade == 2) selected @endif>Jednokratna</option>
                                        </select>
                                        <label id="tip_naknade_error" for="tip_naknade" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                        @if($errors->has('tip_naknade') && $errors->any())
                                            <label id="tip_naknade_error_2" for="tip_naknade" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="zavisi_od_vrste_senzora">Zavisi od vrste senzora:</label>
                                    <div class="col-lg-9">
                                        <div class="checkbox">
                                            <input type="checkbox" class="control-primary" name="zavisi_od_vrste_senzora" id="zavisi_od_vrste_senzora" @if(isset($stavka_fakture) && $stavka_fakture->zavisi_od_vrste_senzora == true) checked @endif  />
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="button" class="btn bg-telekom-slova" onclick="proveriStavkuFakture()">@if(isset($stavka_fakture)) Sačuvaj izmene @else Dodaj @endif <i class="icon-arrow-right14 position-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
        </div>
    </div>

@endsection
