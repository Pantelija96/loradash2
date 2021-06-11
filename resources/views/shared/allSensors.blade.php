@extends('layouts.layout')


@section('title') Lora - All Sensors
@endsection

@section('css')

@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Spisak svih senzora</h1>
</div>

<!-- Content Row Table -->
<div class="row">
    <div class="col-lg-12 mb-12">
        <!-- Senzori -->
        <div class="card shadow mb-12">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-danger">Svi senzori</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Naziv</th>
                          <th>Opis</th>
                          <th>Lager</th>
                          <th>Nabavna cena</th>
                          <th>Prodajna cena</th>
                          <th>Akcije</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($sensors as $s)
                          <tr>
                            <td>{{ $s->naziv }}</td>
                            <td>{{ $s->opis }}</td>
                            <td>{{ $s->komadaNaLageru }}</td>
                            <td>{{ $s->nabavnaCena }}</td>
                            <td>{{ $s->prodajnaCena }}</td>
                            <td>{{ $s->naziv }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                      <tr>
                        <th>Naziv</th>
                        <th>Opis</th>
                        <th>Lager</th>
                        <th>Nabavna cena</th>
                        <th>Prodajna cena</th>
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
