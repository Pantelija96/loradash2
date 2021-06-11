@extends('layouts.layout')


@section('title') Lora - Users (KAMS)
@endsection

@section('css')

@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Key account manager-i (KAM-ovi)</h1>
</div>

<!-- Content Row Table -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <!-- Korisnici -->
        <!-- samo key account manageri ne ispisuju se admini -->
        <div class="card shadow mb-12">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Key account manager-i (KAM-ovi)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>Email</th>
                                <th>Datum registracije</th>
                                <th>Datum Poslednjeg logovanja</th>
                                <th>Uloga</th>
                                <th>Akcije</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($korisnici as $korisnik)
                            @if($korisnik->idUloga != 1)
                              <tr>
                                <td>{{ $korisnik->ime }}</td>
                                <td>{{ $korisnik->prezime }}</td>
                                <td>{{ $korisnik->email }}</td>
                                <td>{{ date('d/m/Y',strtotime($korisnik->datumRegistracije)) }}</td>
                                <td>{{ date('d/m/Y',strtotime($korisnik->datumPoslednjegLogovanja)) }}</td>
                                <td>{{ $korisnik->naziv }}</td>
                                <td style="text-align: center;">
                                  <a href="{{ url('/useredit/'.$korisnik->idKorisnikSistema) }}"><i class="fas  fa-lg  fa-edit"></i></a>
                                </td>
                              </tr>
                            @endif
                          @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Ime</th>
                                <th>Prezime</th>
                                <th>Email</th>
                                <th>Datum registracije</th>
                                <th>Datum Poslednjeg logovanja</th>
                                <th>Uloga</th>
                                <th>Akcije</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
