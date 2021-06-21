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

})(jQuery);




/*	This work is licensed under Creative Commons GNU LGPL License.

	License: http://creativecommons.org/licenses/LGPL/2.1/
   Version: 0.9
	Author:  Stefan Goessner/2006
	Web:     http://goessner.net/
*/
function xml2json(xml, tab) {
   var X = {
      toObj: function(xml) {
         var o = {};
         if (xml.nodeType==1) {   // element node ..
            if (xml.attributes.length)   // element with attributes  ..
               for (var i=0; i<xml.attributes.length; i++)
                  o[""+xml.attributes[i].nodeName] = (xml.attributes[i].nodeValue||"").toString();
            if (xml.firstChild) { // element has child nodes ..
               var textChild=0, cdataChild=0, hasElementChild=false;
               for (var n=xml.firstChild; n; n=n.nextSibling) {
                  if (n.nodeType==1) hasElementChild = true;
                  else if (n.nodeType==3 && n.nodeValue.match(/[^ \f\n\r\t\v]/)) textChild++; // non-whitespace text
                  else if (n.nodeType==4) cdataChild++; // cdata section node
               }
               if (hasElementChild) {
                  if (textChild < 2 && cdataChild < 2) { // structured element with evtl. a single text or/and cdata node ..
                     X.removeWhite(xml);
                     for (var n=xml.firstChild; n; n=n.nextSibling) {
                        if (n.nodeType == 3)  // text node
                           o["text"] = X.escape(n.nodeValue);
                        else if (n.nodeType == 4)  // cdata node
                           o["cdata"] = X.escape(n.nodeValue);
                        else if (o[n.nodeName]) {  // multiple occurence of element ..
                           if (o[n.nodeName] instanceof Array)
                              o[n.nodeName][o[n.nodeName].length] = X.toObj(n);
                           else
                              o[n.nodeName] = [o[n.nodeName], X.toObj(n)];
                        }
                        else  // first occurence of element..
                           o[n.nodeName] = X.toObj(n);
                     }
                  }
                  else { // mixed content
                     if (!xml.attributes.length)
                        o = X.escape(X.innerXml(xml));
                     else
                        o["text"] = X.escape(X.innerXml(xml));
                  }
               }
               else if (textChild) { // pure text
                  if (!xml.attributes.length)
                     o = X.escape(X.innerXml(xml));
                  else
                     o["text"] = X.escape(X.innerXml(xml));
               }
               else if (cdataChild) { // cdata
                  if (cdataChild > 1)
                     o = X.escape(X.innerXml(xml));
                  else
                     for (var n=xml.firstChild; n; n=n.nextSibling)
                        o["cdata"] = X.escape(n.nodeValue);
               }
            }
            if (!xml.attributes.length && !xml.firstChild) o = null;
         }
         else if (xml.nodeType==9) { // document.node
            o = X.toObj(xml.documentElement);
         }
         else
            alert("unhandled node type: " + xml.nodeType);
         return o;
      },
      toJson: function(o, name, ind) {
         var json = name ? ("\""+name+"\"") : "";
         if (o instanceof Array) {
            for (var i=0,n=o.length; i<n; i++)
               o[i] = X.toJson(o[i], "", ind+"\t");
            json += (name?":[":"[") + (o.length > 1 ? ("\n"+ind+"\t"+o.join(",\n"+ind+"\t")+"\n"+ind) : o.join("")) + "]";
         }
         else if (o == null)
            json += (name&&":") + "null";
         else if (typeof(o) == "object") {
            var arr = [];
            for (var m in o)
               arr[arr.length] = X.toJson(o[m], m, ind+"\t");
            json += (name?":{":"{") + (arr.length > 1 ? ("\n"+ind+"\t"+arr.join(",\n"+ind+"\t")+"\n"+ind) : arr.join("")) + "}";
         }
         else if (typeof(o) == "string")
            json += (name&&":") + "\"" + o.toString() + "\"";
         else
            json += (name&&":") + o.toString();
         return json;
      },
      innerXml: function(node) {
         var s = ""
         if ("innerHTML" in node)
            s = node.innerHTML;
         else {
            var asXml = function(n) {
               var s = "";
               if (n.nodeType == 1) {
                  s += "<" + n.nodeName;
                  for (var i=0; i<n.attributes.length;i++)
                     s += " " + n.attributes[i].nodeName + "=\"" + (n.attributes[i].nodeValue||"").toString() + "\"";
                  if (n.firstChild) {
                     s += ">";
                     for (var c=n.firstChild; c; c=c.nextSibling)
                        s += asXml(c);
                     s += "</"+n.nodeName+">";
                  }
                  else
                     s += "/>";
               }
               else if (n.nodeType == 3)
                  s += n.nodeValue;
               else if (n.nodeType == 4)
                  s += "<![CDATA[" + n.nodeValue + "]]>";
               return s;
            };
            for (var c=node.firstChild; c; c=c.nextSibling)
               s += asXml(c);
         }
         return s;
      },
      escape: function(txt) {
         return txt.replace(/[\\]/g, "\\\\")
                   .replace(/[\"]/g, '\\"')
                   .replace(/[\n]/g, '\\n')
                   .replace(/[\r]/g, '\\r');
      },
      removeWhite: function(e) {
         e.normalize();
         for (var n = e.firstChild; n; ) {
            if (n.nodeType == 3) {  // text node
               if (!n.nodeValue.match(/[^ \f\n\r\t\v]/)) { // pure whitespace text node
                  var nxt = n.nextSibling;
                  e.removeChild(n);
                  n = nxt;
               }
               else
                  n = n.nextSibling;
            }
            else if (n.nodeType == 1) {  // element node
               X.removeWhite(n);
               n = n.nextSibling;
            }
            else                      // any other node
               n = n.nextSibling;
         }
         return e;
      }
   };
   if (xml.nodeType == 9) // document node
      xml = xml.documentElement;
   var json = X.toJson(X.toObj(X.removeWhite(xml)), xml.nodeName, "\t");
   return "{\n" + tab + (tab ? json.replace(/\t/g, tab) : json.replace(/\t|\n/g, "")) + "\n}";
}



function parseXml(xml) {
   var dom = null;
   if (window.DOMParser) {
      try {
         dom = (new DOMParser()).parseFromString(xml, "text/xml");
      }
      catch (e) { dom = null; }
   }
   else if (window.ActiveXObject) {
      try {
         dom = new ActiveXObject('Microsoft.XMLDOM');
         dom.async = false;
         if (!dom.loadXML(xml)) // parse error ..

            window.alert(dom.parseError.reason + dom.parseError.srcText);
      }
      catch (e) { dom = null; }
   }
   else
      alert("cannot parse xml string!");
   return dom;
}


// End of use strict

function pronadjiKupca(){
  var sifraKorisnika = document.getElementById('sifraKorisnika').value;
  //alert(sifraKorisnika);
  $.ajax({
    type: 'GET',
    url: baseUrl+'ajax/finduser/'+sifraKorisnika,
    success: function (data) {
        //console.log(data.response);

        if(Object.keys(data.response).length == 1){
            //samo jedan zapis je dohvacen soap-om
            var naziv = data.response.io3TS1GetAccountDetailsOutputMessage.io3Account.outAccountName;
            var pib = data.response.io3TS1GetAccountDetailsOutputMessage.io3Account.outAccountPIB;
            var mb = data.response.io3TS1GetAccountDetailsOutputMessage.io3Account.outAccountMB;

            document.getElementById('userName').value = naziv;
            document.getElementById('inputPIB').value = pib;
            document.getElementById('inputMB').value = mb;
        }
        else{
            //postoji vise zapisa sa soap-a
            alert("Postoji vise zapisa!");
        }


    },
    error: function (xhr, status, error) {
        alert('Desila se greska, proveriti console.');
        console.log(xhr);
        console.log(status);
        console.log(error);
    }
});
}






















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
          $('#probniPeriod').children("option:selected").val() = 0;
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
