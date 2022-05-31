@extends('admin.systemmanaging')

@section('navigation')
    <li class="active"><a href="#">Stavka fakture</a></li>
    <li><a href="#">Tip ugovora</a></li>
    <li><a href="#">Tip servisa</a></li>
    <li><a href="#">Tip tehnologije</a></li>
    <li><a href="#">Partner</a></li>
    <li><a href="#">Servis</a></li>
    <li><a href="#">Senzor</a></li>
    <li><a href="#">Lokacija aplikacije</a></li>
@endsection

@section('form')
    <div class="tab-pane animated zoomIn has-padding " id="stavkeFakture">

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
                <p>test</p>
                </tbody>
            </table>
        </div>

        <form class="form-horizontal" method="POST" id="formStavkaFakture">
            {{ csrf_field() }}
            <div class="panel panel-flat" style="border: none;">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <h5 class="panel-title">Dodaj novu stavku fakture</h5>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="nazivStavkeFakture">Naziv:</label>
                                <div class="col-lg-9">
                                    <input type="text" name="nazivStavkeFakture" id="nazivStavkeFakture" class="form-control" placeholder="Naziv stavke fakture">
                                    <label id="nazivStavkeFaktureError" for="naziv" class="validation-error-label" style="display: none;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="mesecnaNaknada">Naknada:</label>
                                <div class="col-lg-9">
                                    <input type="number" name="mesecnaNaknada" id="mesecnaNaknada" class="form-control" step="0.1">
                                    <label id="mesecnaNaknadaError" for="mesecnaNaknada" class="validation-error-label" style="display: none;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="tipNaknade">Tip naknade:</label>
                                <div class="col-lg-9">
                                    <select name="tipNaknade" id="tipNaknade" data-placeholder="Tip naknade" class="select">
                                        <option></option>
                                        <option value="1" >Mesečna</option>
                                        <option value="2" >Jednokratna</option>
                                    </select>
                                    <label id="tipNaknadeError" for="tipNaknade" class="validation-error-label" style="display: none;"></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label" for="tipNaknade">Zavisi od vrste senzora:</label>
                                <div class="col-lg-9">
                                    <input type="checkbox" name="zavisnost" id="zavisnost" />
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="button" class="btn bg-telekom-slova" onclick="proveriStavkuFakture()"> <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
