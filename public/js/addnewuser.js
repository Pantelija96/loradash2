$(function() {
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            orderable: false,
            width: '100px',
            targets: [ 5 ]
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Prikaži zapisa:</span> _MENU_',
            paginate: { 'first': 'Prva', 'last': 'Poslednja', 'next': '&rarr;', 'previous': '&larr;' },
            sEmptyTable: 'Prazna tabela!',
            sInfo: "Prikaz _START_ do _END_ od _TOTAL_ zapisa",
            sInfoEmpty: "Nema zapisa u tabeli!",
            sInfoFiltered: "(filtirirano od _MAX_ ukupno zapisa)",
            sSearch: "Pretraga:",
            sLoadingRecords: "Učitavanje",
            sProcessing: "Obrada..."
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });

    var lastIdx = null;
    var table = $('.datatable-highlight').DataTable();

    $('.datatable-highlight tbody').on('mouseover', 'td', function() {
        var colIdx = table.cell(this).index().column;

        if (colIdx !== lastIdx) {
            $(table.cells().nodes()).removeClass('active');
            $(table.column(colIdx).nodes()).addClass('active');
        }
    }).on('mouseleave', function() {
        $(table.cells().nodes()).removeClass('active');
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.select').select2();
});

function proveraLozinke(){
    //alert ("TEST");
    var prvaLozinka = $("#lozinka").val();
    var ponovoLozinka = $("#lozinka_ponovo").val();

    if((prvaLozinka === ponovoLozinka) && ponovoLozinka !== ""){
        $("#dugme").prop('disabled',false);
        $("#lozinka_ponovo_error").attr('style','display: none;');
    }
    else{
        $("#dugme").prop('disabled',true);
        $("#lozinka_ponovo_error").attr('style','');
        new PNotify({
            title: 'Greška!',
            text: 'Lozinke se ne poklapaju!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    }
}

function proveri(){
    let greske = [];

    $("#ime_error").attr('style','display: none;');
    $("#prezime_error").attr('style','display: none;');
    $("#email_error").attr('style','display: none;');
    $("#lozinka_error").attr('style','display: none;');
    $("#lozinka_ponovo_error").attr('style','display: none;');
    $("#uloga_error").attr('style','display: none;');

    $("#ime_error_2").attr('style','display: none;');
    $("#prezime_error_2").attr('style','display: none;');
    $("#email_error_2").attr('style','display: none;');
    $("#lozinka_error_2").attr('style','display: none;');
    $("#lozinka_ponovo_error_2").attr('style','display: none;');
    $("#uloga_error_2").attr('style','display: none;');

    var ime = $("#ime").val();
    var prezime = $("#prezime").val();
    var email = $("#email").val();
    var lozinka = $("#lozinka").val();
    var lozinkaPonovo = $("#lozinka_ponovo").val();
    var uloga = $("#uloga").val();


    if(ime === "") greske.push('ime_error');
    if(prezime === "") greske.push('prezime_error');
    if(email === "") greske.push('email_error');
    if(lozinka === "") greske.push('lozinka_error');
    if(lozinkaPonovo === "") greske.push('lozinka_ponovo_error');
    if(uloga === "") greske.push('uloga_error');


    if(greske.length === 0){
        new PNotify({
            title: 'Uspešno popunjeno!',
            text: 'Slanje...',
            addclass: 'bg-success'
        });
        $("#forma").submit();
    }
    else{
        new PNotify({
            title: 'Greška!',
            text: 'Ispravite greške za nastavak!',
            addclass: 'bg-telekom-slova',
            hide: false,
            buttons: {
                sticker: false
            }
        });
        for(let i = 0; i < greske.length; i++){
            $("#"+greske[i]).attr('style','');
        }
    }

}

function brisanjeUsera(idUser){
    var idKorisnika = parseInt(idUser);
    if(idKorisnika !== 0){
        var notice = new PNotify({
            title: 'Confirmation',
            text: '<p>Da li ste sigurni da želite da obršete korisnika portala?</p>',
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
            $.ajax({
                type: 'GET',
                url: baseUrl+'ajax/deleteuser/'+idKorisnika,
                success: function (data) {
                    new PNotify({
                        title: 'Uspeh!',
                        text: 'Uspešno obrisan korsnik!',
                        addclass: 'bg-success'
                    });
                    window.location.reload();
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
