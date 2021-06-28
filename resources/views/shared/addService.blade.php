@extends('layouts.layout')


@section('title') Lora - Add Service
@endsection

@section('css')
    <script type="text/javascript">
        var baseUrl = "{{ asset('/') }}";
        var sviSenzori =<?php echo(json_encode($sensors)); ?>;

        console.log(sviSenzori);
    </script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dodavanje usluge korisniku</h1>
</div>

<!-- Content Row Table -->
<div class="card shadow mb-12">
    <div class="card-body">

        <form action="#" method="POST">
            <div class="card shadow mb-3">
                <div class="card-header text-danger font-weight-bold">
                    Korisnik
                </div>
                <div class="card-body row">
                    <div class="form-group col-6 mb-0">
                        <input type="text" class="form-control col-12 mb-4 mt-4" placeholder="Unesite šifru korisnika" id="sifraKorisnika" name="sifraKorisnika">
                        <button type="button" onclick="pronadjiKupca()" class="btn btn-danger col-12">PRONAĐI</button>

                        <select id="direkcija" name="direkcija" class="form-control">
                            <option value="0" selected disabled>Izaberi direkciju</option>

                        </select>
                    </div>




                    <div class="form-group col-6 bg-light p-3 mb-0" style="border-radius: 10px">
                        <label for="userName" class="col-3 col-form-label float-left">Naziv:</label>
                        <div class="col-9 float-right">
                            <input type="text" readonly class="form-control" id="userName" name="userName">
                        </div>

                        <label for="inputPIB" class="col-3 col-form-label float-left">PIB:</label>
                        <div class="col-9 float-right">
                            <input type="text" readonly class="form-control" id="inputPIB" name="PIB">
                        </div>

                        <label for="inputMB" class="col-3 col-form-label float-left">MB:</label>
                        <div class="col-9 float-right">
                            <input type="text" readonly class="form-control" id="inputMB" name="MB">
                        </div>
                    </div>





                </div>
            </div>

            <div class="card shadow mb-3">
                <div class="card-header text-danger font-weight-bold">
                    Izbor senzora
                </div>
                <div class="card-body">
                    <div class="form-group poljeSenzora">
                        <div id="proba">
                            <div class="card shadow mb-3">
                                <div id="formRow1" class="row">
                                    <div class="col-4">
                                        <label for="tipSenzora1" class="col-form-label" style="font-size: 15px;">Tip senzora:</label>
                                        <select id="tipSenzora1" name="tipSenzora1" class="form-control" onchange="postaviCenuSenzora(1)">
                                            <option value="0" selected>Izaberi iz liste...</option>
                                            @foreach($sensors as $s)
                                                <option value="{{ $s->idSenzor }}" data-id="{{ $s->idSenzor }}">{{ $s->naziv }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label for="brojAktivnih1" class="col-form-label" style="font-size: 15px;">Broj aktivnih senzora:</label>
                                        <input type="number" min="1" value="0" class="form-control" id="brojAktivnih1" name="brojAktivnih1" onchange="izmenaLagera(1); izracunajCenu();">
                                    </div>
                                    <div class="col-4">
                                        <label for="brojNeaktivnih1" class="col-form-label" style="font-size: 15px;">Broj povremeno neaktivnih:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="brojNeaktivnih1" name="brojNeaktivnih1" onchange="izmenaLagera(1); izracunajCenu(); imaNekativnih(1);">
                                    </div>

                                    <div class="col-4">
                                        <label for="cenaSenzoraUGr1" class="col-form-label" style="font-size: 15px;">Cena senzora u GR:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="cenaSenzoraUGr1" name="cenaSenzoraUGr1" onchange="izracunajCenu()">
                                    </div>
                                    <div class="col-4">
                                        <label for="cenaLicenceUGr1" class="col-form-label" style="font-size: 15px;">Cena licence u GR:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="cenaLicenceUGr1" name="cenaLicenceUGr1" onchange="izracunajCenu()">
                                    </div>
                                    <div class="col-4">
                                        <label for="cenaServisaZaAktivne1" class="col-form-label" style="font-size: 15px;">Cena servisa za aktivne:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="cenaServisaZaAktivne1" name="cenaServisaZaAktivne1" onchange="izracunajCenu()">
                                    </div>

                                    <div class="col-4">
                                        <label for="cenaSenzoraVanGr1" class="col-form-label" style="font-size: 15px;">Cena senzora <strong>van</strong> GR:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="cenaSenzoraVanGr1" name="cenaSenzoraVanGr1" onchange="izracunajCenu()">
                                    </div>
                                    <div class="col-4">
                                        <label for="cenaLicenceVanGr1" class="col-form-label" style="font-size: 15px;">Cena licence <strong>van</strong> GR:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="cenaLicenceVanGr1" name="cenaLicenceVanGr1" onchange="izracunajCenu()">
                                    </div>
                                    <div class="col-4">
                                        <label for="cenaServisaZaNeaktivne1" class="col-form-label" style="font-size: 15px;">Cena servisa za neaktivne:</label>
                                        <input type="number" min="0" value="0" class="form-control" id="cenaServisaZaNeaktivne1" name="cenaServisaZaNeaktivne1" onchange="izracunajCenu()">
                                    </div>

                                    <div class="col-4">
                                        <label for="komadaNaLageru1" class="col-form-label" style="font-size: 15px;">Komada na lageru</label>
                                        <input disabled data-number="0" type="number" min="1" value="0" class="form-control" id="komadaNaLageru1" name="komadaNaLageru1">
                                    </div>
                                    <div class="col-4">
                                        <label for="nabavnaCena1" class="col-form-label" style="font-size: 15px;">Nabavna cena</label>
                                        <input readonly type="number" min="1" value="0" class="form-control" id="nabavnaCena1" name="nabavnaCena1">
                                    </div>
                                    <div class="col-4">
                                        <label for="cenaTehnickePodrske1" class="col-form-label" style="font-size: 15px;">Cena tehnicke podrske</label>
                                        <input type="number" min="1" value="0" class="form-control" id="cenaTehnickePodrske1" name="cenaTehnickePodrske1" onchange="izracunajCenu()">
                                    </div>

                                    <div class="col-3">
                                        <label for="brojNeaktivnihMeseci1" class="col-form-label" style="font-size: 15px;">Broj neaktivnih meseci</label>
                                        <input type="number" disabled value="1" min="1" max="12" class="form-control" id="brojNeaktivnihMeseci1" name="brojNeaktivnihMeseci1" onchange="izracunajCenu(); neaktivniMeseci(1);">
                                    </div>
                                    <div class="col-8">
                                        <label for="neaktivniMeseci1" class="col-form-label" style="font-size: 15px;">Neaktivni meseci</label>
                                        <select disabled class="js-example-basic-multiple form-control" name="neaktivniMeseci1[]" id="neaktivniMeseci1" multiple="multiple">
                                            <option value="1">Januar</option>
                                            <option value="2">Februar</option>
                                            <option value="3">Mart</option>
                                            <option value="4">April</option>
                                            <option value="5">Maj</option>
                                            <option value="6">Jun</option>
                                            <option value="7">Jul</option>
                                            <option value="8">Avgust</option>
                                            <option value="9">Septembar</option>
                                            <option value="10">Oktobar</option>
                                            <option value="11">Novembar</option>
                                            <option value="12">Decembar</option>
                                        </select>
                                        <!--<input type="text" class="js-example-basic-multiple" disabled class="form-control" id="neaktivniMeseci1" name="neaktivniMeseci1"> -->
                                    </div>
                                    <div class="col-1">
                                        <div onclick="removeFromForm(1)"><i style="padding-top: 49px; color: #e74a3b; cursor: pointer;" class="far fa-trash-alt fa-lg"></i></div>
                                    </div>
                                </div>
                            </div>
                            <!--<div id="formRow1"  class="row">
                                <div class="col-5">
                                    <label for="inputTipSenzora" class="col-form-label" style="font-size: 15px;">Tip senzora:</label>
                                    <select id="tipSenzora1" name="tipSenzora1" class="form-control" onchange="postaviCenuSenzora(1)">
                                        <option value="0" selected>Izaberi iz liste...</option>

                                    </select>
                                </div>
                                <div class="col-2">
                                    <label for="kolicinaSenzora1" class="col-form-label" style="font-size: 15px;">Aktivnih:</label>
                                    <input type="number" min="1" value="0" class="form-control" id="kolicinaSenzora1" name="kolicinaSenzora1" onchange="izracunajCenu()">
                                </div>
                                <div class="col-2">
                                    <label for="kolicinaSenzora2" class="col-form-label" style="font-size: 15px;">Neaktivnih:</label>
                                    <input type="number" min="1" value="0" class="form-control" id="kolicinaSenzora2" name="kolicinaSenzora2" onchange="izracunajCenu()">
                                </div>
                                <div class="col-2">
                                    <label for="cenaSenzora1" class="col-form-label" style="font-size: 15px;">Jedinična cena:</label>
                                    <input type="number" min="1" value="0" class="form-control" id="cenaSenzora1" name="cenaSenzora1" onchange="izracunajCenu()">
                                </div>
                                <div class="col-1">
                                    <div onclick="removeFromForm(1)"><i style="padding-top: 49px; color: #e74a3b; cursor: pointer;" class="far fa-trash-alt fa-lg"></i></div>
                                </div>
                            </div> -->
                        </div>
                        <input type="hidden" id="brojSenzora" name="brojSenzora" value="1" />
                        <div class="col" style="text-align: right;">
                            <div onclick="addToForm()" style=" color: #e74a3b; cursor: pointer;">Dodaj senzor: <i class="fas fa-lg fa-plus-circle"></i></div>
                        </div>

                    </div>



                </div>


            </div>

            <div class="card shadow mb-3">
                <div class="card-header text-danger font-weight-bold">
                    Detalji usluge
                </div>
                <div class="card-body">

                    <div class="form-group row">

                        <div class="form-group col-6 mb-0">
                            <label for="nazivUsluge" class="col-form-label">Naziv:</label>
                            <input type="text" class="form-control" id="nazivUsluge" name="nazivUsluge">

                            <label for="brojMeseciUgovora" class="col-form-label">Broj meseci trajanja ugovorne obaveze:</label>
                            <input type="number" min="12" value="12" step="12" class="form-control" id="brojMeseciUgovora" name="brojMeseciUgovora">


                            <label for="brojMeseciGr" class="col-form-label" style="font-size: 15px;">Broj meseci GR</label>
                            <input type="number" value="12" step="1" class="form-control" id="brojMeseciGr" name="brojMeseciGr">


                            <div class="form-group" style="    padding-top: 45px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="odobriProbniPeriod">
                                    <label class="form-check-label" for="odobriProbniPeriod">
                                        Odobri probni period
                                    </label>
                                </div>
                            </div>

                        </div>



                        <div class="form-group col-6 mb-0">
                            <label for="jednokratnaCena" class="col-form-label">Jednokratna cena:</label>
                            <input type="number" value="0" class="form-control" min="0" step="1" id="jednokratnaCena" name="jednokratnaCena" onchange="izracunajJednokratnuCenu();">

                            <div class="form-group" style="    padding-top: 45px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="opremaJednokratno" name="opremaJednokratno">
                                    <label class="form-check-label" for="opremaJednokratno">
                                        Jednokratno placanje opreme
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" style="    padding-top: 45px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="aplikacijaJednokratno" name="aplikacijaJednokratno">
                                    <label class="form-check-label" for="aplikacijaJednokratno">
                                        Jednokratno placanje aplikacije
                                    </label>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-3" id="probniPeriodCard">
                <div class="card-header text-danger font-weight-bold">
                    Probni period
                </div>
                <div class="card-body">
                    <div class="form-group row poljeSenzora">
                        <div class="col-2">
                            <label for="probniPeriod" class="col-form-label">Probni period:</label>
                            <select id="probniPeriod" name="probniPeriod" class="form-control" onchange="promenaProbnogPerioda()">
                                <option value="0" selected=""> NE </option>
                                <option value="1"> DA </option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="brojTrialMeseci" class="col-form-label">Meseci:</label>
                            <input type="number" min="0" step="1" max="6" value="0" class="form-control" id="brojTrialMeseci" name="brojTrialMeseci" onchange="prikaziProbniPeriod()" disabled>
                            <input type="hidden" name="trialMeseciHid" value="0">
                        </div>
                        <div class="col-2">
                            <label for="brojTrialDana" class="col-form-label">Broj dana:</label>
                            <input type="number" min="0" step="1" max="30" value="0" class="form-control" id="brojTrialDana" name="brojTrialDana" onchange="prikaziProbniPeriod()" disabled>
                            <input type="hidden" name="trialDaniHid" value="0">
                        </div>
                        <div class="col-6">
                            <label for="inputProbniPeriodDo" class="col-form-label" style=" color: #e74a3b;">Probni period ističe:</label>
                            <input type="text" name="istekProbnogPerioda" class="form-control" readonly id="inputProbniPeriodDo">
                        </div>
                    </div>



                </div>
            </div>

            <div class="card shadow mb-3">

                <div class="form-group row pt-3">
                    <div class="col-4">
                        <label for="jednokratnaCenaFinal" class="col-form-label" style="font-size: 15px;">Jednokratna cena:</label>
                        <input readonly type="number" min="1" value="0" class="form-control" id="jednokratnaCenaFinal" name="jednokratnaCenaFinal">
                    </div>
                    <div class="col-4">
                        <label for="mesecnaPretplataGRFinal" class="col-form-label" style="font-size: 15px;">Pretplata u GR:</label>
                        <input disabled type="text" class="form-control" id="mesecnaPretplataGRFinal" name="mesecnaPretplataGRFinal">
                    </div>
                    <div class="col-4">
                        <label for="mesecnaPretplataVanFinal" class="col-form-label" style="font-size: 15px;">Pretplata van GR:</label>
                        <input disabled type="text" class="form-control" id="mesecnaPretplataVanFinal" name="mesecnaPretplataVanFinal">
                    </div>
                </div>
                <div class="form-group row pt-3">
                    <div class="col">
                        <button type="submit" value="true" name="inputSubmit" class="btn btn-danger float-right">Dodaj uslugu korisniku</button>
                    </div>
                </div>
        </form>
    </div>


    @endsection
