@extends('layout')

@section('additionalThemeJs')
    <script type="text/javascript" src="{{ asset('/') }}js/select2.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/pnotify.min.js"></script>
    <script type="text/javascript" src="{{ asset('/') }}js/uniform.min.js"></script>
@endsection

@section('additionalAppJs')
    <script type="text/javascript" src="{{ asset('/') }}js/tehnologija.js"></script>
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
    </script>
@endsection

@section('systemmanaging')
    class="active"
@endsection

@section('tehnologija')
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
                    <li><a href="#">Tehnologija</a></li>
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
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($tipovi_tehnologija) > 0)
                        @foreach($tipovi_tehnologija as $tt)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tt->naziv }}</td>
                                <td>
                                    <ul class="icons-list text-center">
                                        <li class="text-danger-800"><a href="{{ url('/menage/tehnologije/'.$tt->id) }}" data-popup="tooltip" title="Izmeni"><i class="icon-pencil7"></i></a></li>
                                        <li> | </li>
                                        <li class="text-danger-800"><a href="#" onclick="deleteRecord({{ $tt->id }})" data-popup="tooltip" title="Obriši"><i class="icon-trash"></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    @endif

                    </tbody>
                </table>
            </div>

            <form class="form-horizontal" @if(isset($tip_tehnologije))  action="{{ route('editTehnologija') }}" @else  action="{{ route('insertTehnologija') }}" @endif method="POST" id="form_tehnologija">
                {{ csrf_field() }}
                @if(isset($tip_tehnologije))
                    <input type="hidden" name="id_tehnologija" id="id_tehnologija" value="{{ $tip_tehnologije->id }}" />
                @endif
                <div class="panel panel-flat" style="border: none;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                @if(isset($tip_tehnologije))
                                    <h5 class="panel-title">Izmeni tip tehnologije</h5>
                                @else
                                    <h5 class="panel-title">Dodaj novi tip tehnologije</h5>
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
                                        <input type="text" name="naziv" id="naziv" class="form-control" placeholder="Naziv tipa tehnologije" @if(isset($tip_tehnologije)) value="{{ $tip_tehnologije->naziv }}" @else value="{{ old('naziv') }}" @endif>
                                        <label id="naziv_error" for="naziv" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                                        @if($errors->has('naziv') && $errors->any())
                                            <label id="naziv_error_2" for="naziv_error_2" class="validation-error-label" style="display: block;">Obavezno polje!</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="button" class="btn bg-telekom-slova" onclick="proveraUnosa()">@if(isset($tip_tehnologije)) Sačuvaj izmene @else Dodaj @endif <i class="icon-arrow-right14 position-right"></i></button>
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
