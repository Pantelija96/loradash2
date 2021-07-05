@extends('layouts.layout')


@section('title') Lora - Login
@endsection

@section('css')
<link href="{{ asset('/') }}vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">IOT portal naplate - Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Broj korisnika</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $brojKupaca }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Broj senzora</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $brojSenzora }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Aktivni senzori
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $brojAktivnihSenzora[0]->brojAktivnihSenzora }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Broj aktivnih usluga</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $brojAktivnihServisa[0]->brojAktivnihUsluga }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">
    </div>

    <div class="col-lg-12 mb-12">

        <!-- Korisnici -->
        <div class="card shadow mb-12">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Korisnici</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Naziv korisnika</th>
                                <th>PIB</th>
                                <th>Datum registracije</th>
                                <th>Trajanje ugovora</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Naziv korisnika</th>
                                <th>PIB</th>
                                <th>Datum registracije</th>
                                <th>Trajanje ugovora</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($usluge as $u)
                                <tr>
                                    <th>{{ $u->nazivFirmeDirekcije }}</th>
                                    <th>{{ $u->pib }}</th>
                                    <th>{{ date("d.m.y",strtotime($u->datumPotpisaUgovora)) }}</th>
                                    <th>{{ $u->ugovornaObaveza }}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
