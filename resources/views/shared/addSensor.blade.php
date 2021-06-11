@extends('layouts.layout')


@section('title') Lora - Add Sensor
@endsection

@section('css')

@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dodavanje novog senzora</h1>
</div>

<!-- Content Row Table -->
    <div class="card shadow mb-12">

        <div class="card-body">

            <form method="POST" action="{{ route('addSensor') }}">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label for="nazivSenzora" class="col-sm-2 col-form-label">Naziv senzora</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="nazivSenzora" name="nazivSenzora" placeholder="Naziv senzora">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="opisSenzora" class="col-sm-2 col-form-label">Opis senzora</label>
                    <div class="col-sm-10">
                        <input type="input" class="form-control" id="opisSenzora" name="opisSenzora" placeholder="Opis senzora">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nabavnaCena" class="col-sm-2 col-form-label">Nabavna cena</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="nabavnaCena" name="nabavnaCena">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="prodajnaCena" class="col-sm-2 col-form-label">Prodajna cena</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="prodajnaCena" name="prodajnaCena">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaSenzoraGR" class="col-sm-2 col-form-label">Cena senzora u GR</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaSenzoraGR" name="cenaSenzoraGR">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaSenzoraVanGR" class="col-sm-2 col-form-label">Cena senzora van GR</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaSenzoraVanGR" name="cenaSenzoraVanGR">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaAppGR" class="col-sm-2 col-form-label">Cena aplikacije(licence) u GR</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaAppGR" name="cenaAppGR">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaAppVanGR" class="col-sm-2 col-form-label">Cena aplikacije(licence) van GR</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaAppVanGR" name="cenaAppVanGR">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaServisaAktivan" class="col-sm-2 col-form-label">Cena servisa za aktivne</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaServisaAktivan" name="cenaServisaAktivan">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaServisaNeaktivan" class="col-sm-2 col-form-label">Cena servisa za <strong>neaktivne</strong></label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaServisaNeaktivan" name="cenaServisaNeaktivan">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cenaTehnickePodrske" class="col-sm-2 col-form-label">Cena tehnicke podrske partnera</label>
                    <div class="col-sm-10">
                        <input type="number" min="0.00" step="0.01" class="form-control" id="cenaTehnickePodrske" name="cenaTehnickePodrske">
                    </div>
                </div>

                <!--  <div class="form-group row">
              <label for="kategorija" class="col-sm-2 col-form-label">Kategorija</label>
              <div class="col-sm-10">
              <input type="text" class="form-control" id="kategorija" name="kategorija">
              </div>
          </div> -->

                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" name="submit" value="true" class="btn btn-danger">Dodaj senzor</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
