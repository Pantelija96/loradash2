var pickDateOptions = {
    selectYears: true,
    selectMonths: true,
    selectDay: false,
    monthsFull: ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Jun', 'Jul', 'Avgust', 'Septembar', 'Oktobar', 'Novembar', 'Decembar'],
    weekdaysShort: ['Ned', 'Pon', 'Uto', 'Sre', 'Čet', 'Pet', 'Sub'],
    today: 'Danas',
    clear: 'Poništi',
    format: 'yyyy-mm-dd',
    formatSubmit: 'yyyy-mm-dd',
    hiddenSuffix: '_data',
    editable: true
};
var stavkeFakture = [];
var menjanDatum = false;
var submitted = false;
var nijeSeDesilaIzmenaNaloga = true;
var nijeSeDesilaIzmenaPropustanja = true;

var brojRedovaPojedinacniNalog = 1;
var aktivniRedoviPojedinacniNalog = [];
var aktivniRedoviPojedinacniNalogNaStartu = [];
var postojeciNalozi = [];
var postojeciNaloziPocetak = [];

var brojRedovaPropustanje = 1;
var aktivniRedoviPropustanje = [];
var aktivniRedoviPropustanjeNaStartu = [];
var postojecaPropustanja = [];
var postojecaPropustanjaPocetak = [];

function postaviStavkeFakture(){
    var izabraniSenzori = $("#tip_senzora").select2('data');
    var select_data = [];
    for (let i = 0; i < stavkeIzBaze.length; i++) {
        if (stavkeIzBaze[i].zavisi_od_vrste_senzora === 0) {
            select_data.push({
                id: "0|" + stavkeIzBaze[i].id,
                text: stavkeIzBaze[i].naziv
            })
        }
    }
    for (let j = 0; j < izabraniSenzori.length; j++) {
        //debugger;
        var obj = {
            text: izabraniSenzori[j].text,
            children: []
        };
        for (var z = 0; z < stavkeIzBaze.length; z++) {
            if (stavkeIzBaze[z].zavisi_od_vrste_senzora === 1) {
                obj.children.push({
                    id: izabraniSenzori[j].id + "|" + stavkeIzBaze[z].id,
                    text: stavkeIzBaze[z].naziv + izabraniSenzori[j].text
                });
            }
        }
        select_data.push(obj);
    }
    stavkeFakture = select_data;
}
function addRow(){
    brojRedova++;
    var new_row = `
        <div class="row" id="row_`+brojRedova+`">
            <div class="col-md-2 form-group">
                <select name="stavka_fakture_`+brojRedova+`" id="stavka_fakture_`+brojRedova+`" data-placeholder="Stavka fakture" class="select new_row_select" onchange="stavkaChanged(`+brojRedova+`)">
                    <option></option>
                </select>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <input type="text" name="datum_pocetak_`+brojRedova+`" id="datum_pocetak_`+brojRedova+`" class="form-control pickadate-selectors" placeholder="Datum početak">
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <input type="text" name="datum_kraj_`+brojRedova+`" id="datum_kraj_`+brojRedova+`" class="form-control pickadate-selectors" placeholder="Datum kraj">
            </div>

            <div class="col-md-2 form-group" style="margin-left: 5px;">
                <input type="number" step=".01" min="0" name="naknada_`+brojRedova+`" id="naknada_`+brojRedova+`" class="form-control" min="0" value="0">
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px;">
                <select name="status_`+brojRedova+`" id="status_`+brojRedova+`" data-placeholder="Status" class="select">
                    <option></option>
                    <option value="1">Aktivni</option>
                    <option value="2">Prijavljeni</option>
                    <option value="3">N/A</option>
                </select>
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
                <label id="datum_kraj_`+brojRedova+`_error" for="datum_kraj_`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti poslednji dan u mesecu!</label>
                <label id="datum_kraj_`+brojRedova+`_error2" for="datum_pocetak_`+brojRedova+`" class="validation-error-label" style="display: none;">Mora biti veci od datuma pocetka!</label>
            </div>

            <div class="col-md-2 form-group" style="margin-left: 5px; ">
                <label id="naknada_`+brojRedova+`_error" for="naknada_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                <label id="status_`+brojRedova+`_error" for="status_`+brojRedova+`" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                <label id="min_`+brojRedova+`_error" for="min_`+brojRedova+`" class="validation-error-label" style="display: none;">Maximum mora biti veći od minimuma!</label>
            </div>

            <div class="col-md-1 form-group" style="margin-left: 5px; ">
                <label id="max_`+brojRedova+`_error" for="max_`+brojRedova+`" class="validation-error-label" style="display: none;">Maximum mora biti veći od minimuma!</label>
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

    dosloDoIzmene();

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
    dosloDoIzmene();
    $("#aktivne_stavke").val(aktivneStavke);
}
function removeActiveRow(row_id){
    var notice = new PNotify({
        title: 'Confirmation',
        text: '<p>Da li ste sigurni da želite da obršete komercijalni uslov?</p>',
        hide: false,
        type: 'warning',
        addclass: 'bg-telekom-slova',
        confirm: {
            confirm: true,
            buttons: [
                {
                    text: 'Obriši',
                    addClass: 'btn-sm'
                },
                {
                    text: 'Poništi',
                    addClass: 'btn-sm'
                }
            ]
        },
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        }
    });
    notice.get().on('pnotify.confirm', function() {
        var id = parseInt($("#id_komercijalni_uslov_"+row_id).val());
        if(id !== 0){
            $.ajax({
                type: 'GET',
                url: baseUrl+'ajax/delete/komercijalniuslov/'+id,
                success: function (data) {
                    removeRow(row_id);
                },
                error: function (xhr, status, error) {
                    new PNotify({
                        title: 'Greška!',
                        text: xhr.responseText,
                        addclass: 'bg-telekom-slova',
                        hide: false,
                        buttons: {
                            sticker: false
                        }
                    });
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
        else{
            new PNotify({
                title: 'Greška!',
                text: 'Desila se neočekivana greška, id = 0!',
                addclass: 'bg-telekom-slova',
                hide: false,
                buttons: {
                    sticker: false
                }
            });
        }
    });

    /*$("#row_"+row_id).remove();
    $("#row_"+row_id+"_error").remove();
    var index = aktivneStavke.indexOf(row_id);
    aktivneStavke.splice(index, 1);
    console.log(aktivneStavke);
    $("#aktivne_stavke").val(aktivneStavke);*/
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
function izmenaDatuma(){
    menjanDatum = true;
    dosloDoIzmene();
}
function izmenaNalogaIliPropustanja(tip, element){
    if(tip === 1){
        if($("#"+element.id).val() !== ""){
            nijeSeDesilaIzmenaNaloga = false;
        }
    }
    else{
        if($("#"+element.id).val() !== ""){
            nijeSeDesilaIzmenaPropustanja = false;
        }
    }

}
function dosloDoIzmene(){
    if(!menjanDatum){
        //nije menjan datum proveriti da li je bilo izmena ostalih
        var brojDodeljenihOld = parseInt($("#brojDodeljenihOld").val());
        var brojDodeljenih = parseInt($("#brojDodeljenih").val());

        if(aktivneStavke.toString() === aktivneStavkeNaPocetku.toString()
            && aktivniRedoviPojedinacniNalog.toString() === aktivniRedoviPojedinacniNalogNaStartu.toString()
            && postojeciNalozi.toString() === postojeciNaloziPocetak.toString()
            && aktivniRedoviPropustanje.toString() === aktivniRedoviPropustanjeNaStartu.toString()
            && postojecaPropustanja.toString() === postojecaPropustanjaPocetak.toString()
            && brojDodeljenih === brojDodeljenihOld
            && nijeSeDesilaIzmenaNaloga
            && nijeSeDesilaIzmenaPropustanja){
            //nema izmene disable true
            $("#submit_izmene").prop('disabled', true);
        }
        else{
            //bilo izmena disable false
            // console.log('doslo do izmene');
            // console.log(aktivniRedoviPojedinacniNalog.toString() === aktivniRedoviPojedinacniNalogNaStartu.toString());
            // console.log(aktivniRedoviPojedinacniNalog);
            // console.log(aktivniRedoviPojedinacniNalogNaStartu);
            $("#submit_izmene").prop('disabled', false);
        }
    }
    else{
        $("#submit_izmene").prop('disabled', false);
    }
}
function deaktivirajUgovor(id){
    var notice = new PNotify({
        title: 'Confirmation',
        text: '<p>Da li ste sigurni da želite da deaktivirate ugovor?</p>',
        hide: false,
        type: 'warning',
        addclass: 'bg-telekom-slova',
        confirm: {
            confirm: true,
            buttons: [
                {
                    text: 'Deaktiviraj',
                    addClass: 'btn-sm'
                },
                {
                    text: 'Poništi',
                    addClass: 'btn-sm'
                }
            ]
        },
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        }
    });
    notice.get().on('pnotify.confirm', function() {
        if(id !== 0){
            $.ajax({
                type: 'GET',
                url: baseUrl+'ajax/delete/dekativirajugovor/'+id,
                success: function (data) {
                    //console.log(data);
                    window.location.href = baseUrl+'home';
                },
                error: function (xhr, status, error) {
                    new PNotify({
                        title: 'Greška!',
                        text: xhr.responseText,
                        addclass: 'bg-telekom-slova',
                        hide: false,
                        buttons: {
                            sticker: false
                        }
                    });
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
        else{
            new PNotify({
                title: 'Greška!',
                text: 'Desila se neočekivana greška, id = 0!',
                addclass: 'bg-telekom-slova',
                hide: false,
                buttons: {
                    sticker: false
                }
            });
        }
    });
}
function secondStepVerification(){
    var rows_with_errors = [];

    if(aktivneStavke.length === 0){
        //obirsane su sve stavke fakture -> strana je prazna -> pravi se ugovor bez komercijalnih uslova
        return true;
    }



    for(var i = 0; i < aktivneStavke.length; i++){
        $("#row_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#stavka_fakture_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#datum_pocetak_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#datum_kraj_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#datum_kraj_" + (aktivneStavke[i]) + "_error2").attr('style','display: none;');
        $("#naknada_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#status_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#min_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');
        $("#max_" + (aktivneStavke[i]) + "_error").attr('style','display: none;');

        var id_stavka = $("#stavka_fakture_"+(aktivneStavke[i])).val();
        var datum_pocetak = 0;
        var datum_kraj =  $("#datum_kraj_"+(aktivneStavke[i])).val();
        var naknada = $("#naknada_"+(aktivneStavke[i])).val();
        var status = $("#status_"+(aktivneStavke[i])).val();
        var min = parseInt($("#min_"+(aktivneStavke[i])).val());
        var max = parseInt($("#max_"+(aktivneStavke[i])).val());

        var row_errors = [];

        if(id_stavka === "") row_errors.push('stavka_fakture_'+aktivneStavke[i]+ "_error");
        if(naknada === "" || parseFloat(naknada) === 0) row_errors.push('naknada_'+aktivneStavke[i]+ "_error");
        if(status === "") row_errors.push('status_'+aktivneStavke[i]+ "_error");

        if( $("#id_komercijalni_uslov_"+ (aktivneStavke[i])).length ){
            //postoji id u htmlu -> znaci da je vec aktivan uslov, tj da je procitan iz baze
            datum_pocetak = $("#datum_pocetak_hidden_"+(aktivneStavke[i])).val();
        }
        else{
            datum_pocetak = $("input[name=datum_pocetak_"+(aktivneStavke[i])+"_data]").val();
            datum_kraj =  $("input[name=datum_kraj_"+(aktivneStavke[i])+"_data]").val();
        }


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

        var datumPocetak = new Date(datum_pocetak);
        var datumKraj = new Date(datum_kraj);
        if(datumKraj < datumPocetak){
            row_errors.push('datum_kraj_'+aktivneStavke[i]+ "_error2");
        }


        console.log("min max:",min,max);
        if(max < min){
            row_errors.push('min_'+aktivneStavke[i]+ "_error");
            row_errors.push('max_'+aktivneStavke[i]+ "_error");
            console.log(true);
        }
        else{
            console.log(false);
        }

        console.log('errors',row_errors);

        if(row_errors.length !== 0){
            $("#row_" + (aktivneStavke[i]) + "_error").attr('style','');
            for(var j = 0; j < row_errors.length; j++){
                $("#"+row_errors[j]).attr('style','');
            }
            rows_with_errors.push(aktivneStavke[i]);
        }
    }



    return rows_with_errors.length === 0;
}

function removeRowPojedinacniNalog(row_id, db_id){
        if(db_id > 0){
            var notice = new PNotify({
                title: 'Confirmation',
                text: '<p>Da li ste sigurni da želite da obršete pojedinacni nalog?</p>',
                hide: false,
                type: 'warning',
                addclass: 'bg-telekom-slova',
                confirm: {
                    confirm: true,
                    buttons: [
                        {
                            text: 'Obriši',
                            addClass: 'btn-sm'
                        },
                        {
                            text: 'Poništi',
                            addClass: 'btn-sm'
                        }
                    ]
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                }
            });
            notice.get().on('pnotify.confirm', function() {
                var id = db_id;
                if(id !== 0){
                    $.ajax({
                        type: 'GET',
                        url: baseUrl+'ajax/delete/pojedinacninalog/'+id,
                        success: function (data) {
                            $("#pojedinacniNalog_row_"+row_id).remove();
                            $("#pojedinacniNalog_row_"+row_id+"_error").remove();
                            var index = aktivniRedoviPojedinacniNalog.indexOf(row_id);
                            aktivniRedoviPojedinacniNalog.splice(index, 1);
                            postojeciNalozi = postojeciNalozi.filter(item => item !== parseInt(id));
                            $("#postojeciNalozi").val(postojeciNalozi);
                            $("#aktivniRedoviPojedinacniNalog").val(aktivniRedoviPojedinacniNalog);
                        },
                        error: function (xhr, status, error) {
                            new PNotify({
                                title: 'Greška!',
                                text: xhr.responseText,
                                addclass: 'bg-telekom-slova',
                                hide: false,
                                buttons: {
                                    sticker: false
                                }
                            });
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
                else{
                    new PNotify({
                        title: 'Greška!',
                        text: 'Desila se neočekivana greška, id = 0!',
                        addclass: 'bg-telekom-slova',
                        hide: false,
                        buttons: {
                            sticker: false
                        }
                    });
                }
            });
        }
        else{
            $("#pojedinacniNalog_row_"+row_id).remove();
            $("#pojedinacniNalog_row_"+row_id+"_error").remove();
            var index = aktivniRedoviPojedinacniNalog.indexOf(row_id);
            aktivniRedoviPojedinacniNalog.splice(index, 1);
            $("#aktivniRedoviPojedinacniNalog").val(aktivniRedoviPojedinacniNalog);
        }
    // }
    // else{
    //     new PNotify({
    //         title: 'Info',
    //         text: '<p>Bar jedan mora da ostane!</p>',
    //         hide: false,
    //         type: 'warning',
    //         addclass: 'bg-telekom-slova',
    //         buttons: {
    //             closer: true,
    //             sticker: false
    //         },
    //         history: {
    //             history: false
    //         }
    //     });
    // }
}
function addRowPojedinacniNalog(){
    brojRedovaPojedinacniNalog++;

    var row = `
        <div id="pojedinacniNalog_row_`+brojRedovaPojedinacniNalog+`">
            <label class="col-lg-1 control-label" for="imePojedinacniNalog`+brojRedovaPojedinacniNalog+`">Ime:</label>
            <div class="col-lg-1">
                <input type="text" class="form-control" placeholder="Ime" id="imePojedinacniNalog`+brojRedovaPojedinacniNalog+`" name="imePojedinacniNalog`+brojRedovaPojedinacniNalog+`">
            </div>

            <label class="col-lg-1 control-label" for="prezimePojedinacniNalog`+brojRedovaPojedinacniNalog+`">Prezime:</label>
            <div class="col-lg-1">
                <input type="text" class="form-control" placeholder="Prezime" id="prezimePojedinacniNalog`+brojRedovaPojedinacniNalog+`" name="prezimePojedinacniNalog`+brojRedovaPojedinacniNalog+`">
            </div>

            <label class="col-lg-1 control-label" for="emailPojedinacniNalog`+brojRedovaPojedinacniNalog+`">E-mail:</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" placeholder="E-mail" id="emailPojedinacniNalog`+brojRedovaPojedinacniNalog+`" name="emailPojedinacniNalog`+brojRedovaPojedinacniNalog+`">
            </div>

            <label class="col-lg-1 control-label" for="brojTelefonaPojedinacniNalog`+brojRedovaPojedinacniNalog+`">Broj telefona:</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" placeholder="Broj telefona" id="brojTelefonaPojedinacniNalog`+brojRedovaPojedinacniNalog+`" name="brojTelefonaPojedinacniNalog`+brojRedovaPojedinacniNalog+`">
            </div>

            <div class="col-lg-2 text-center">
                <a href="#/" onclick="addRowPojedinacniNalog()" class="button-back btn bg-telekom-slova">Dodaj red <i class="icon-plus3 position-right"></i></a>
                <a href="#/" onclick="removeRowPojedinacniNalog(`+brojRedovaPojedinacniNalog+`, false)" class="button-back btn bg-telekom-slova">Orisi red <i class="icon-minus3 position-right"></i></a>
            </div>
        </div>
        <div class="row" id="pojedinacniNalog_row_`+brojRedovaPojedinacniNalog+`_error" style="display: none;">
            <div class="col-md-1 form-group"></div>
            <div class="col-md-1 form-group">
                <label id="imePojedinacniNalog_`+brojRedovaPojedinacniNalog+`_error" for="stavka_fakture_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group"></div>
            <div class="col-md-1 form-group">
                <label id="prezimePojedinacniNalog_`+brojRedovaPojedinacniNalog+`_error" for="stavka_fakture_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group"></div>
            <div class="col-md-2 form-group">
                <label id="emailPojedinacniNalog_`+brojRedovaPojedinacniNalog+`_error" for="stavka_fakture_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>

            <div class="col-md-1 form-group"></div>
            <div class="col-md-2 form-group">
                <label id="brojTelefonaPojedinacniNalog_`+brojRedovaPojedinacniNalog+`_error" for="stavka_fakture_1" class="validation-error-label" style="display: none;">Obavezno polje!</label>
            </div>
        </div>
    `;

    $("#pojedinacniNalog_wrapper").append(row);

    aktivniRedoviPojedinacniNalog.push(brojRedovaPojedinacniNalog);
    $("#aktivniRedoviPojedinacniNalog").val(aktivniRedoviPojedinacniNalog);
}

function removeRowPropustanje(row_id, db_id){
    if(db_id > 0){
        var notice = new PNotify({
            title: 'Confirmation',
            text: '<p>Da li ste sigurni da želite da obršete propuštanje?</p>',
            hide: false,
            type: 'warning',
            addclass: 'bg-telekom-slova',
            confirm: {
                confirm: true,
                buttons: [
                    {
                        text: 'Obriši',
                        addClass: 'btn-sm'
                    },
                    {
                        text: 'Poništi',
                        addClass: 'btn-sm'
                    }
                ]
            },
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            }
        });
        notice.get().on('pnotify.confirm', function() {
            var id = db_id;
            if(id !== 0){
                $.ajax({
                    type: 'GET',
                    url: baseUrl+'ajax/delete/propustanje/'+id,
                    success: function (data) {
                        console.log(data);
                        console.log(row_id);
                        $("#propustanje_row_"+row_id).remove();
                        // $("#propustanje_row_"+row_id+"_error").remove();
                        var index = aktivniRedoviPropustanje.indexOf(row_id);
                        aktivniRedoviPropustanje.splice(index, 1);
                        postojecaPropustanja = postojecaPropustanja.filter(item => item !== parseInt(id));
                        $("#postojecaPropustanja").val(postojecaPropustanja);
                        $("#aktivniRedoviPropustanje").val(aktivniRedoviPropustanje);
                    },
                    error: function (xhr, status, error) {
                        new PNotify({
                            title: 'Greška!',
                            text: xhr.responseText,
                            addclass: 'bg-telekom-slova',
                            hide: false,
                            buttons: {
                                sticker: false
                            }
                        });
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            }
            else{
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, id = 0!',
                    addclass: 'bg-telekom-slova',
                    hide: false,
                    buttons: {
                        sticker: false
                    }
                });
            }
        });
    }
    else{
        $("#propustanje_row_"+row_id).remove();
        $("#propustanje_row_"+row_id+"_error").remove();
        var index = aktivniRedoviPropustanje.indexOf(row_id);
        aktivniRedoviPropustanje.splice(index, 1);
        $("#aktivniRedoviPropustanje").val(aktivniRedoviPropustanje);
    }
}
function addRowPropustanje(){
    brojRedovaPropustanje++;

    var row = `
        <div id="propustanje_row_`+brojRedovaPropustanje+`" style="margin-bottom: 50px;">
            <label class="col-lg-2 control-label" for="ipPropustanje`+brojRedovaPropustanje+`">Mrežno propuštanje IP:PORT :</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" placeholder="IP" id="ipPropustanje`+brojRedovaPropustanje+`" name="ipPropustanje`+brojRedovaPropustanje+`">
            </div>
            <div class="col-lg-1">
                <input type="text" class="form-control" placeholder="PORT" id="portPropustanje`+brojRedovaPropustanje+`" name="portPropustanje`+brojRedovaPropustanje+`">
            </div>

            <label class="col-lg-1 control-label" for="appUrl`+brojRedovaPropustanje+`">URL aplikacije</label>
            <div class="col-lg-4">
                <input type="text" class="form-control" placeholder="URL aplikacije" id="appUrl`+brojRedovaPropustanje+`" name="appUrl`+brojRedovaPropustanje+`">
            </div>

            <div class="col-lg-2 text-center">
                <a href="#/" onclick="addRowPropustanje()" class="button-back btn bg-telekom-slova">Dodaj red <i class="icon-plus3 position-right"></i></a>
                <a href="#/" onclick="removeRowPropustanje(`+brojRedovaPropustanje+`)" class="button-back btn bg-telekom-slova">Orisi red <i class="icon-minus3 position-right"></i></a>
            </div>
        </div>
        <div class="row" id="propustanje_row_`+brojRedovaPropustanje+`_error" style="display: none;">
            <div class="col-md-2 form-group"></div>
            <div class="col-md-2 form-group">
                <label id="ip_`+brojRedovaPropustanje+`_error" for="ipPropustanje`+brojRedovaPropustanje+`" class="validation-error-label" style="display: none;">Obavezno polje/format ip adrese!</label>
            </div>
        </div>
    `;

    $("#propustanje_wrapper").append(row);

    aktivniRedoviPropustanje.push(brojRedovaPropustanje);
    $("#aktivniRedoviPropustanje").val(aktivniRedoviPropustanje);
}

function checkLicences(){
    $("#brojDodeljenih_error").attr('style','display: none;');
    if(parseInt($("#brojDodeljenih").val()) < 0){
        $("#brojDodeljenih_error").attr('style','');
        return false;
    }
    return true;
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
            dosloDoIzmene();
            return checkLicences();
        },
        finish: function() {
            //provara inputa za komercijalne uslovew
            submitted = true;
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

    //Date time picker podesavanja
    $('.pickadate-selectors').pickadate(pickDateOptions);

    //Checkbox
    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });

    //podesavanje stavki fakture
    postaviStavkeFakture();

    //postavljanje inicijalnih stavki fakture
    $("#aktivne_stavke").val(aktivneStavke);
})
