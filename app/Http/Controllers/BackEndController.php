<?php

namespace App\Http\Controllers;

use App\Models\UslugaSenzor;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;
use App\Models\Usluga;

use SoapClient;
use SoapHeader;

class BackEndController extends Controller
{

    private $user;

    public function login(Request $request)
    {
        $this->user = new User();
        $this->user->email = $request->get('email');
        $this->user->lozinka = $request->get('password');

        $userResult = $this->user->login();

        if ($userResult) {
            $request->session()->put('korisnik', $userResult);
            return redirect('/home');
        } else {
            return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('korisnik');
        $request->session()->flush();
        return redirect('/');
    }

    public function changePassword(Request $request)
    {
        $this->user = new User();
        $this->user->idKorisnikSistema = $request->session()->get('korisnik')->idKorisnikSistema;
        $this->user->lozinka = $request->get('newPassword');

        $result = $this->user->changePassword();

        return dd($result);
    }

    public function addUser(Request $request)
    {
        $this->user = new User();
        $this->user->ime = $request->get('ime');
        $this->user->prezime = $request->get('prezime');
        $this->user->email = $request->get('email');
        $this->user->lozinka = $request->get('password');

        $result = $this->user->insertNewUser();
        return redirect('/allusers');
    }

    public function addSensor(Request $request)
    {
        $senzor = new Sensor();

        $senzor->idKorisnikSistema = $request->session()->get('korisnik')->idKorisnikSistema;
        $senzor->naziv = $request->get('nazivSenzora');
        $senzor->opis = $request->get('opisSenzora');
        $senzor->komadaNaLageru = $request->get('komadaNaLageru');
        $senzor->nabavnaCena = $request->get('nabavnaCena');
        $senzor->prodajnaCena = $request->get('prodajnaCena');
        $senzor->cenaSenzoraGR = $request->get('cenaSenzoraGR');
        $senzor->cenaSenzoraVanGR = $request->get('cenaSenzoraVanGR');
        $senzor->cenaAppVanGR = $request->get('cenaSenzoraVanGR');
        $senzor->cenaAppGR = $request->get('cenaAppGR');
        $senzor->cenaAppVanGR = $request->get('cenaAppVanGR');
        $senzor->cenaServisaAktivan = $request->get('cenaServisaAktivan');
        $senzor->cenaServisaNeaktivan = $request->get('cenaServisaNeaktivan');
        $senzor->tehnickaPodrska = $request->get('cenaTehnickePodrske');
        $senzor->idKategorija = 1;

        if($senzor->insertSenzor()){
            return redirect('/allsensors');
        }
        else{
            return redirect()->back();
        }
    }

    public function findUser($userId)
    {

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://10.1.21.245:7810/services/GetAccountDetails?wsdl",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>
                    "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:shar=\"http://www.telekom.rs/EAI/SharedResources\" xmlns:get=\"http://www.telekom.rs/services/GetAccountDetails\">\n
      	  <soapenv:Header>\n
				<shar:Header>\n
					<shar:invokerId>10</shar:invokerId>\n
					<!--Optional:-->\n
					<shar:invokingSystem>BillingIoT</shar:invokingSystem>\n
					<!--Optional:-->\n
					<shar:invokingUser>test</shar:invokingUser>\n
					<!-- uneti user koji poziva WS -->\n
				</shar:Header>\n
			</soapenv:Header>\n
      	  <soapenv:Body>\n
      		    <get:TS1GetAccountDetailsInputMessage>\n
					<get:PIB>" . $userId . "</get:PIB>\n
				</get:TS1GetAccountDetailsInputMessage>\n
      	  </soapenv:Body>\n
        </soapenv:Envelope>",
                CURLOPT_HTTPHEADER => array("content-type: text/xml"),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

        } catch (Exception $e) {
            $response = $e->getMessage();
        }

        $res1 = str_replace(":", "", $response);
        $res2 = str_replace("\"", "'", $res1);
        $res3 = str_replace("%20", "", $res2);

        $xmlString = <<<XML
            $res3
        XML;

        $xml = simplexml_load_string($xmlString);

        $obj = [
            'response' => $xml->outBody
        ];
        return ($obj);
    }

    public function sensorDetails($sensorId)
    {
        $obj = [
            'response' => Sensor::getOne($sensorId)[0]
        ];
        return ($obj);
    }

    public function addService(Request $request){
        $usluga = new Usluga();
        $usluga->idKorisnikSistema = $request->session()->get('korisnik')->idKorisnikSistema;
        $usluga->pib = $request->get('PIB');
        $usluga->mb = $request->get('MB');
        $usluga->nazivFirmeDirekcije = $request->get('userName')." ".$request->get('direkcija');
        $usluga->naziv = $request->get('nazivUsluge');
        $usluga->jednokratnaCena = $request->get('jednokratnaCenaFinal');
        $usluga->placenaJednokratnaCena = false;
        $usluga->placeniUredjajiJednokratno = $request->get('opremaJednokratno') === "on";
        $usluga->placenaAppJednokratno = $request->get('aplikacijaJednokratno') === "on";
        $usluga->ugovornaObaveza = intval($request->get('brojMeseciUgovora'));
        $usluga->probniPeriod = $request->get('probniPeriod') === "1";
        $usluga->probniPeriodMeseci = intval($request->get('brojTrialMeseci'));
        $usluga->probniPeriodDana = intval($request->get('brojTrialDana'));
        $usluga->datumPotpisaUgovora = date('Y-m-d H:i:s');
        $usluga->datumPocetkaNaplate = date('Y-m-d H:i:s',strtotime($request->get('istekProbnogPerioda')));
        $usluga->datumKrajNaplate = date('Y-m-d H:i:s',strtotime($request->get('istekProbnogPerioda')."+".$usluga->ugovornaObaveza." months"));
        $usluga->datumAktivacijeSenzora = date('Y-m-d H:i:s',strtotime($request->get('istekProbnogPerioda')));
        $usluga->garantniRok = intval($request->get('brojMeseciGr'));
        $usluga->istekaoGarantniRok = false;

        //return dd($request->all());

        $idUsluga = $usluga->insertId();

        $redoviZaUnos = explode(',',$request->get('idRowZaUnos'));

        //return dd($redoviZaUnos);
        if($idUsluga != 0){
            //uspenan unos u tabelu usluga
            $uspesanUnosUslugaSenzor = true;
            for($i=0; $i < count($redoviZaUnos); $i++ ){
                //unos novih senzor->uslluga zapisa
                $idReda = $redoviZaUnos[$i]; //id reda za dohvatanje
                $uslugaSenzor = new UslugaSenzor();
                $uslugaSenzor->idUsluga = $idUsluga;
                $uslugaSenzor->idSenzor = $request->get('tipSenzora'.$idReda);
                $uslugaSenzor->nabavnaCena = $request->get('nabavnaCena'.$idReda);
                $uslugaSenzor->cenaSenzoraGR = $request->get('cenaSenzoraUGr'.$idReda);
                $uslugaSenzor->cenaSenzoraVanGR = $request->get('cenaSenzoraVanGr'.$idReda);
                $uslugaSenzor->cenaAppGR = $request->get('cenaLicenceUGr'.$idReda);
                $uslugaSenzor->cenaAppVanGR = $request->get('cenaLicenceVanGr'.$idReda);
                $uslugaSenzor->cenaServisaAktivnih = $request->get('cenaServisaZaAktivne'.$idReda);
                $uslugaSenzor->cenaServisaNeaktivnih = $request->get('cenaServisaZaNeaktivne'.$idReda);
                $uslugaSenzor->cenaTehnickePodrske = $request->get('cenaTehnickePodrske'.$idReda);
                $uslugaSenzor->brojAktivnihSenzora = $request->get('brojAktivnih'.$idReda);
                $uslugaSenzor->brojPovremenoNeaktivnih = $request->get('brojNeaktivnih'.$idReda);
                $uslugaSenzor->ukupanBrojSenzora = $uslugaSenzor->brojAktivnihSenzora + $uslugaSenzor->brojPovremenoNeaktivnih;
                $uslugaSenzor->brojNeaktivnihMeseci = count($request->get('neaktivniMeseci'.$idReda));
                $nizNeaktivnihMeseci = implode('|',$request->get('neaktivniMeseci'.$idReda));
                $uslugaSenzor->neaktivniMeseci = $nizNeaktivnihMeseci;
                //smanjivanje broja na lageru
                Sensor::smanjiLager($uslugaSenzor->idSenzor, $uslugaSenzor->ukupanBrojSenzora);

                $insertUslugaSenzor = $uslugaSenzor->insert();

                if($insertUslugaSenzor != 1){
                    $uspesanUnosUslugaSenzor = false;
                }
            }

            if($uspesanUnosUslugaSenzor){
                //uspesan unos UslugSenzor
                return redirect('/home');
            }
            else{
                //neuspesn unos uslugaSenzor
                return redirect()->back();
            }
        }
        else {
            //desila se greska pri unosu u tabelu usluga
            return redirect()->back();
        }
        return redirect()->back();
    }
}
