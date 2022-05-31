$(function() {
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

    $(".control-primary").uniform({
        radioClass: 'choice',
        wrapperClass: 'border-danger-600 text-danger-800'
    });
});

function proveriStavkuFakture(){
    var postoji_geska = false;

    $("#naziv_error").attr('style','display: none;');
    $("#naknada_error").attr('style','display: none;');
    $("#tip_naknade_error").attr('style','display: none;');

    var naziv = $("#naziv").val();
    var naknada = $("#naknada").val();
    var tip_naknade = $("#tip_naknade").val();

    if(naziv === "") {
        $("#naziv_error").attr('style','');
        postoji_geska = true;
    }
    if(naknada <= 0) {
        $("#naknada_error").attr('style', '');
        postoji_geska = true;
    }
    if(tip_naknade === "") {
        $("#tip_naknade_error").attr('style', '');
        postoji_geska = true;
    }

    if(!postoji_geska){
        //poslati formu
        $("#form_stavka_fakture").submit();
    }
}

function deleteRecord(id_var){
    var notice = new PNotify({
        title: 'Confirmation',
        text: '<p>Da li ste sigurni da želite da obršete stavku fakture?</p>',
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
        var id = parseInt(id_var);
        if(id !== 0){
            $.ajax({
                type: 'GET',
                url: baseUrl+'ajax/delete/stavkafakture/'+id,
                success: function (data) {
                    new PNotify({
                        title: 'Uspeh!',
                        text: 'Uspešno obrisan zapis!',
                        addclass: 'bg-success'
                    });
                    window.location.reload();
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
