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

    $("#ime_error_2").attr('style','display: none;');
    $("#prezime_error_2").attr('style','display: none;');
    $("#email_error_2").attr('style','display: none;');
    $("#lozinka_error_2").attr('style','display: none;');
    $("#lozinka_ponovo_error_2").attr('style','display: none;');

    var ime = $("#ime").val();
    var prezime = $("#prezime").val();
    var email = $("#email").val();
    var lozinka = $("#lozinka").val();
    var lozinkaPonovo = $("#lozinka_ponovo").val();


    if(ime === "") greske.push('ime_error');
    if(prezime === "") greske.push('prezime_error');
    if(email === "") greske.push('email_error');
    if(lozinka === "") greske.push('lozinka_error');
    if(lozinkaPonovo === "") greske.push('lozinka_ponovo_error');


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
