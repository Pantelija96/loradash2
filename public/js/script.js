(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function() {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery); // End of use strict

var senzori;
function getSensors(){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ispisiSenzore(this.responseText);
      }
  };
  xmlhttp.open("GET", "../dbControllers/getSensors.php", true);
  xmlhttp.send();
}
function ispisiSenzore(text){
  senzori = text;
}
getSensors();

function refreshSelects(){
  $('.js-example-basic-multiple').select2({
    placeholder: 'Izaberite mesece'
  });
}


var ukupnoRedova = 1;
var brojReda = 2;
function addToForm(){

  var htmlReda2 = `
  <div class="card shadow mb-3">
    <div id="formRow`+brojReda+`"  class="row">
        <div class="col-4">
            <label for="tipSenzora`+brojReda+`" class="col-form-label" style="font-size: 15px;">Tip senzora:</label>
            <select id="tipSenzora`+brojReda+`" name="tipSenzora`+brojReda+`" class="form-control" onchange="postaviCenuSenzora(`+brojReda+`)">
            <option value="0" selected>Izaberi iz liste...</option>
            `+senzori+`
          </select>
        </div>
        <div class="col-4">
            <label for="brojAktivnih`+brojReda+`" class="col-form-label" style="font-size: 15px;">Broj aktivnih senzora:</label>
            <input type="number" min="1" value="0" class="form-control" id="brojAktivnih`+brojReda+`" name="brojAktivnih`+brojReda+`" onchange="izmenaLagera(`+brojReda+`); izracunajCenu();">
        </div>
        <div class="col-4">
            <label for="brojNeaktivnih`+brojReda+`" class="col-form-label" style="font-size: 15px;">Broj povremeno neaktivnih:</label>
            <input type="number" min="0" value="0" class="form-control" id="brojNeaktivnih`+brojReda+`" name="brojNeaktivnih`+brojReda+`" onchange="izmenaLagera(`+brojReda+`); izracunajCenu(); imaNekativnih(`+brojReda+`);">
        </div>

        <div class="col-4">
            <label for="cenaSenzoraUGr`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena senzora u GR:</label>
            <input type="number" min="0" value="0" class="form-control" id="cenaSenzoraUGr`+brojReda+`" name="cenaSenzoraUGr`+brojReda+`" onchange="izracunajCenu(`+brojReda+`)">
        </div>
        <div class="col-4">
            <label for="cenaLicenceUGr`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena licence u GR:</label>
            <input type="number" min="0" value="0" class="form-control" id="cenaLicenceUGr`+brojReda+`" name="cenaLicenceUGr`+brojReda+`" onchange="izracunajCenu(`+brojReda+`)">
        </div>
        <div class="col-4">
            <label for="cenaServisaZaAktivne`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena servisa za aktivne:</label>
            <input type="number" min="0" value="0" class="form-control" id="cenaServisaZaAktivne`+brojReda+`" name="cenaServisaZaAktivne`+brojReda+`" onchange="izracunajCenu()">
        </div>

        <div class="col-4">
            <label for="cenaSenzoraVanGr`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena senzora <strong>van</strong> GR:</label>
            <input type="number" min="0" value="0" class="form-control" id="cenaSenzoraVanGr`+brojReda+`" name="cenaSenzoraVanGr`+brojReda+`" onchange="izracunajCenu(`+brojReda+`)">
        </div>
        <div class="col-4">
            <label for="cenaLicenceVanGr`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena licence <strong>van</strong> GR:</label>
            <input type="number" min="0" value="0" class="form-control" id="cenaLicenceVanGr`+brojReda+`" name="cenaLicenceVanGr`+brojReda+`" onchange="izracunajCenu(`+brojReda+`)">
        </div>
        <div class="col-4">
            <label for="cenaServisaZaNeaktivne`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena servisa za neaktivne:</label>
            <input type="number" min="0" value="0" class="form-control" id="cenaServisaZaNeaktivne`+brojReda+`" name="cenaServisaZaNeaktivne`+brojReda+`" onchange="izracunajCenu()">
        </div>

        <div class="col-4">
            <label for="komadaNaLageru`+brojReda+`" class="col-form-label" style="font-size: 15px;">Komada na lageru</label>
            <input disabled data-number="0" type="number" min="1" value="0" class="form-control" id="komadaNaLageru`+brojReda+`" name="komadaNaLageru`+brojReda+`">
        </div>
        <div class="col-4">
            <label for="nabavnaCena`+brojReda+`" class="col-form-label" style="font-size: 15px;">Nabavna cena</label>
            <input disabled type="number" min="1" value="0" class="form-control" id="nabavnaCena`+brojReda+`" name="nabavnaCena`+brojReda+`" onchange="izracunajCenu(`+brojReda+`)">
        </div>
        <div class="col-4">
            <label for="cenaTehnickePodrske`+brojReda+`" class="col-form-label" style="font-size: 15px;">Cena tehnicke podrske</label>
            <input type="number" min="1" value="0" class="form-control" id="cenaTehnickePodrske`+brojReda+`" name="cenaTehnickePodrske`+brojReda+`" onchange="izracunajCenu(`+brojReda+`)">
        </div>

        <div class="col-3">
            <label for="brojNeaktivnihMeseci`+brojReda+`" class="col-form-label" style="font-size: 15px;">Broj neaktivnih meseci</label>
            <input type="number" disabled value="1" min="1" max="12" class="form-control" id="brojNeaktivnihMeseci`+brojReda+`" name="brojNeaktivnihMeseci`+brojReda+`" onchange="izracunajCenu(); neaktivniMeseci(`+brojReda+`);">
        </div>
        <div class="col-8">
            <label for="neaktivniMeseci`+brojReda+`" class="col-form-label" style="font-size: 15px;">Neaktivni meseci</label>
            <select disabled class="js-example-basic-multiple form-control" name="neaktivniMeseci`+brojReda+`[]" id="neaktivniMeseci`+brojReda+`" multiple="multiple">
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
        </div>
        <div class="col-1">
            <div onclick="removeFromForm(`+brojReda+`)"><i style="padding-top: 49px; color: #e74a3b; cursor: pointer;" class="far fa-trash-alt fa-lg"></i></div>
        </div>
    </div>
  </div>
  `;
  ukupnoRedova++;
  brojReda++;
  document.getElementById('brojSenzora').value = ukupnoRedova;
  $( "#proba" ).append(htmlReda2);
  refreshSelects();
}

function removeFromForm(idRow){
  var elementId = "formRow"+idRow;
  $("#"+elementId).parent().remove();
  ukupnoRedova--;
  document.getElementById('brojSenzora').value = ukupnoRedova;
}

function promenaProbnogPerioda(){
  var probniPeriod =  $('#probniPeriod'). children("option:selected"). val();

  if(probniPeriod == "1"){
    document.getElementById("brojTrialMeseci").disabled = false;
    document.getElementById("brojTrialDana").disabled = false;
  }
  else{
    document.getElementById("brojTrialMeseci").disabled = true;
    document.getElementById("brojTrialDana").disabled = true;
    document.getElementById("brojTrialMeseci").value = 0;
    document.getElementById("brojTrialDana").value = 0;
  }
}

var noviDatum;
var trajanjeUgogovra = 0;
var trenutniDatum = new Date();
var datumNakonPromeneMeseci;


function postaviMaksimalnoTrajanjeProbnogPerioda(){
  trajanjeUgogovra = document.getElementById("brojMeseciUgovora").value;
  document.getElementById("brojTrialMeseci").setAttribute("max",trajanjeUgogovra);
}
function prikaziProbniPeriod(){
  var brojMeseci = parseInt(document.getElementById("brojTrialMeseci").value);
  var brojDana = parseInt(document.getElementById("brojTrialDana").value);

  noviDatum = new Date();
  noviDatum.setMonth(trenutniDatum.getMonth()+brojMeseci);
  noviDatum.setDate(trenutniDatum.getDate()+brojDana);

  var textZaProbniPeriodDo = noviDatum.getDate()+"."+(noviDatum.getMonth()+1)+"."+noviDatum.getFullYear();
  document.getElementById('inputProbniPeriodDo').value = textZaProbniPeriodDo;
}

function imaNekativnih(id){
    var brojNeaktivnih = document.getElementById("brojNeaktivnih"+id).value;
    if(brojNeaktivnih>0){
      document.getElementById("brojNeaktivnihMeseci"+id).disabled = false;
      document.getElementById("neaktivniMeseci"+id).disabled = false;
      neaktivniMeseci(id);
    }
}
function neaktivniMeseci(id){
  var broj = document.getElementById('brojNeaktivnihMeseci'+id).value;
  $('#neaktivniMeseci'+id).select2({
    maximumSelectionLength: broj
  });
}

function izracunajCenu(){
  var cenaGrMin = 0;
  var cenaGrMax = 0;
  var cenaVanGrMin = 0;
  var cenaVanGrMax = 0;
  for(var i = 0; i< brojReda-1 ; i++){
    var brojAktivnih = parseInt(document.getElementById("brojAktivnih"+(i+1)).value);
    var brojNeaktivnih = parseInt(document.getElementById("brojNeaktivnih"+(i+1)).value);

    var cenaSenGr = parseInt(document.getElementById("cenaSenzoraUGr"+(i+1)).value);
    var cenaSenVan = parseInt(document.getElementById("cenaSenzoraVanGr"+(i+1)).value);

    var cenaLicGr = parseInt(document.getElementById('cenaLicenceUGr'+(i+1)).value);
    var cenaLicVan = parseInt(document.getElementById('cenaLicenceVanGr'+(i+1)).value);

    var cenaSerNeaktivni = parseInt(document.getElementById('cenaServisaZaNeaktivne'+(i+1)).value);
    var cenaSerAktivni = parseInt(document.getElementById('cenaServisaZaAktivne'+(i+1)).value);

    var tehnickaPodrska = parseInt(document.getElementById('cenaTehnickePodrske'+(i+1)).value);

    if(brojNeaktivnih>0){
      cenaGrMin += ((brojAktivnih)*(cenaSenGr+cenaLicGr+cenaSerAktivni+tehnickaPodrska)) + ((brojNeaktivnih)*(cenaSenGr+cenaLicGr+cenaSerNeaktivni+tehnickaPodrska));
      cenaVanGrMin += ((brojAktivnih)*(cenaSenVan+cenaLicVan+cenaSerAktivni+tehnickaPodrska)) + ((brojNeaktivnih)*(cenaSenVan+cenaLicVan+cenaSerNeaktivni+tehnickaPodrska));
    }

    cenaGrMax = (brojAktivnih+brojNeaktivnih)*(cenaSenGr+cenaLicGr+cenaSerAktivni+tehnickaPodrska);
    cenaVanGrMax = (brojAktivnih+brojNeaktivnih)*(cenaSenVan+cenaLicVan+cenaSerAktivni+tehnickaPodrska);

  }

  if(cenaGrMin != 0){
    document.getElementById("mesecnaPretplataGRFinal").value = "Aktivni: "+cenaGrMax+", neaktivni: "+cenaGrMin;
  }
  else{
    document.getElementById("mesecnaPretplataGRFinal").value = "Svi meseci su aktivni: "+cenaGrMax;
  }

  if(cenaVanGrMin != 0){
    document.getElementById("mesecnaPretplataVanFinal").value = "Aktivni: "+cenaVanGrMax+", neaktivni: "+cenaVanGrMin;
  }
  else{
    document.getElementById("mesecnaPretplataVanFinal").value = "Svi meseci su aktivni: "+cenaVanGrMax;
  }
}

function postaviCenuSenzora(id){
  var data = JSON.parse($('#tipSenzora'+id+' :selected').attr("data-id"));
  console.log(data);
  document.getElementById("cenaSenzoraUGr"+id).value = parseInt(data.cenaSenzoraGR);
  document.getElementById("cenaLicenceUGr"+id).value = parseInt(data.cenaAppGR);
  document.getElementById("cenaServisaZaAktivne"+id).value = parseInt(data.cenaServisaAktivan);
  document.getElementById("cenaSenzoraVanGr"+id).value = parseInt(data.cenaSenzoraVanGR);
  document.getElementById("cenaLicenceVanGr"+id).value = parseInt(data.cenaAppVanGR);
  document.getElementById("cenaServisaZaNeaktivne"+id).value = parseInt(data.cenaServisaNeaktivan);
  document.getElementById("komadaNaLageru"+id).value = parseInt(data.komadaNaLageru);
  document.getElementById("komadaNaLageru"+id).setAttribute("data-number",parseInt(data.komadaNaLageru));
  document.getElementById("brojAktivnih"+id).setAttribute("max", parseInt(data.komadaNaLageru));
  document.getElementById("brojNeaktivnih"+id).setAttribute("max", parseInt(data.komadaNaLageru));
  document.getElementById("nabavnaCena"+id).value = parseInt(data.nabavnaCena);
  document.getElementById("cenaTehnickePodrske"+id).value = parseInt(data.tehnickaPodrska);
  izracunajCenu();
}

function izmenaLagera(id){
  var aktivni = document.getElementById("brojAktivnih"+id).value;
  var neaktivni = document.getElementById("brojNeaktivnih"+id).value;
  var lager = document.getElementById("komadaNaLageru"+id).getAttribute("data-number") - aktivni - neaktivni;
  //razmisliti o ajaxu za smanjivanje u tabeli
  document.getElementById("komadaNaLageru"+id).value = lager;
  document.getElementById("brojAktivnih"+id).setAttribute("max",parseInt(aktivni)+parseInt(lager));
  document.getElementById("brojNeaktivnih"+id).setAttribute("max", parseInt(neaktivni)+parseInt(lager));
}

function checkPasswords(){
  var loz1 = document.getElementById("newPassword").value;
  var loz2 = document.getElementById("newPasswordAgain").value;

  if(loz1 == loz2){
    document.getElementById("submitNewPassword").disabled = false;
  }
  else{
    document.getElementById("submitNewPassword").disabled = true;
  }
}

/*LETO ZIMA SKRIVANJE*/
$("#probniPeriodCard").hide();
$(function () {
  $("#odobriProbniPeriod").click(function () {
      if ($(this).is(":checked")) {
          $("#probniPeriodCard").show();
      } else {
          $("#probniPeriodCard").hide();
          document.getElementById("brojTrialMeseci").value = 0;
          document.getElementById("brojTrialDana").value = 0;
          $('#probniPeriod'). children("option:selected"). val() = 0;
      }
  });
});

var ukupnaCenaOpremeFinal = 0;
var ceneOpreme = [];
$(function () {
  $("#opremaJednokratno").click(function () {
      var brojRedova = ukupnoRedova;
      var ukupnaCenaOpreme = 0;
      var ugovornaObaveza = parseInt(document.getElementById('brojMeseciUgovora').value);
      var garantniRok = parseInt(document.getElementById('brojMeseciGr').value);
    //  if($('#opremaJednokratno:checked').length > 0){
        for(var i=0; i < brojRedova; i++){
          var cenaGR = parseInt(document.getElementById('cenaSenzoraUGr'+(i+1)).value);
          var cenaVanGR = parseInt(document.getElementById('cenaSenzoraVanGr'+(i+1)).value);
          var aktivni = parseInt(document.getElementById('brojAktivnih'+(i+1)).value);
          var neaktivni = parseInt(document.getElementById('brojNeaktivnih'+(i+1)).value);
          var ukupnaCenaOpremeGR = garantniRok*cenaGR*(aktivni+neaktivni);
          var ukupnaCenaOpremeVanGR = (ugovornaObaveza-garantniRok)*cenaVanGR*(aktivni+neaktivni);

          ukupnaCenaOpreme += ukupnaCenaOpremeGR+ukupnaCenaOpremeVanGR;
          document.getElementById('cenaSenzoraUGr'+(i+1)).value = 0;
          document.getElementById('cenaSenzoraVanGr'+(i+1)).value = 0;
        }
        //alert("Ukupna cena opreme:"+ukupnaCenaOpreme);
        ukupnaCenaOpremeFinal = ukupnaCenaOpreme;
        izracunajJednokratnuCenu();
        opremaJednokratnoPlacanje = !opremaJednokratnoPlacanje;
        izracunajCenu();
      //}
  });
});

var ukupnaCenaAplikacijeFinal = 0;
$(function () {
  $("#aplikacijaJednokratno").click(function () {
    var brojRedova = ukupnoRedova;
    var ukupnaCenaAplikacije = 0;
    var ugovornaObaveza = parseInt(document.getElementById('brojMeseciUgovora').value);
    var garantniRok = parseInt(document.getElementById('brojMeseciGr').value);
    for(var i=0; i < brojRedova; i++){
      var cenaGR = parseInt(document.getElementById('cenaLicenceUGr'+(i+1)).value);
      var cenaVanGR = parseInt(document.getElementById('cenaLicenceVanGr'+(i+1)).value);
      var aktivni = parseInt(document.getElementById('brojAktivnih'+(i+1)).value);
      var neaktivni = parseInt(document.getElementById('brojNeaktivnih'+(i+1)).value);
      var ukupnaCenaOpremeGR = garantniRok*cenaGR*(aktivni+neaktivni);
      var ukupnaCenaOpremeVanGR = (ugovornaObaveza-garantniRok)*cenaVanGR*(aktivni+neaktivni);

      ukupnaCenaAplikacije += ukupnaCenaOpremeGR+ukupnaCenaOpremeVanGR;
      document.getElementById('cenaLicenceUGr'+(i+1)).value = 0;
      document.getElementById('cenaLicenceVanGr'+(i+1)).value = 0;
    }
    ukupnaCenaAplikacijeFinal = ukupnaCenaAplikacije;
    izracunajJednokratnuCenu();
    aplikacijaJednokratnoPlacanje = !aplikacijaJednokratnoPlacanje;
    izracunajCenu()
  });
});

function izracunajJednokratnuCenu(){

  var trenutnaJeddnkratnaCena = parseInt(document.getElementById('jednokratnaCena').value);
  var jedCena = parseInt(ukupnaCenaOpremeFinal) + parseInt(ukupnaCenaAplikacijeFinal) + parseInt(document.getElementById('jednokratnaCena').value);
  document.getElementById('jednokratnaCenaFinal').value = jedCena;
}
