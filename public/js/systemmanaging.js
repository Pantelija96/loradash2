$(function() {
    $('.select').select2();
});

function prikaziAlert(idZapisa, idBaze){
    var notice = new PNotify({
        title: 'Confirmation',
        text: '<p>Da li ste sigurni da želite da obršete zapis?</p>',
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
        if(idBaze === 1){
            obrisiStavkuFakture(idZapisa);
        }
        if(idBaze === 2){
            obrisiTipUgovora(idZapisa);
        }
        if(idBaze === 3){
            obrisiTipServisa(idZapisa);
        }
        if(idBaze === 4) {
            obrisiTipTehnologije(idZapisa);
        }
        if(idBaze === 5){
            obrisiPartnera(idZapisa);
        }
        if(idBaze === 6){
            obrisiServis(idZapisa);
        }
        if(idBaze === 7){
            obrisiSenzor(idZapisa);
        }
        if(idBaze === 8){
            obrisiLokaciju(idZapisa);
        }
    });
}

function proveriStavkuFakture(){
    var postojiGreska = false;
    var naziv = $("#nazivStavkeFakture").val();
    var naknada = parseFloat($("#mesecnaNaknada").val());
    var tip = $("#tipNaknade").val();

    $("#nazivStavkeFaktureError2").attr('style','display: none;');
    $("#mesecnaNaknadaError2").attr('style','display: none;');
    $("#tipNaknadeError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#nazivStavkeFaktureError").html("Obavezno polje!").attr('style','');
    }
    else{
        if(naziv.length > 200){
            postojiGreska = true;
            $("#nazivStavkeFaktureError").html("Maximalno 200 karaktera!").attr('style','');
        }
        else{
            postojiGreska = false;
            $("#nazivStavkeFaktureError").attr('style','display: none;');
        }
    }

    if(naknada < 0){
        postojiGreska = true;
        $("#mesecnaNaknadaError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#mesecnaNaknadaError").attr('style','display: none;');
    }

    if(tip === ""){
        postojiGreska = true;
        $("#tipNaknadeError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#tipNaknadeError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formStavkaFakture").submit();
    }
}
function brisanjeStavkeFakture(id){
    prikaziAlert(id, 1);
}
function obrisiStavkuFakture(id){
    var idStavke = parseInt(id);
    if(idStavke !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletestavkafakture/'+idStavke,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriTipUgovora(){
    var postojiGreska = false;
    var naziv = $("#nazivTipUgovora").val();

    $("#nazivTipUgovoraError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#nazivTipUgovoraError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#nazivTipUgovoraError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaTipUgovora").submit();
    }
}
function brisanjeTipaUgovora(id){
    prikaziAlert(id, 2);
}
function obrisiTipUgovora(id){
    var idTipUgovora = parseInt(id);
    if(idTipUgovora !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletetipugovora/'+idTipUgovora,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriTipServisa(){
    var postojiGreska = false;
    var naziv = $("#nazivTipServisa").val();

    $("#nazivTipServisaError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#nazivTipServisaError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#nazivTipServisaError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaTipServisa").submit();
    }
}
function brisanjeTipaServisa(id){
    prikaziAlert(id, 3);
}
function obrisiTipServisa(id){
    var idTipServisa = parseInt(id);
    if(idTipServisa !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletetipservisa/'+idTipServisa,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriTipTehnologije(){
    var postojiGreska = false;
    var naziv = $("#nazivTipTehnologije").val();

    $("#nazivTipTehnologijeError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#nazivTipTehnologijeError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#nazivTipTehnologijeError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaTipTehnologije").submit();
    }
}
function brisanjeTipaTehnologije(id){
    prikaziAlert(id, 4);
}
function obrisiTipTehnologije(id){
    var idTipTehnologije = parseInt(id);
    if(idTipTehnologije !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletetiptehnologije/'+idTipTehnologije,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriPartnera(){
    var postojiGreska = false;
    var naziv = $("#partnerInput").val();

    $("#partnerError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#partnerError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#partnerError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaPartner").submit();
    }
}
function brisanjePartnera(id){
    prikaziAlert(id, 5);
}
function obrisiPartnera(id){
    var idPartner = parseInt(id);
    if(idPartner !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletepartner/'+idPartner,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriServis(){
    var postojiGreska = false;
    var naziv = $("#servisInput").val();

    $("#servisError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#servisError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#servisError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaServis").submit();
    }
}
function brisanjeServisa(id){
    prikaziAlert(id, 6);
}
function obrisiServis(id){
    var idServis = parseInt(id);
    if(idServis !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deleteservis/'+idServis,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriSenzor(){
    var postojiGreska = false;
    var naziv = $("#senzorInput").val();

    $("#senzorError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#senzorError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#senzorError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaSenzor").submit();
    }
}
function brisanjeSenzora(id){
    prikaziAlert(id, 7);
}
function obrisiSenzor(id){
    var idSenzor = parseInt(id);
    if(idSenzor !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletesenzor/'+idSenzor,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisan zapis!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}

function proveriLokaciju(){
    var postojiGreska = false;
    var naziv = $("#lokacijaInput").val();

    $("#lokacijaError2").attr('style','display: none;');

    if(naziv === ""){
        postojiGreska = true;
        $("#lokacijaError").html("Obavezno polje!").attr('style','');
    }
    else{
        postojiGreska = false;
        $("#lokacijaError").attr('style','display: none;');
    }

    if(postojiGreska){
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
    else{
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        document.getElementById("formaLokacija").submit();
    }
}
function brisanjeLokacije(id){
    prikaziAlert(id, 8);
}
function obrisiLokaciju(id){
    var idLokacija = parseInt(id);
    if(idLokacija !== 0){
        $.ajax({
            type: 'GET',
            url: baseUrl+'ajax/deletelokapp/'+idLokacija,
            success: function (data) {
                new PNotify({
                    title: 'Uspeh!',
                    text: 'Uspešno obrisana lokacija!',
                    addclass: 'bg-success'
                });
                if(data.success){
                    window.location.reload();
                }
            },
            error: function (xhr, status, error) {
                new PNotify({
                    title: 'Greška!',
                    text: 'Desila se neočekivana greška, proveriti console!',
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
}
