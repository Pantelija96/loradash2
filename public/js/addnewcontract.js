// noinspection JSJQueryEfficiency

var brojRedova = 1;
var aktivneStavke = [1];
var proveraIp = false;
var posetaDrugojStrani = false;

const firstStepIDs = [
    'id_kupac',
    'connectivity_plan',
    'naziv_kupac',
    'pib',
    'mb',
    'telefon',
    'kam',
    'segment',
    'partner',
    'naziv_ugovora',
    'tip_servisa',
    'naziv_servisa',
    'tip_ugovora',
    'broj_ugovora',
    'datum',
    'zbirni_racun',
    'uo',
    'tip_tehnologije',
    'vrsta_senzora',
    'lokacija_app'
];
var stavkeFakture = [];
var pickDateOptions = {
    selectYears: true,
    selectMonths: true,
    selectDay: false,
    monthsFull: ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Jun', 'Jul', 'Avgust', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'],
    weekdaysShort: ['Ned', 'Pon', 'Uto', 'Sre', 'Čet', 'Pet', 'Sub'],
    today: 'Danas',
    clear: 'Poništi',
    format: 'mmm dd, yyyy',
    formatSubmit: 'yyyy-mm-dd',
    hiddenSuffix: '_data',
    editable: true
};
function lokacijaApp(){
    var lokacija = $("#lokacija_app").val();
    if(parseInt(lokacija) === 1){
        $('#nazivWrap').css("display", "block");
        $('#ipAddresaWrap').css("display", "block");
        proveraIp = true;
    }
    else{
        $('#nazivWrap').css("display", "none");
        $('#ipAddresaWrap').css("display", "none");
        proveraIp = false;
    }
}
function firstStepValidation(){
    var errors = [];

    for(var i = 0; i < firstStepIDs.length; i++){
        var element = $("#"+firstStepIDs[i]);
        var element_error = $("#"+firstStepIDs[i]+'_error');

        element_error.attr('style','display: none;');

        if(element.val() === "" || element.val() === null){
            errors.push(firstStepIDs[i]+'_error');
            element_error.attr('style','');
        }
    }

    if(proveraIp) {
        $("#naziv_servera_error").attr('style','display: none;');
        $("#ip_adresa_error").attr('style','display: none;');
        if($("#naziv_servera").val() === ""){
            errors.push('naziv_servera_error');
            $("#naziv_servera_error").attr('style','');
        }

        var ip = $("#ip_adresa").val();
        var ipRegex = /^(?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$/;
        console.log('regex result ', ipRegex.test(ip));
        if (!ipRegex.test(ip)) {
            //nije prosao regularni izraz
            errors.push('ip_adresa_error');
            $("#ip_adresa_error").attr('style','');
        }
    }

    //console.log('errors: ', errors);
    return (errors.length === 0);
}
function secondStepVerification(){
    var rows_with_errors = [];

    if(aktivneStavke.length === 0){
        //obirsane su sve stavke fakture -> strana je prazna -> pravi se ugovor bez komercijalnih uslova
        new PNotify({
            title: 'Uspešno!',
            text: 'Uspešno popunjena forma!',
            addclass: 'bg-success'
        });
        return true;
    }

    if(aktivneStavke.length === 1){
        //proveriti da li je postavljena stavka fakture, ako nije pravi se ugovor bez komercijalnih uslova
        var stavka = $("#stavka_fakture_" + (aktivneStavke[0])).val();
        if(stavka === ""){
            new PNotify({
                title: 'Uspešno!',
                text: 'Uspešno popunjena forma!',
                addclass: 'bg-success'
            });
            return true;
        }
    }

    for(var i = 0; i < aktivneStavke.length; i++){
        $("#row_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#stavka_fakture_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#datum_pocetak_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#datum_kraj_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#naknada_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#status_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');

        var id_stavka = $("#stavka_fakture_"+(aktivneStavke[i])).val();
        var datum_pocetak = $("[name=datum_pocetak_"+aktivneStavke[i]+"_data]").val();
        var datum_kraj = $("[name=datum_kraj_"+aktivneStavke[i]+"_data]").val();
        var naknada = $("#naknada_"+(aktivneStavke[i])).val();
        var status = $("#status_"+(aktivneStavke[i])).val();

        console.log(naknada);

        var row_errors = [];

        if(id_stavka === "") row_errors.push('stavka_fakture_'+aktivneStavke[i]+ "_error");
        if(naknada === "" || parseFloat(naknada) === 0) row_errors.push('naknada_'+aktivneStavke[i]+ "_error");
        if(status === "") row_errors.push('status_'+aktivneStavke[i]+ "_error");

        if(datum_pocetak === ""){
            row_errors.push('datum_pocetak_'+aktivneStavke[i]+ "_error");
        }
        else{
            let datum = new Date(datum_pocetak);
            if(datum.getDate() !== 1){
                row_errors.push('datum_pocetak_'+aktivneStavke[i]+ "_error");
            }
        }

        if(datum_kraj === ""){
            row_errors.push('datum_kraj_'+aktivneStavke[i]+ "_error");
        }
        else{
            let datum = new Date(datum_kraj);
            var last_day = new Date(datum.getFullYear(), datum.getMonth() + 1, 0).getDate();

            if(datum.getDate() !== last_day){
                row_errors.push('datum_kraj_'+aktivneStavke[i]+ "_error");
            }
        }

        if(row_errors.length !== 0){
            $("#row_" + (aktivneStavke[i]) + "_error").attr('style','');
            for(var j = 0; j < row_errors.length; j++){
                $("#"+row_errors[j]).attr('style','');
            }
            rows_with_errors.push(aktivneStavke[i]);
        }
    }

    if(rows_with_errors.length === 0){
        new PNotify({
            title: 'Uspešno!',
            text: 'Uspešno popunjena forma!',
            addclass: 'bg-success'
        });
        return true;
    }
    else{
        return false;
    }
}
function dohvatiKorisnika(){
    var idKorisnika = $("#id_kupac").val();
    if(idKorisnika === ""){
        new PNotify({
            title: 'Greška!',
            text: 'Morate uneti Id korisnika!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
        $("#nazivFirme").val("").prop("disabled", true);
        $("#pib").val("").prop("disabled", true);
        $("#mbr").val("").prop("disabled", true);
        $("#email").val("").prop("disabled", true);
        $("#telefon").val("").prop("disabled", true);
        $("#kam").val("").prop("disabled", true);
        $("#segment").val("").prop("disabled", true);
    }
    else{
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/finduser/'+idKorisnika,
            success: function (data) {
                //console.log(data);
                //dobija se no account found kad se posalje pib koji su oni probali! -> 13034891

                if(data.response === false){
                    //nije nadjen korisnik ni u lokalu ni na mrezi
                    new PNotify({
                        title: 'Greška!',
                        text: 'Nije pronadjen korisnik sa zadatim id-om!',
                        addclass: 'bg-telekom-slova',
                        hide: false,
                        buttons: {
                            sticker: false
                        }
                    });
                    $("#nazivFirme").val("").prop("disabled", true);
                    $("#pib").val("").prop("disabled", true);
                    $("#mbr").val("").prop("disabled", true);
                    $("#email").val("").prop("disabled", true);
                    $("#telefon").val("").prop("disabled", true);
                    $("#kam").val("").prop("disabled", true);
                    $("#segment").val("").prop("disabled", true);
                }
                else{
                    //korisnik pronadjen na mrezi

                    var greska = data.response.split('<out:BusinessName xmlns:out="http://www.siebel.com/xml/TS1%20Account%20IOInternal">')[1];


                    if (typeof greska === 'undefined'){
                        //postoji greska
                        var greskaPre = data.response.split('<io:FaultMessageText>')[1];
                        var greskaText = greskaPre.split('</io:FaultMessageText>')[0];
                        new PNotify({
                            title: 'Greška!',
                            text: 'Desila se greska : '+greskaText+' !',
                            addclass: 'bg-telekom-slova',
                            hide: false,
                            buttons: {
                                sticker: false
                            }
                        });
                    }
                    else{
                        //console.log(data.response);

                        $("#lokalniKorisnik").val('0');
                        var dataRegex = />(.*?)</;
                        var readError = false;

                        try {
                            var nazivReg = /<out:BusinessName xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:BusinessName>/g;
                            var naziv0 = data.response.match(nazivReg);
                            var naziv = dataRegex.exec(naziv0)[1];

                            console.log("Naziv = " + naziv);

                            var pibReg = /<out:AccountPIB xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:AccountPIB>/g;
                            var pib0 = data.response.match(pibReg);
                            var pib = dataRegex.exec(pib0)[1];

                            console.log("PIB = " + pib);

                            var mbReg = /<out:AccountMB xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:AccountMB>/g;
                            var mb0 = data.response.match(mbReg);
                            var mb = dataRegex.exec(mb0)[1];

                            console.log("mb = " + mb);

                            var segmentReg = /<out:AccountSegment xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:AccountSegment>/g;
                            var segment0 = data.response.match(segmentReg);
                            var segment = dataRegex.exec(segment0)[1];

                            console.log("segment = " + segment);

                            try {
                                var emailReg = /<out:MainEmailAddress xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:MainEmailAddress>/g;
                                var email0 = data.response.match(emailReg);
                                var email = dataRegex.exec(email0)[1];
                            }
                            catch(e){
                                var email = "";
                            }
                            console.log("email = " + email);

                            var telefonlReg = /<out:AccountMainPhoneNumber xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:AccountMainPhoneNumber>/g;
                            var telefon0 = data.response.match(telefonlReg);
                            var telefon = dataRegex.exec(telefon0)[1];

                            console.log("telefon = " + telefon);

                            var kamReg = /<out:AccountTeam xmlns:out="http:\/\/www.siebel.com\/xml\/TS1%20Account%20IOInternal">(.*?)<\/out:AccountTeam>/g;
                            var kam0 = data.response.match(kamReg);
                            var kam = dataRegex.exec(kam0)[1];

                            console.log("email = " + kam);
                        }
                        catch (err){
                            console.log(err);
                            readError = true;
                        }

                        if(readError || naziv==="") {
                            new PNotify({
                                title: 'Greška!',
                                text: 'Desila se greska : neispravan ID korisnika !',
                                addclass: 'bg-telekom-slova',
                                hide: false,
                                buttons: {
                                    sticker: false
                                }
                            });

                            $("#nazivFirme").val(naziv).prop("disabled", true);
                            $("#pib").val(pib).prop("disabled", true);
                            $("#mbr").val(mb).prop("disabled", true);
                            $("#telefon").val(telefon).prop("disabled", true);
                            $("#kam").val(kam).prop("disabled", true);
                            $("#segment").val(segment).prop("disabled", true);
                            $("#email").val(email).prop("disabled", true);

                        }
                        else{
                            $("#nazivFirme").val(naziv).prop("readonly", true);
                            $("#pib").val(pib).prop("readonly", true);
                            $("#mbr").val(mb).prop("readonly", true);
                            $("#telefon").val(telefon).prop("readonly", true);
                            $("#kam").val(kam).prop("readonly", true);
                            $("#segment").val(segment).prop("readonly", true);
                            $("#email").val(email).prop("readonly", true);

                            //dohvatanje zbirnih racuna
                            var nizSvihInvoProfila = [];
                            var zbirniRacuni = [];
                            var sviProfiliReg = /<out:ComInvoiceProfile (.*?)<\/out:ComInvoiceProfile>/g;

                            nizSvihInvoProfila = data.response.match(sviProfiliReg);
                            //console.log(nizSvihInvoProfila);

                            for(var i = 0; i < nizSvihInvoProfila.length; i++){
                                //provera da li je mob
                                var mobRegex = /<out:BPProfileType>(.*?)<\/out:BPProfileType>/g;
                                var profileTip = nizSvihInvoProfila[i].match(mobRegex)[0];

                                if ( (profileTip.indexOf("Mobile") !== -1) || (profileTip.indexOf("mobile") !== -1) || (profileTip.indexOf("Mobilni") !== -1) || (profileTip.indexOf("mobilni") !== -1) ){
                                    var zibrniReg = /<out:BPCode>\w+.\w+<\/out:BPCode>/g;
                                    zbirniRacuni.push(nizSvihInvoProfila[i].match(zibrniReg));
                                }
                            }


                            var select = document.getElementById('zbirniRacun');

                            for (var i = 0; i < zbirniRacuni.length; i++) {
                                var option = document.createElement('option');
                                option.value = zbirniRacuni[i];
                                option.innerHTML = zbirniRacuni[i];
                                select.appendChild(option);
                            }

                            $('#zbirniRacun').select2({
                                minimumResultsForSearch: Infinity
                            });

                            new PNotify({
                                title: 'Uspešno!',
                                text: 'Pronađen korisnik!',
                                addclass: 'bg-success'
                            });
                        }
                    }
                }

            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                new PNotify({
                    title: 'Greška!',
                    text: 'Serverska greska!',
                    addclass: 'bg-telekom-slova',
                    hide: false,
                    buttons: {
                        sticker: false
                    }
                });
                $("#nazivFirme").val("").prop("disabled", true);
                $("#pib").val("").prop("disabled", true);
                $("#mbr").val("").prop("disabled", true);
                $("#email").val("").prop("disabled", true);
                $("#telefon").val("").prop("disabled", true);
                $("#kam").val("").prop("disabled", true);
                $("#segment").val("").prop("disabled", true);
            }
        });
    }
}
function createConnPlan(){
    var text = "iot-cs/";
    var selectedText = $('#zbirni_racun').select2('data')[0].text.replaceAll('.','_');
    text += selectedText;
    $("#connectivity_plan").val(text);
}
function addRow(){
    brojRedova++;
    var new_row = `
        <div class="row" id="row_`+brojRedova+`">
            <div class="col-md-2 form-group">
                <select name="stavka_fakture_`+brojRedova+`" id="stavka_fakture_`+brojRedova+`" data-placeholder="Stavka fakture" class="select new_row_select" onchange="stavkaChanged(`+brojRedova+`)">
                    <option></option>
                </select>
                <label id="stavka_fakture_`+brojRedova+`_error" for="stavka_fakture_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <input type="text" name="datum_pocetak_`+brojRedova+`" id="datum_pocetak_`+brojRedova+`" class="form-control pickadate-selectors" placeholder="Datum početak">
                <label id="datum_pocetak_`+brojRedova+`_error" for="datum_pocetak_`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti prvi dan u mesecu!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <input type="text" name="datum_kraj_`+brojRedova+`" id="datum_kraj_`+brojRedova+`" class="form-control pickadate-selectors" placeholder="Datum kraj">
                <label id="datum_kraj_`+brojRedova+`_error" for="datum_kraj_`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti poslednji dan u mesecu!</label>
            </div>

            <div class="col-md-2 form-group" style="margin-left: 5px;">
                <input type="number" step=".01" min="0" name="naknada_`+brojRedova+`" id="naknada_`+brojRedova+`" class="form-control" min="0" value="0">
                <label id="naknada_`+brojRedova+`_error" for="naknada_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                    <select name="status_`+brojRedova+`" id="status_`+brojRedova+`" data-placeholder="Status" class="select">
                        <option></option>
                        <option value="1">Aktivni</option>
                        <option value="2">Prijavljeni</option>
                        <option value="3">N/A</option>
                    </select>
                    <label id="status_`+brojRedova+`_error" for="status_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <input type="number" step="0.1" name="min_`+brojRedova+`" id="min_`+brojRedova+`" class="form-control" value="0" step="1">
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <input type="number" step="0.1" name="max_`+brojRedova+`" id="max_`+brojRedova+`" class="form-control" value="0" step="1">
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <div class="checkbox" style="margin-left: 40%;">
                    <input type="checkbox" class="control-primary" name="sim_`+brojRedova+`" id="sim_`+brojRedova+`" value="0">
                </div>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <div class="checkbox" style="margin-left: 40%;">
                    <input type="checkbox" class="control-primary" name="uredjaj_`+brojRedova+`" id="uredjaj_`+brojRedova+`" value="0">
                </div>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <ul class="icons-list text-center form-control" style="border: none;" id="link_brisanje_1">
                    <li class="text-danger-800" style="padding-top: 6px;"><a href="#"  onclick="removeRow(`+brojRedova+`)" data-popup="tooltip" title="Obriši red: `+brojRedova+`"><i style="font-size: 20px;" class="icon-trash"></i></a></li>
                </ul>
            </div>
        </div>

        <div class="row" id="row_`+brojRedova+`_error" style="display: none;">
            <div class="col-md-2 form-group">
                <label id="stavka_fakture_`+brojRedova+`_error" for="stavka_fakture_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                <label id="datum_pocetak_`+brojRedova+`_error" for="datum_pocetak_`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti prvi dan u mesecu!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                <label id="datum_kraj_`+brojRedova+`_error" for="datum_kraj_`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti prvi dan u mesecu!</label>
            </div>

            <div class="col-md-2 form-group" style="margin-left: 5px; ">
                <label id="naknada_`+brojRedova+`_error" for="naknada_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                <label id="status_`+brojRedova+`_error" for="status_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>
        </div>
    `;

    $("#komercijalni_uslovi").append(new_row);

    $("#stavka_fakture_"+brojRedova).select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Stavka fakture",
        data: stavkeFakture
    });
    $("#status_"+brojRedova).select2({
        minimumResultsForSearch: Infinity,
    });

    $("#datum_pocetak_"+brojRedova).pickadate(pickDateOptions);
    $("#datum_kraj_"+brojRedova).pickadate(pickDateOptions);

    aktivneStavke.push(brojRedova);
    $("#aktivne_stavke").val(aktivneStavke);

    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });
}
function removeRow(row_id){
    $("#row_"+row_id).remove();
    $("#row_"+row_id+"_error").remove();
    var index = aktivneStavke.indexOf(row_id);
    aktivneStavke.splice(index, 1);
    $("#aktivne_stavke").val(aktivneStavke);
}
function stavkaChanged(row_id){
    //dohvatanje naknade za stavku fakture
    var selected = $("#stavka_fakture_"+row_id).val();
    var id_senzor = selected.split('|')[0];
    var id_stavka_fakture = selected.split('|')[1];
    $.ajax({
        type: "GET",
        url: baseUrl+'ajax/naknada/'+id_stavka_fakture,
        success: function(data) {
            $("#naknada_"+row_id).val(data.naknada);
        },
        error: function (xhr, status, error){
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
}
function getSoapUser(){
    var id_korisnik = $("#id_kupac").val();
    if(id_korisnik === ""){
        new PNotify({
            title: 'Greška!',
            text: 'Morate uneti Id korisnika!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
        $("#naziv_kupac").val("").prop("disabled", true);
        $("#mbr").val("").prop("disabled", true);
        $("#pib").val("").prop("disabled", true);
        $("#telefon").val("").prop("disabled", true);
        $("#email").val("").prop("disabled", true);
        $("#segment").val("").prop("disabled", true);
        $("#kam").val("").prop("disabled", true);
    }
    else{
        $.ajax({
            type: "GET",
            url: baseUrl+'ajax/getuser/'+id_korisnik,
            success: function(data) {
                console.log(data);
                $("#naziv_kupac").val(data.name).prop("readonly", true);
                $("#mb").val(data.mbr).prop("readonly", true);
                $("#pib").val(data.pib).prop("readonly", true);
                $("#telefon").val(data.telefon).prop("readonly", true);
                $("#email").val(data.email).prop("readonly", true);
                $("#segment").val(data.segm).prop("readonly", true);
                $("#kam").val(data.kam).prop("readonly", true);

                $("#zbirni_racun").select2({
                    minimumResultsForSearch: Infinity,
                    data: data.racuni
                });
            },
            error: function (xhr, status, error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
}
$(document).ready(function() {
    //Forma podesavanja
    $.fn.stepy.defaults.legend = false;
    $.fn.stepy.defaults.transition = 'fade';
    $.fn.stepy.defaults.duration = 250;
    $.fn.stepy.defaults.backLabel = '<i class="icon-arrow-left13 position-left"></i> Nazad';
    $.fn.stepy.defaults.nextLabel = 'Dalje <i class="icon-arrow-right14 position-right"></i>';
    $(".stepy-callbacks").stepy({
        transition: 'slide',
        next: function(index) {
            var validation = firstStepValidation();
            posetaDrugojStrani = !validation;
            $('#vrsta_senzora').prop('readonly',true);
            return validation;
        },
        finish: function() {
            //provara inputa za komercijalne uslove
            return secondStepVerification();
        },
        titleClick: true
    });
    $('.stepy-step').find('.button-next').addClass('btn bg-telekom-slova');
    $('.stepy-step').find('.button-back').addClass('btn bg-telekom-slova');

    //Select podesavanja
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });
    $(".multiple-custom").on('select2:opening select2:closing', function( event ) {
        var $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });
    $('#vrsta_senzora').on('change', function (e) {
        var selected = $(this).select2('data');
        var select_data = [];
        for (var i = 0; i < stavkeIzBaze.length; i++) {
            if (stavkeIzBaze[i].zavisi_od_vrste_senzora === 0) {
                select_data.push({
                    id: "0|" + stavkeIzBaze[i].id,
                    text: stavkeIzBaze[i].naziv
                })
            }
        }
        for (var j = 0; j < selected.length; j++) {
            //debugger;
            var obj = {
                text: selected[j].text,
                children: []
            };
            for (var z = 0; z < stavkeIzBaze.length; z++) {
                if (stavkeIzBaze[z].zavisi_od_vrste_senzora === 1) {
                    obj.children.push({
                        id: selected[j].id + "|" + stavkeIzBaze[z].id,
                        text: stavkeIzBaze[z].naziv + selected[j].text
                    });
                }
            }
            select_data.push(obj);
        }
        stavkeFakture = select_data;
        if(!posetaDrugojStrani){
            var first_select = $("#stavka_fakture_1");
            first_select.html('<option></option>');
            first_select.select2({
                minimumResultsForSearch: Infinity,
                placeholder: "Stavka fakture",
                data: select_data
            });
        }
    });


    //Date time picker podesavanja
    $('.pickadate-selectors').pickadate(pickDateOptions);

    //Podesavanje pritiska na enter
    document.getElementById("id_kupac").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            getSoapUser();
        }
    });

    //Checkbox
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    //inicijalno podesavanje aktivnih stavki
    $("#aktivne_stavke").val(aktivneStavke);
})

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*function promenaLokacije(){
    var lokacija = $("#lokacijaAplikacije").val();
    if(parseInt(lokacija) === 2){
        $('#nazivWrap').css("display", "block");
        $('#ipAddresaWrap').css("display", "block");
        proveraIp = true;
    }
    else{
        $('#nazivWrap').css("display", "none");
        $('#ipAddresaWrap').css("display", "none");
        proveraIp = false;
    }
}

function izabraniSenzori(){
    stavke = [];
    var senzoriID = $("#tipSenzora").val();
    var senzoriName = $("#tipSenzora option:selected").toArray().map(item => item.text);
    dohvatiStavkeFakutre(0,"",0);

    for(var i = 0; i < senzoriID.length; i++){
        dohvatiStavkeFakutre(1,senzoriName[i],senzoriID[i]);
    }
}

function dohvatiStavkeFakutre(flag, sufiks, idSenzor){
    //dohvatanje stavki fakture
    $.ajax({
        type: "GET",
        url: baseUrl+'ajax/getstavke/'+flag,
        success: function(data) {
            //console.log(data);
            //stavke = []; treba prvo postaviti niz ids senzora!
            for(var i = 0; i < data.stavke.length; i++){
                var obj = {
                    naziv: data.stavke[i].naziv+' '+sufiks,
                    idStavkaFakture: data.stavke[i].idStavkaFakture,
                    naknada: data.stavke[i].naknada,
                    tipNaknade: data.stavke[i].tipNaknade,
                    idVrstaSenzora: idSenzor
                }

                stavke.push(obj);
            }
        },
        error: function (xhr, status, error){
            console.log(xhr);
            console.log(status);
            console.log(error);
        }
    });
}

function prikaziStavkeFakture(){
    //prikazati tek kad se ode na drugi korak! stavkaFakture1

    var select = document.getElementById('stavkaFakture1');

    for (var i = 0; i < stavke.length; i++) {
        var option = document.createElement('option');
        option.value = stavke[i].idStavkaFakture+"|"+stavke[i].idVrstaSenzora;
        option.innerHTML = stavke[i].naziv;
        select.appendChild(option);
    }

    $('#zbirniRacun').select2({
        minimumResultsForSearch: Infinity
    });

}

function dodajNoviRed(){
    brojRedova ++;
    brojAktivnihRedova ++;

    var stavkeFakture = `<select name="stavkaFakture`+brojRedova+`" id="stavkaFakture`+brojRedova+`" onchange="izbranaStavkaFakture(`+brojRedova+`)" data-placeholder="Stavka fakture" class="select"> <option></option>`;
    for(var i=0; i<stavke.length; i++){
        stavkeFakture+= `<option value="`+stavke[i].idStavkaFakture+`|`+stavke[i].idVrstaSenzora+`">`+stavke[i].naziv+`</option>`;
    }
    stavkeFakture += `</select>
        <label id="stavkaFakture`+brojRedova+`Error" for="stavkaFakture`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
        <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#stavkaFaktureDiv").append(stavkeFakture);

    var pocetakDatum = `<input type="text" name="pocetakDatum`+brojRedova+`" id="pocetakDatum`+brojRedova+`" class="form-control pickdate-novi" placeholder="Datum početak">
                        <label id="pocetakDatum`+brojRedova+`Error" for="pocetakDatum`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti prvi dan u mesecu!</label>
                        <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#pocetakDiv").append(pocetakDatum);

    var krajDatum = `<input type="text" name="krajDatum`+brojRedova+`" id="krajDatum`+brojRedova+`" class="form-control pickdate-novi" placeholder="Datum kraj">
                     <label id="krajDatum`+brojRedova+`Error" for="krajDatum`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti poslednji dan u mesecu!</label>
                     <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#krajDiv").append(krajDatum);

    var naknada = `<input type="number" name="naknada`+brojRedova+`" id="naknada`+brojRedova+`" class="form-control" min="0" value="0" step="0.01">
                    <label id="naknada`+brojRedova+`Error" for="naknada`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                     <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#naknadaDiv").append(naknada);

    var status = `<select name="status`+brojRedova+`" id="status`+brojRedova+`" data-placeholder="Status `+brojRedova+`" class="select">
                                <option></option>
                                <option value="1">Aktivni</option>
                                <option value="2">Prijavljeni</option>
                                <option value="3">N/A</option>
                            </select>
                            <label id="status`+brojRedova+`Error" for="naknada`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
                            <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#statusDiv").append(status);

    var min = `<input type="number" name="min`+brojRedova+`" id="min`+brojRedova+`" class="form-control" value="0" step="1">
                     <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#minDiv").append(min);

    var max = `<input type="number" name="max`+brojRedova+`" id="max`+brojRedova+`" class="form-control" value="0" step="1">
                     <span class="divider`+brojRedova+`" style="width: 100%; min-height: 3px; display: inline-block;"></span>`;
    $("#maxDiv").append(max);

    var brisanje = `<ul class="icons-list text-center form-control" style="border: none;" id="linkBrisanje`+brojRedova+`">
                        <li class="text-danger-800" style="padding-top: 6px;"><a href="#"  onclick="obrisiRed(`+brojRedova+`)" data-popup="tooltip" title="Obriši red"><i style="font-size: 20px;" class="icon-trash"></i></a></li>
                    </ul><span class="divider`+brojRedova+`" style="width: 100%; min-height: 5px; display: inline-block;"></span>`;
    $("#akcijeDiv").append(brisanje);

    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

    $('.pickdate-novi').pickadate({
        selectYears: true,
        selectMonths: true,
        monthsFull: ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Jun', 'Jul', 'Avgust', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'],
        weekdaysShort: ['Ned', 'Pon', 'Uto', 'Sre', 'Čet', 'Pet', 'Sub'],
        today: 'Danas',
        clear: 'Poništi',
        formatSubmit: 'yyyy/mm/dd 12:00:00'
    });

    $("#aktivneStavke").val(aktivneStavke);
    aktivneStavke.push(brojRedova);
    console.log("dodavanje");
    console.log("broj redova = "+brojRedova);
    console.log("brojAktivnihRedova = "+brojAktivnihRedova);
    console.log(aktivneStavke);
}

function obrisiRed(idRed){
    brojAktivnihRedova--;
    //ukljanja se po id-evima : stavkaFakture1, pocetakDatum1, krajDatum1, naknada1, status1, min1, max1, linkBrisanje1

    $("#stavkaFakture"+idRed).select2('destroy');
    document.getElementById("stavkaFakture"+idRed).remove();
    document.getElementById('stavkaFakture'+idRed+'Error').remove();

    document.getElementById('pocetakDatum'+idRed).remove();
    document.getElementById('pocetakDatum'+idRed+'Error').remove();

    document.getElementById('krajDatum'+idRed).remove();

    document.getElementById('naknada'+idRed).remove();
    document.getElementById('naknada'+idRed+'Error').remove();

    $("#status"+idRed).select2('destroy');
    document.getElementById("status"+idRed).remove();
    document.getElementById('status'+idRed+'Error').remove();

    document.getElementById('min'+idRed).remove();
    document.getElementById('max'+idRed).remove();
    document.getElementById('linkBrisanje'+idRed).remove();

    $(".divider"+idRed).remove();

    var index = aktivneStavke.indexOf(idRed);
    aktivneStavke.splice(index, 1);
    $("#aktivneStavke").val(aktivneStavke);

    console.log("brisanje");
    console.log("broj redova = "+brojRedova);
    console.log("brojAktivnihRedova = "+brojAktivnihRedova);
    console.log(aktivneStavke);
}

function izbranaStavkaFakture(idReda){
    var idStavke = $("#stavkaFakture"+idReda+" option:selected").val().split('|')[0];
    for(var i=0; i<stavke.length; i++){
        if(parseInt(idStavke) === stavke[i].idStavkaFakture){
            $("#naknada"+idReda).val(stavke[i].naknada);
        }
    }
}*/

/*if(posetaDrugojStrani){
            var selected2 = $(this).select2('data');
            var stavke_fakture_text = [];
            for (var i = 0; i < stavkeFakture.length; i++){
                stavke_fakture_text.push(stavkeFakture[i].text)
            }

            for(var j = 0; j < selected2.length; j++){
                if(!stavke_fakture_text.includes(selected2[j].text)){
                    console.log('text koji fali', selected2[j].text);
                }
            }








             /*if (stavkeFakture[i].text !== selected2[j].text) {
            var obj2 = {
                text: selected2[j].text,
                children: []
            };

            let opt_grp = document.createElement('optgroup');
            opt_grp.label = selected2[j].text;
            for (var z = 0; z < stavkeIzBaze.length; z++) {
                if (stavkeIzBaze[z].zavisi_od_vrste_senzora === 1) {
                    var option = document.createElement('option');
                    option.value = selected2[j].id + "|" + stavkeIzBaze[z].id;
                    option.innerHTML = stavkeIzBaze[z].naziv + selected2[j].text;
                    opt_grp.appendChild(option);
                    obj2.children.push({
                        id: selected2[j].id + "|" + stavkeIzBaze[z].id,
                        text: stavkeIzBaze[z].naziv + selected2[j].text
                    });
                }
            }
            stavkeFakture.push(obj2);
            $(".stavka_select").append(opt_grp);

            console.log('stavke fakture', stavkeFakture);
            console.log('stavke texts', stavke_fakture_text);
            console.log('selected data 2', selected2);
        }*/
