<?php

namespace App\Http\Controllers;

use App\Exports\UgovorExport;
use App\Http\Requests\EditRequest;
use App\Http\Requests\InsertRequest;
use App\Http\Requests\LoginRequest;
use App\Models\KomercijalniUslovi;
use App\Models\LokacijaApp;
use App\Models\NazivServisa;
use App\Models\Partner;
use App\Models\PartnerUgovor;
use App\Models\StavkaFakture;
use App\Models\Tehnologije;
use App\Models\TehnologijeUgovor;
use App\Models\TipServisa;
use App\Models\TipUgovora;
use App\Models\Ugovor;
use App\Models\User;
use App\Models\VrstaSenzora;
use App\Models\VrstaSenzoraUgovor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use SoapClient;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;

class BackendController extends Controller
{
    public function login(LoginRequest $request){
        //proveriti da li je korisnik u lokalnoj bazi -> korinsik
        //ako nije onda traziti na ldap-u
        //ako ga nema ni tamo izbaciti error

        $validated = $request->validated();

        if(Auth::attempt(['email' => $validated['email'], 'password' => $validated['lozinka'], 'deaktiviran' => false])){
            return redirect('/home');
        }
        else{
            //proveriti na ldap-u
            return redirect('/')->withErrors('Korisnik nije pronadjen!');
        }
    }
    public function logout(){
        Session::flush();
        return redirect('/');
    }

    private function removeSC($text){
        $search = array("Š","š","Č","č","Ć","ć","Đ","đ","Ž","ž");
        $replace = array("S","s","C","c","C","c","D","d","Z","z");
        return str_replace($search,$replace,$text);
    }
    private function dohvatiIdLinije($resp){
        preg_match('/<ns3:linijaID>(.*?)<\/ns3:linijaID>/', $resp, $match);
        return $match[1];
    }
    private function modifyIoTService($data, $function){
        $dom = new \DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;

        $envelope = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/','Envelope');
        $dom->appendChild($envelope);

        $body = $dom->createElement('Body');
        $envelope->appendChild($body);

        $createIot = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/',$function);
        $body->appendChild($createIot);

        $commonDescription = $dom->createElementNS('', 'IoTServiceCommonDescription');
        $createIot->appendChild($commonDescription);

        $profileCode = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'profileCode',$data['zbirni_racun']);
        $commonDescription->appendChild($profileCode);

        $connPlan = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'connectivityPlan',$data['connectivity_plan']);
        $commonDescription->appendChild($connPlan);

        $agreName = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'agreementName',$this->removeSC($data['naziv_ugovra']));
        $commonDescription->appendChild($agreName);

        $agreId = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'agreementId',$data['broj_ugovora']);
        $commonDescription->appendChild($agreId);

        $agreType = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'agreementType',$this->removeSC($data['tip_ugovora']));
        $commonDescription->appendChild($agreType);

        $commitPeriod = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'commitmentPeriod',$data['ugovorna_obaveza']);
        $commonDescription->appendChild($commitPeriod);

        $date = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'serviceDate',$data['datum_potpisivanja']);
        $commonDescription->appendChild($date);

        $serviceName = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'serviceName',$this->removeSC($data['naziv_servisa']));
        $commonDescription->appendChild($serviceName);

        $serviceType = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'serviceType',$this->removeSC($data['tip_servisa']));
        $commonDescription->appendChild($serviceType);

        $partnerText = "";
        $partnerIteration = 1;
        foreach ($data['partneri'] as $partner){
            if($partnerIteration == count($data['partneri'])){
                $partnerText.= $partner;
            }
            else{
                $partnerText.= $partner.";";
            }
            $partnerIteration ++;
        }
        $partnerDom = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'partnerName',$this->removeSC($partnerText));
        $commonDescription->appendChild($partnerDom);

        $tehnologijaText = "";
        $tehnologijeIteration = 1;
        foreach ($data['tehnologije'] as $tehnologija){
            if($tehnologijeIteration == count($data['tehnologije'])){
                $tehnologijaText.= $tehnologija;
            }
            else{
                $tehnologijaText.= $tehnologija.";";
            }
            $tehnologijeIteration ++;
        }
        $techDom = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'technologyType',$this->removeSC($tehnologijaText));
        $commonDescription->appendChild($techDom);

        $senzoriText = "";
        $senzoriIteration = 1;
        foreach ($data['senzori'] as $senzor){
            if($senzoriIteration == count($data['senzori'])){
                $senzoriText .= $senzor;
            }
            else{
                $senzoriText .= $senzor.";";
            }
            $senzoriIteration++;
        }
        $senzorDom = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'sensorType',$this->removeSC($senzoriText));
        $commonDescription->appendChild($senzorDom);

        $appLocation = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'applicationLocation',$this->removeSC($data['lokacija_aplikacije']));
        $commonDescription->appendChild($appLocation);

        if(count($data['stavke_fakture']) > 0){
            foreach($data['stavke_fakture'] as $stavka){
                $status = "";
                if($stavka['status'] == 1){$status = "Aktivni";}
                if($stavka['status'] == 2){$status = "Prijavljeni";}
                if($stavka['status'] == 3){$status = "N/A";}

                $sim = $stavka["sim"] == 1 ? "true" : "false";
                $uredjaj = $stavka["uredjaj"] == 1 ? "true" : "false";


                $detailedDescriptionName =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionName);
                $nameNaziv = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Stavka fakture");
                $valueNaziv = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $this->removeSC($stavka["naziv"]));
                $detailedDescriptionName->appendChild($nameNaziv);
                $detailedDescriptionName->appendChild($valueNaziv);

                $detailedDescriptionPocetak =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionPocetak);
                $namePocetak = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Pocetak");
                $valuePocetak = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["pocetak"]);
                $detailedDescriptionPocetak->appendChild($namePocetak);
                $detailedDescriptionPocetak->appendChild($valuePocetak);

                $detailedDescriptionKraj =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionKraj);
                $nameKraj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Kraj");
                $valueKraj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["kraj"]);
                $detailedDescriptionKraj->appendChild($nameKraj);
                $detailedDescriptionKraj->appendChild($valueKraj);

                $detailedDescriptionNaknada =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionNaknada);
                $nameNaknada = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Iznos naknade");
                $valueNaknada = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["iznos_naknade"]);
                $detailedDescriptionNaknada->appendChild($nameNaknada);
                $detailedDescriptionNaknada->appendChild($valueNaknada);

                $detailedDescriptionStatus =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionStatus);
                $nameStatus = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Status");
                $valueStatus = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $status);
                $detailedDescriptionStatus->appendChild($nameStatus);
                $detailedDescriptionStatus->appendChild($valueStatus);

                $detailedDescriptionMin =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionMin);
                $nameMin = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Min");
                $valueMin = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["min"]);
                $detailedDescriptionMin->appendChild($nameMin);
                $detailedDescriptionMin->appendChild($valueMin);

                $detailedDescriptionMax =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionMax);
                $nameMax = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Max");
                $valueMax = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["max"]);
                $detailedDescriptionMax->appendChild($nameMax);
                $detailedDescriptionMax->appendChild($valueMax);

                $detailedDescriptionSim =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionSim);
                $nameSim = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Sim");
                $valueSim = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $sim);
                $detailedDescriptionSim->appendChild($nameSim);
                $detailedDescriptionSim->appendChild($valueSim);

                $detailedDescriptionUredjaj =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionUredjaj);
                $nameUredjaj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Uredjaj");
                $valueUredjaj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $uredjaj);
                $detailedDescriptionUredjaj->appendChild($nameUredjaj);
                $detailedDescriptionUredjaj->appendChild($valueUredjaj);
            }
        }

        //return dd($dom->saveXML());

        $url = "http://10.1.32.179/CloudWebService/CloudWebService";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/soap+xml",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dom->saveXML());
        $resp = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        //return dd($resp);

        //return dd($this->dohvatiIdLinije($resp));




        if($status === 200){
            Log::error("odgovor na http_response_200 => ".$resp);
            return true;
        }
        else{
            Log::error("Greska pri editu ugovora soap-request-1 => ".$resp);
            return false;
        }

    }
    private function createIoTService($data, $function, $id_ugovor, $create = true){
        $dom = new \DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;

        $envelope = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/','Envelope');
        $dom->appendChild($envelope);

        $body = $dom->createElement('Body');
        $envelope->appendChild($body);

        $createIot = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/',$function);
        $body->appendChild($createIot);

        $commonDescription = $dom->createElementNS('', 'IoTServiceCommonDescription');
        $createIot->appendChild($commonDescription);

        $profileCode = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'profileCode',$data['zbirni_racun']);
        $commonDescription->appendChild($profileCode);

        $connPlan = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'connectivityPlan',$data['connectivity_plan']);
        $commonDescription->appendChild($connPlan);

        $agreName = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'agreementName',$this->removeSC($data['naziv_ugovra']));
        $commonDescription->appendChild($agreName);

        $agreId = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'agreementId',$data['broj_ugovora']);
        $commonDescription->appendChild($agreId);

        $agreType = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'agreementType',$this->removeSC($data['tip_ugovora']));
        $commonDescription->appendChild($agreType);

        $commitPeriod = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'commitmentPeriod',$data['ugovorna_obaveza']);
        $commonDescription->appendChild($commitPeriod);

        $date = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'serviceDate',$data['datum_potpisivanja']);
        $commonDescription->appendChild($date);

        $serviceName = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'serviceName',$this->removeSC($data['naziv_servisa']));
        $commonDescription->appendChild($serviceName);

        $serviceType = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'serviceType',$this->removeSC($data['tip_servisa']));
        $commonDescription->appendChild($serviceType);

        $partnerText = "";
        $partnerIteration = 1;
        foreach ($data['partneri'] as $partner){
            if($partnerIteration == count($data['partneri'])){
                $partnerText.= $partner;
            }
            else{
                $partnerText.= $partner.";";
            }
            $partnerIteration ++;
        }
        $partnerDom = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'partnerName',$this->removeSC($partnerText));
        $commonDescription->appendChild($partnerDom);

        $tehnologijaText = "";
        $tehnologijeIteration = 1;
        foreach ($data['tehnologije'] as $tehnologija){
            if($tehnologijeIteration == count($data['tehnologije'])){
                $tehnologijaText.= $tehnologija;
            }
            else{
                $tehnologijaText.= $tehnologija.";";
            }
            $tehnologijeIteration ++;
        }
        $techDom = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'technologyType',$this->removeSC($tehnologijaText));
        $commonDescription->appendChild($techDom);

        $senzoriText = "";
        $senzoriIteration = 1;
        foreach ($data['senzori'] as $senzor){
            if($senzoriIteration == count($data['senzori'])){
                $senzoriText .= $senzor;
            }
            else{
                $senzoriText .= $senzor.";";
            }
            $senzoriIteration++;
        }
        $senzorDom = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'sensorType',$this->removeSC($senzoriText));
        $commonDescription->appendChild($senzorDom);

        $appLocation = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'applicationLocation',$this->removeSC($data['lokacija_aplikacije']));
        $commonDescription->appendChild($appLocation);

        if(count($data['stavke_fakture']) > 0){
            foreach($data['stavke_fakture'] as $stavka){
                $status = "";
                if($stavka['status'] == 1){$status = "Aktivni";}
                if($stavka['status'] == 2){$status = "Prijavljeni";}
                if($stavka['status'] == 3){$status = "N/A";}

                $sim = $stavka["sim"] == 1 ? "true" : "false";
                $uredjaj = $stavka["uredjaj"] == 1 ? "true" : "false";


                $detailedDescriptionName =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionName);
                $nameNaziv = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Stavka fakture");
                $valueNaziv = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $this->removeSC($stavka["naziv"]));
                $detailedDescriptionName->appendChild($nameNaziv);
                $detailedDescriptionName->appendChild($valueNaziv);

                $detailedDescriptionPocetak =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionPocetak);
                $namePocetak = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Pocetak");
                $valuePocetak = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["pocetak"]);
                $detailedDescriptionPocetak->appendChild($namePocetak);
                $detailedDescriptionPocetak->appendChild($valuePocetak);

                $detailedDescriptionKraj =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionKraj);
                $nameKraj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Kraj");
                $valueKraj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["kraj"]);
                $detailedDescriptionKraj->appendChild($nameKraj);
                $detailedDescriptionKraj->appendChild($valueKraj);

                $detailedDescriptionNaknada =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionNaknada);
                $nameNaknada = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Iznos naknade");
                $valueNaknada = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["iznos_naknade"]);
                $detailedDescriptionNaknada->appendChild($nameNaknada);
                $detailedDescriptionNaknada->appendChild($valueNaknada);

                $detailedDescriptionStatus =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionStatus);
                $nameStatus = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Status");
                $valueStatus = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $status);
                $detailedDescriptionStatus->appendChild($nameStatus);
                $detailedDescriptionStatus->appendChild($valueStatus);

                $detailedDescriptionMin =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionMin);
                $nameMin = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Min");
                $valueMin = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["min"]);
                $detailedDescriptionMin->appendChild($nameMin);
                $detailedDescriptionMin->appendChild($valueMin);

                $detailedDescriptionMax =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionMax);
                $nameMax = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Max");
                $valueMax = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $stavka["max"]);
                $detailedDescriptionMax->appendChild($nameMax);
                $detailedDescriptionMax->appendChild($valueMax);

                $detailedDescriptionSim =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionSim);
                $nameSim = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Sim");
                $valueSim = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $sim);
                $detailedDescriptionSim->appendChild($nameSim);
                $detailedDescriptionSim->appendChild($valueSim);

                $detailedDescriptionUredjaj =  $dom->createElementNS('', 'IoTServiceDetailedDescription');
                $createIot->appendChild($detailedDescriptionUredjaj);
                $nameUredjaj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'name', $stavka["id_stavka"]."_Uredjaj");
                $valueUredjaj = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/Entities/', 'value', $uredjaj);
                $detailedDescriptionUredjaj->appendChild($nameUredjaj);
                $detailedDescriptionUredjaj->appendChild($valueUredjaj);
            }
        }

        //return dd($dom->saveXML());

        $url = "http://10.1.32.179/CloudWebService/CloudWebService";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/soap+xml",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dom->saveXML());
        $resp = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        //return dd($resp);

        //return dd($this->dohvatiIdLinije($resp));




        if($status === 200){
            Log::error("odgovor na http_response_200 => ".$resp);
            if($create){
                // radi se dohvatanje linije
                if(str_contains($resp, '<ns3:linijaID>')){
                    $id_linija = $this->dohvatiIdLinije($resp);
                    try{
                        Ugovor::whereId($id_ugovor)->update([
                            'id_linije' => $id_linija
                        ]);
                    }
                    catch (\Exception $exception){
                        Log::error("Greska pri dodavanju id linije ugovoru!".$exception->getMessage());
                        return false;
                    }
                }
                else{
                    return false;
                }
            }

            return true;
        }
        else{
            Log::error("Greska pri dodavanju novog ugovora soap-request-1 => ".$resp);
            return false;
        }

    }
    private function deleteIoTService($connPlan, $date){
        $dom = new \DOMDocument('1.0','UTF-8');
        $dom->formatOutput = true;

        $envelope = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/','Envelope');
        $dom->appendChild($envelope);

        $body = $dom->createElement('Body');
        $envelope->appendChild($body);

        $deleteIot = $dom->createElementNS('http://www.telekomsrbija.com/ws/CloudWebService/','DeleteIoTService');
        $body->appendChild($deleteIot);

        $connectivity_plan = $dom->createElementNS('', 'conncectivityPlan',$connPlan);
        $deleteIot->appendChild($connectivity_plan);

        $service_date = $dom->createElementNS('', 'serviceDate',date("c", strtotime($date)));
        $deleteIot->appendChild($service_date);

        //return $dom->saveXML();

        $url = "http://10.1.32.179/CloudWebService/CloudWebService";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/soap+xml",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $dom->saveXML());
        $resp = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        //return dd($resp);

        Log::error("poslato ka tisu => ".$dom->saveXML());
        if($status === 200){
            Log::error("odgovor na http_response_200 => ".$resp);
            return true;
        }
        else{
            Log::error("Greska pri dodavanju novog ugovora soap-request-1 => ".$resp);
            return false;
        }
    }
    private function brisanjeNeuspelogUgovora($id){
        try{
            KomercijalniUslovi::whereIdUgovor($id)->delete();
            VrstaSenzoraUgovor::whereIdUgovor($id)->delete();
            PartnerUgovor::whereIdUgovor($id)->delete();
            TehnologijeUgovor::whereIdUgovor($id)->delete();
            Ugovor::whereId($id)->delete();
        }
        catch (\Exception $exception){
            Log::error("Greska pri brisanju podataka za neuspeli ugovor => ".$exception->getMessage());
        }
    }

    public function addNewContract(Request $request){
        //return dd($request->all());
        $request->validate([
            'id_kupac' => 'required',
            'connectivity_plan' => 'required',
            'naziv_kupac' => 'required',
            'pib' => 'required',
            'mb' => 'required',
            'telefon' => 'required',
            'kam' => 'required',
            'segment' => 'required',

            "partner" => "required",
            "naziv_ugovora" => "required",
            "tip_servisa" => "required",
            "naziv_servisa" => "required",
            "tip_ugovora" => "required",
            "broj_ugovora" => "required",
            "datum" => "required",
            "datum_data" => "required",
            "zbirni_racun" => "required",
            "uo" => "required",
            "tip_tehnologije" =>"required",
            "vrsta_senzora" => "required",
            "lokacija_app" => "required"
        ]);
        $id_ugovor = 0;

        $soapData = [
            'zbirni_racun' => $request->input('zbirni_racun'),
            'connectivity_plan' => $request->input('connectivity_plan'),
            'naziv_ugovra' => $request->input('naziv_ugovora'),
            'broj_ugovora' => $request->input('broj_ugovora'),
            'ugovorna_obaveza' => $request->input('uo'),
            'datum_potpisivanja' => date('c', strtotime($request->input('datum_data'))),
            'tip_ugovora' => TipUgovora::whereId($request->input('tip_ugovora'))->first()->naziv,
            'tip_servisa' => TipServisa::whereId($request->input('tip_servisa'))->first()->naziv,
            'lokacija_aplikacije' => LokacijaApp::whereId($request->input('lokacija_app'))->first()->naziv,
            'naziv_servisa' => NazivServisa::whereId($request->input('naziv_servisa'))->first()->naziv,
            'pratneri' => [],
            'tehnologije' => [],
            'senzori' => [],
            'stavke_fakture' => [],
        ];

        try {
            $id_ugovor = DB::table('ugovor')->
                insertGetId([
                    'id_user' => Auth::user()->id,
                    'id_tip_ugovora' => $request->input('tip_ugovora'),
                    'id_tip_servisa' => $request->input('tip_servisa'),
                    'id_naziv_servisa' => $request->input('naziv_servisa'),
                    'id_lokacija_app' => $request->input('lokacija_app'),
                    'connectivity_plan' => $request->input('connectivity_plan'),
                    'ip_adresa' => $request->input('ip_adresa'),
                    'naziv_servera' => $request->input('naziv_servera'),
                    'naziv_ugovra' => $request->input('naziv_ugovora'),
                    'broj_ugovora' => $request->input('broj_ugovora'),
                    'datum_potpisivanja' => date('Y-m-d H:i:s', strtotime($request->input('datum_data'))),
                    'ugovorna_obaveza' => $request->input('uo'),
                    'zbirni_racun' => $request->input('zbirni_racun'),
                    'napomena' => $request->input('napomena'),
                    'id_kupac' => $request->input('id_kupac'),
                    'naziv_kupac' => $request->input('naziv_kupac'),
                    'pib' => $request->input('pib'),
                    'mb' => $request->input('mb'),
                    'segment' => $request->input('segment'),
                    'email' => $request->input('email'),
                    'telefon' => $request->input('telefon'),
                    'kam' => $request->input('kam'),
                    'dekativiran' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri dodavanju novog ugovora insert-ugovor-1 => ".$exception->getMessage());
            return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-ugovor-1"]);
        }

        if($id_ugovor > 0){

            // treba popuniti niz sa podacima kojih ima vise => partneri, tehnologije, vrste senzora, za sad ce biti to vrednost test
            // + komercijalni uslovi kako se dodaju


            foreach ($request->input('tip_tehnologije') as $id_tip_tehnologije){
                try{
                    DB::table('tehnologije_ugovor')
                        ->insert([
                            'id_tehnologije' => $id_tip_tehnologije,
                            'id_ugovor' => $id_ugovor
                        ]);
                    $soapData['tehnologije'][] = Tehnologije::whereId($id_tip_tehnologije)->first()->naziv;
                }
                catch (\Exception $exception){
                    Log::error("Greska pri dodavanju novog ugovora insert-ugovor-2 => ".$exception->getMessage());
                    $this->brisanjeNeuspelogUgovora($id_ugovor);
                    return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-ugovor-2"]);
                }
            }
            foreach ($request->input('partner') as $id_partner){
                try{
                    DB::table('partner_ugovor')
                        ->insert([
                            'id_partner' => $id_partner,
                            'id_ugovor' => $id_ugovor
                        ]);
                    $soapData['partneri'][] = Partner::whereId($id_partner)->first()->naziv;
                }
                catch (\Exception $exception){
                    Log::error("Greska pri dodavanju novog ugovora insert-ugovor-3 => ".$exception->getMessage());
                    $this->brisanjeNeuspelogUgovora($id_ugovor);
                    return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-ugovor-3"]);
                }
            }
            foreach ($request->input('vrsta_senzora') as $id_vrsta_senzora){
                try{
                    DB::table('vrsta_senzora_ugovor')
                        ->insert([
                            'id_vrsta_senzora' => $id_vrsta_senzora,
                            'id_ugovor' => $id_ugovor
                        ]);
                    $soapData['senzori'][] = VrstaSenzora::whereId($id_vrsta_senzora)->first()->naziv;
                }
                catch (\Exception $exception){
                    Log::error("Greska pri dodavanju novog ugovora insert-ugovor-4 => ".$exception->getMessage());
                    $this->brisanjeNeuspelogUgovora($id_ugovor);
                    return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-ugovor-4 "]);
                }
            }
            if($request->input('aktivne_stavke') != null){
                //ima bar jedan komercijalni uslov
                $stavke = explode(",", $request->input('aktivne_stavke'));
                foreach ($stavke as $stavka){
                    if($request->input('stavka_fakture_'.$stavka) != null){
                        try{
                            $vrsta_senzora = intval(explode('|',$request->input('stavka_fakture_'.$stavka))[0]);
                            $id_stavka_forma = intval(explode('|',$request->input('stavka_fakture_'.$stavka))[1]);
                            $id_stavka_fakture = DB::table('komercijalni_uslovi')
                                ->insertGetId([
                                    'id_user' => Auth::user()->id,
                                    'id_ugovor' => $id_ugovor,
                                    'id_vrsta_senzora' => $vrsta_senzora == 0 ? null : $vrsta_senzora,
                                    'id_stavka_fakture' => $id_stavka_forma,
                                    'datum_pocetak' => date('Y-m-d', strtotime($request->input('datum_pocetak_'.$stavka.'_data'))),
                                    'datum_kraj' => date('Y-m-d', strtotime($request->input('datum_kraj_'.$stavka.'_data'))),
                                    'naknada' => floatval($request->input('naknada_'.$stavka)),
                                    'status' => $request->input('status_'.$stavka),
                                    'min' => intval($request->input('min_'.$stavka)),
                                    'max' => intval($request->input('max_'.$stavka)),
                                    'obrisana' => false,
                                    'uredjaj' => $request->input('uredjaj_' . $stavka) !== null,
                                    'sim_kartica' => $request->input('sim_'.$stavka) !== null,
                                    'id_user_obrisao' => 0
                                ]);

                            $naizvKomUslov = "";
                            if($vrsta_senzora !== 0){
                                $naizvKomUslov = StavkaFakture::whereId($id_stavka_forma)->first()->naziv."".VrstaSenzora::whereId($vrsta_senzora)->first()->naziv;
                            }
                            else{
                                $naizvKomUslov = StavkaFakture::whereId($id_stavka_forma)->first()->naziv;
                            }

                            $soapData['stavke_fakture'][]=[
                                'id_stavka' => $id_stavka_fakture,
                                'naziv' => $naizvKomUslov,
                                'pocetak' => date('c', strtotime($request->input('datum_pocetak_'.$stavka.'_data'))),
                                'kraj' => date('c', strtotime($request->input('datum_kraj_'.$stavka.'_data'))),
                                'iznos_naknade' => floatval($request->input('naknada_'.$stavka)),
                                'status' => $request->input('status_'.$stavka),
                                'min' => intval($request->input('min_'.$stavka)),
                                'max' => intval($request->input('max_'.$stavka)),
                                'sim' => $request->input('sim_'.$stavka) !== null,
                                'uredjaj' => $request->input('uredjaj_' . $stavka) !== null
                            ];
                        }
                        catch (\Exception $exception){
                            Log::error("Greska pri dodavanju novog ugovora insert-ugovor-5 => ".$exception->getMessage());
                            $this->brisanjeNeuspelogUgovora($id_ugovor);
                            return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-ugovor-5"]);
                        }
                    }
                }
            }

            //echo ("Create iot service funkcija");
            //return dd($this->createIoTService($soapData));   //2109697.024

            if($this->createIoTService($soapData, 'CreateIoTService', $id_ugovor)){
                return redirect('/home');
            }
            else{
                //obrisati sve vezano za id tog ugovora
                $this->brisanjeNeuspelogUgovora($id_ugovor);
                return redirect()->back()->with(['greska' => "Desila se greska! Id greske : soap-request-1"]);
            }

        }
        else{
            Log::error("Greska pri dodavanju novog ugovora insert-ugovor-1");
            $this->brisanjeNeuspelogUgovora($id_ugovor);
            return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-ugovor-1"]);
        }

    }
    public function editContract(Request $request){
        //return dd($request->all());
        $partneri_array = explode(';',$request->input('partneri_naziv'));
        $tehnologije_array = explode(';',$request->input('tehnologije_naziv'));
        $senzori_array = explode(';',$request->input('senzori_naziv'));
        $soapData = [
            'zbirni_racun' => $request->input('zbirni_racun'),
            'connectivity_plan' => $request->input('connectivity_plan'),
            'naziv_ugovra' => $request->input('naziv_ugovora'),
            'broj_ugovora' => $request->input('broj_ugovora'),
            'ugovorna_obaveza' => $request->input('ugovorna_obaveza'),
            'datum_potpisivanja' => date('c', strtotime($request->input('datum_potpisa'))),
            'tip_ugovora' => TipUgovora::whereId($request->input('id_tip_ugovora'))->first()->naziv,
            'tip_servisa' => TipServisa::whereId($request->input('id_tip_servisa'))->first()->naziv,
            'lokacija_aplikacije' => LokacijaApp::whereId($request->input('id_lokacija_aplikacije'))->first()->naziv,
            'naziv_servisa' => NazivServisa::whereId($request->input('id_naziv_servisa'))->first()->naziv,
            'partneri' => $partneri_array,
            'tehnologije' => $tehnologije_array,
            'senzori' => $senzori_array,
            'stavke_fakture' => [],
        ];
        //return dd($soapData);
        //postoji vec ugovor, ne dodaje se novi samo komercijalni uslovi
        if($request->input('aktivne_stavke') != null){
            //ima bar jedan komercijalni uslov
            $stavke = explode(",", $request->input('aktivne_stavke'));
            $id_ugovor = intval($request->input('id_ugovor'));

            foreach ($stavke as $stavka){
                if($request->input('id_komercijalni_uslov_'.$stavka) !== null){
                    //radi se update komercijalnog uslova
                    try {
                        $id_kom_uslov = intval($request->input('id_komercijalni_uslov_'.$stavka));
                        DB::table('komercijalni_uslovi')
                            ->where('id', '=', $id_kom_uslov)
                            ->update([
                                'datum_kraj' => date('Y-m-d', strtotime($request->input("datum_kraj_" . $stavka . "_data")))
                            ]);

                        $soapData['stavke_fakture'][] = [
                            'id_stavka' => $request->input('id_komercijalni_uslov_'.$stavka),
                            'naziv' => $request->input('stavka_fakture_'.$stavka),
                            'pocetak' => date('c', strtotime($request->input('datum_pocetak_hidden_'.$stavka))),
                            'kraj' => date('c', strtotime($request->input('datum_kraj_'.$stavka.'_data'))),
                            'iznos_naknade' => floatval($request->input('naknada_'.$stavka)),
                            'status' => $request->input('status_'.$stavka),
                            'min' => intval($request->input('min_'.$stavka)),
                            'max' => intval($request->input('max_'.$stavka)),
                            'sim' => $request->input('sim_'.$stavka) !== null,
                            'uredjaj' => $request->input('uredjaj_' . $stavka) !== null
                        ];

                    }
                    catch (\Exception $exception){
                        Log::error("Greska pri editu postojecih komercijalnih uslova edit-ugovor-1 => ".$exception->getMessage());
                        return redirect()->back()->with(['greska' => "Desila se greska! Id greske : edit-ugovor-1"]);
                    }
                }
                else{
                    //radi se insert novog kom uslova

                    try{
                        $vrsta_senzora = intval(explode('|',$request->input('stavka_fakture_'.$stavka))[0]);
                        $id_stavka = intval(explode('|',$request->input('stavka_fakture_'.$stavka))[1]);
                        $id_kom_uslov = DB::table('komercijalni_uslovi')
                            ->insertGetId([
                                'id_user' => Auth::user()->id,
                                'id_ugovor' => $id_ugovor,
                                'id_vrsta_senzora' => $vrsta_senzora == 0 ? null : $vrsta_senzora,
                                'id_stavka_fakture' => $id_stavka,
                                'datum_pocetak' => date('Y-m-d', strtotime($request->input('datum_pocetak_'.$stavka.'_data'))),
                                'datum_kraj' => date('Y-m-d', strtotime($request->input('datum_kraj_'.$stavka.'_data'))),
                                'naknada' => floatval($request->input('naknada_'.$stavka)),
                                'status' => $request->input('status_'.$stavka),
                                'min' => intval($request->input('min_'.$stavka)),
                                'max' => intval($request->input('max_'.$stavka)),
                                'obrisana' => false,
                                'uredjaj' => $request->input('uredjaj_' . $stavka) !== null,
                                'sim_kartica' => $request->input('sim_'.$stavka) !== null,
                                'id_user_obrisao' => 0
                            ]);

                        $naizvKomUslov = "";
                        if($vrsta_senzora !== 0){
                            $naizvKomUslov = StavkaFakture::whereId($id_stavka)->first()->naziv."".VrstaSenzora::whereId($vrsta_senzora)->first()->naziv;
                        }
                        else{
                            $naizvKomUslov = StavkaFakture::whereId($id_stavka)->first()->naziv;
                        }

                        $soapData['stavke_fakture'][] = [
                            'id_stavka' => $id_kom_uslov,
                            'naziv' => $naizvKomUslov,
                            'pocetak' => date('c', strtotime($request->input('datum_pocetak_'.$stavka.'_data'))),
                            'kraj' => date('c', strtotime($request->input('datum_kraj_'.$stavka.'_data'))),
                            'iznos_naknade' => floatval($request->input('naknada_'.$stavka)),
                            'status' => $request->input('status_'.$stavka),
                            'min' => intval($request->input('min_'.$stavka)),
                            'max' => intval($request->input('max_'.$stavka)),
                            'sim' => $request->input('sim_'.$stavka) !== null,
                            'uredjaj' => $request->input('uredjaj_' . $stavka) !== null
                        ];
                    }
                    catch (\Exception $exception){
                        Log::error("Greska pri dodavanju novih komercijalnih uslova za posotjeci ugovor edit-ugovor-2 => ".$exception->getMessage());
                        return redirect()->back()->with(['greska' => "Desila se greska! Id greske : edit-ugovor-2"]);
                    }
                }
            }



            //return dd($soapData);
            if($this->modifyIoTService($soapData, 'ModifyIoTService')){
                return redirect('/home');
            }
            else{
                return redirect()->back()->with(['greska' => "Desila se greska! Id greske : soap-request-2"]);
            }
        }
        return redirect()->back();
    }
    public function deleteKomUslov($id){
        try{
            $deleteResult = KomercijalniUslovi::where('id', $id)->update([
                'obrisana' => true,
                'datum_brisanja' => date("Y-m-d H:i:s"),
                'id_user_obrisao' => Auth::user()->id
            ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri brisanju komercijalnog uslova, greska: delete-kom-uslov-1 => ".$exception->getMessage());
            return response("Desila se greska! Id greske : delete-kom-uslov-1", Response::HTTP_BAD_REQUEST);
        }
        if($deleteResult){
            return response('',Response::HTTP_OK);
        }
        else{
            Log::error("Greska pri brisanju komercijalnog uslova, greska: delete-kom-uslov-1 => ".var_dump($deleteResult));
            return response("Desila se greska! Id greske : delete-kom-uslov-1", Response::HTTP_BAD_REQUEST);
        }
    }
    public function deaktivirajUgovor($id){
        $ugovor = Ugovor::whereId($id)->first();
        $res = $this->deleteIoTService($ugovor["connectivity_plan"], $ugovor["datum_potpisivanja"]);

        if($res){
            try{
                $deleteResult = Ugovor::where('id', $id)->update([
                    'dekativiran' => true
                ]);
            }
            catch (\Exception $exception){
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-1 => ".$exception->getMessage());
                return response("Desila se greska! Id greske : delete-ugovor-1", Response::HTTP_BAD_REQUEST);
            }
            if($deleteResult){
                return \response(Response::HTTP_OK);
            }
            else{
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-2".var_dump($deleteResult));
                return response("Desila se greska! Id greske : delete-ugovor-2", Response::HTTP_BAD_REQUEST);
            }
        }
        else{
            Log::error("Greska pri deaktivaciji ugovora delete-soap-1");
            return response("Desila se greska! Id greske : delete-soap-1", Response::HTTP_BAD_REQUEST);
        }

        //return \response($ugovor);

        /*if($res){
            try{
                //brisanje komercijalnih uslova
                KomercijalniUslovi::whereIdUgovor($id)->delete();
            }
            catch (\Exception $exception){
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-1 => ".$exception->getMessage());
                return response("Desila se greska! Id greske : delete-ugovor-1", Response::HTTP_BAD_REQUEST);
            }

            try{
                //brisanje vrste senzora_ugovroa
                VrstaSenzoraUgovor::whereIdUgovor($id)->delete();
            }
            catch (\Exception $exception){
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-2 => ".$exception->getMessage());
                return response("Desila se greska! Id greske : delete-ugovor-2", Response::HTTP_BAD_REQUEST);
            }

            try{
                //brisanje partnera_ugovora
                PartnerUgovor::whereIdUgovor($id)->delete();
            }
            catch (\Exception $exception){
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-3 => ".$exception->getMessage());
                return response("Desila se greska! Id greske : delete-ugovor-3", Response::HTTP_BAD_REQUEST);
            }

            try{
                //brisanje tehnologije_ugovor
                TehnologijeUgovor::whereIdUgovor($id)->delete();
            }
            catch (\Exception $exception){
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-4 => ".$exception->getMessage());
                return response("Desila se greska! Id greske : delete-ugovor-4", Response::HTTP_BAD_REQUEST);
            }

            try{
                //brisanje ugovora
                Ugovor::whereId($id)->delete();
            }
            catch (\Exception $exception){
                Log::error("Greska pri deaktivaciji ugovora delete-ugovor-5 => ".$exception->getMessage());
                return response("Desila se greska! Id greske : delete-ugovor-5", Response::HTTP_BAD_REQUEST);
            }

            return \response(Response::HTTP_OK);

        }
        else{
            Log::error("Greska pri deaktivaciji ugovora delete-soap-1");
            return response("Desila se greska! Id greske : delete-soap-1", Response::HTTP_BAD_REQUEST);
        }*/
    }

    public function addNewUser(Request $request){
        $request->validate([
            'ime' => 'required',
            'prezime' => 'required',
            'email' => 'required|email',
            'uloga' => 'required',
            'lozinka' => 'required',
            'lozinka_ponovo' => 'required'
        ]);

        try{
            $result = DB::table('users')
                ->insert([
                    'ime' => $request->input('ime'),
                    'prezime' => $request->input('prezime'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('lozinka')),
                    'id_uloga' => $request->get('uloga'),
                    'lastLogin' => date('Y-m-d H:i:s'),
                    'deaktiviran' => false
                ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri dodavanju korisnika id greske: korisnik-insert-1 => ".$exception->getMessage());
            return redirect()->back()->with(['greska' => "Greska pri dodavanju korisnika id greske: korisnik-insert-1."]);
        }
        if($result){
            return redirect('/addnewuser');
        }
        else{
            Log::error("Greska pri dodavanju korisnika id greske: korisnik-edit-1 => ");
            return redirect()->back()->with(['greska' => "Greska pri dodavanju korisnika id greske: korisnik-insert-1."]);
        }
    }
    public function editUser(Request $request){
        $request->validate([
            'id_korisnik' => 'required',
            'ime' => 'required',
            'prezime' => 'required',
            'email' => 'required|email',
            'uloga' => 'required'
        ]);

        try{
            $result = DB::table('users')->where('id','=',$request->input('id_korisnik'))
                ->update([
                    'ime' => $request->input('ime'),
                    'prezime' => $request->input('prezime'),
                    'email' => $request->input('email'),
                    'id_uloga' => $request->input('uloga')
                ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri editu korisnika id greske: korisnik-edit-1 => ".$exception->getMessage());
            return redirect()->back()->with(['greska' => "Greska pri editu korisnika id greske: korisnik-edit-1."]);
        }
        if($result){
            return redirect('/addnewuser');
        }
        else{
            Log::error("Greska pri editu korisnika id greske: korisnik-edit-1 => ");
            return redirect()->back()->with(['greska' => "Greska pri editu korisnika id greske: korisnik-edit-1."]);
        }
    }
    public function deleteUser($id){
        try{
            $deleteResult = User::where('id', $id)->update([
                'deaktiviran' => true
            ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri brisanju korisnika delete-korisnik-1 => ".$exception->getMessage());
            return response("Desila se greska! Id greske : delete-korisnik-1", Response::HTTP_BAD_REQUEST);
        }
        if($deleteResult){
            return response('',Response::HTTP_OK);
        }
        else{
            Log::error("Greska pri brisanju korisnika insert-korisnik-1".var_dump($deleteResult));
            return response("Desila se greska! Id greske : delete-korisnik-1", Response::HTTP_BAD_REQUEST);
        }
    }

    public function insert($text, $model, $naziv, $prikazi, $idGreske){
        try {
            $insertResult = $model::insert([
                'naziv' => $naziv,
                'prikazi' => $prikazi
            ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri dodavanju $text insert-$idGreske => ".$exception->getMessage());
            return redirect()->back()->with(['error' => "Desila se greska! Id greske : insert-$idGreske"]);
        }
        if($insertResult){
            return redirect()->back();
        }
        else{
            Log::error("Greska pri dodavanju $text insert-$idGreske");
            return redirect()->back()->with(['error' => "Desila se greska! Id greske : insert-$idGreske"]);
        }
    }
    public function dodajStavkuFakture(Request $request){
        $validated_data = $request->validate([
            'naziv' => ['required'],
            'naknada' => ['required'],
            'tip_naknade' => ['required']
        ]);

        try{
            $result = DB::table('stavka_fakture')->insert([
                'naziv' => $request->input('naziv'),
                'tip_naknade' => intval($request->input('tip_naknade')),
                'naknada' => floatval($request->input('naknada')),
                'zavisi_od_vrste_senzora' => $request->input('zavisi_od_vrste_senzora') !== null,
                'prikazi' => true
            ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri dodavanju stavke fakture insert-8 => ".$exception->getMessage());
            return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-8"]);
        }
        if($result){
            return redirect()->back();
        }
        else{
            Log::error("Greska pri dodavanju stavke fakture insert-8");
            return redirect()->back()->with(['greska' => "Desila se greska! Id greske : insert-8"]);
        }
    }
    public function dodajTipUgovora(InsertRequest $request){
        return $this->insert('tip ugovora', TipUgovora::class, $request->input('naziv'), true, 7);
    }
    public function dodajTipServisa(InsertRequest $request){
        return $this->insert('tip servisa', TipServisa::class, $request->input('naziv'), true, 6);
    }
    public function dodajTehnologije(InsertRequest $request){
        return $this->insert('tehnologija', Tehnologije::class, $request->input('naziv'), true, 5);
    }
    public function dodajPartnera(InsertRequest $request){
        return $this->insert('partner', Partner::class, $request->input('naziv'), true, 4);
    }
    public function dodajNazivServisa(InsertRequest $request){
        return $this->insert('servis', NazivServisa::class, $request->input('naziv'), true, 3);
    }
    public function dodajVrstuSenzora(InsertRequest $request){
        return $this->insert('vrsta senzora', VrstaSenzora::class, $request->input('naziv'), true, 2);
    }
    public function dodajLokacijuApp(InsertRequest $request){
        return $this->insert('lokacije aplikacije', LokacijaApp::class, $request->input('naziv'), true, 1);
    }

    public function delete($model, $id, $error_id, $text){
        try{
            $deleteResult = $model::where('id', $id)->update([
                'prikazi' => false
            ]);
        }
        catch (\Exception $exception){
            Log::error("Greska pri brisanju $text delete-$error_id => ".$exception->getMessage());
            return response("Desila se greska! Id greske : delete-$error_id", Response::HTTP_BAD_REQUEST);
        }
        if($deleteResult){
            return response('',Response::HTTP_OK);
        }
        else{
            Log::error("Greska pri brisanju $text insert-$error_id".var_dump($deleteResult));
            return response("Desila se greska! Id greske : delete-$error_id", Response::HTTP_BAD_REQUEST);
        }
    }
    public function deleteStavkaFakture($id){
        return $this->delete( StavkaFakture::class, $id, 8, 'stavka fakture');
    }
    public function deleteTipUgovora($id){
        return $this->delete(TipUgovora::class, $id, 7, 'tip ugovora');
    }
    public function deleteTipServisa($id){
        return $this->delete(TipServisa::class, $id, 6, 'tip servisa');
    }
    public function deleteTehnologije($id){
        return $this->delete(Tehnologije::class, $id, 5, 'tip servisa');
    }
    public function deletePartnera($id){
        return $this->delete(Partner::class, $id, 4, 'partner');
    }
    public function deleteServis($id){
        return $this->delete(NazivServisa::class, $id, 3, 'naziv servisa');
    }
    public function deleteVrstuSenzora($id){
        return $this->delete(VrstaSenzora::class, $id, 2, 'vrsta senzora');
    }
    public function deleteLokacijuApp($id){
        return $this->delete(LokacijaApp::class, $id, 1, 'lokacije aplikacije');
    }

    public function edit($model, $id, $error_id, $text, $data, $url){
        try{
            $editResult = $model::where('id', $id)->update($data);
        }
        catch (\Exception $exception){
            Log::error("Greska pri editu $text edit-$error_id => ".$exception->getMessage());
            return redirect()->back()->with(['error' => "Desila se greska! Id greske : edit-$error_id"]);
        }
        if($editResult){
            return redirect('/menage/'.$url);
        }
        else{
            Log::error("Greska pri editu $text edit-$error_id");
            return redirect()->back()->with(['error' => "Desila se greska! Id greske : edit-$error_id"]);
        }
    }
    public function editStavkaFakture(Request $request){
        $validated_data = $request->validate([
            'id_stavka_fakture' => 'required',
            'naziv' => 'required',
            'naknada' => 'required',
            'tip_naknade' => 'required'
        ]);

        $update_data = [
            'naziv' => $request->input('naziv'),
            'tip_naknade' => $request->input('tip_naknade'),
            'naknada' => $request->input('naknada'),
            'zavisi_od_vrste_senzora' => $request->input('zavisi_od_vrste_senzora') !== null
        ];

        return $this->edit(StavkaFakture::class, $request->input('id_stavka_fakture'), 8, 'stavka_fakture', $update_data, 'stavkafakture');
    }
    public function editTipUgovora(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(TipUgovora::class, $request->input('id_tip_ugovora'), 7, 'tip ugovora', $update_data, 'tipugovora');
    }
    public function editTipServisa(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(TipServisa::class, $request->input('id_tip_servisa'), 6, 'tip servisa', $update_data, 'tipservisa');
    }
    public function editTehnologije(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(Tehnologije::class, $request->input('id_tehnologija'), 5, 'tehnologija', $update_data, 'tehnologije');
    }
    public function editPartnera(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(Partner::class, $request->input('id_partner'), 4, 'partner', $update_data, 'partner');
    }
    public function editNazivServisa(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(NazivServisa::class, $request->input('id_naziv_servisa'), 3, 'naziv servisa', $update_data, 'nazivservisa');
    }
    public function editVrstaSenzora(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(VrstaSenzora::class, $request->input('id_vrsta_senzora'), 2, 'vrsta senzora', $update_data, 'vrstasenzora');
    }
    public function editLokacijuApp(EditRequest $request){
        $update_data = [
            'naziv' => $request->input('naziv')
        ];

        return $this->edit(LokacijaApp::class, $request->input('id_lokacija_aplikacije'), 1, 'lokacija aplikacije', $update_data, 'lokacijaapp');
    }


    public function getStavkaFakture($id){
        return StavkaFakture::whereId($id)->first();
    }
    public function getSoapUser($id){
        /*return [
            'id' => $id,
            'pib' => '123',
            'mbr' => '123',
            'email' => 'ast@asd',
            'telefon' => '0123',
            'kam' => 'kam',
            'segm' => 'seg',
            'racuni' => [
                "zbirni rac 1",
                "zbirni rac 2",
                "zbirni rac 3"
            ],
            'name' => "name test"
        ];*/

        $soapclient = new SoapClient('http://10.1.21.245:7810/services/GetAccountDetails?wsdl');
        $params = array(
            "CustomerId" => $id
        );
        //22563316
        //2109697
        $result = $soapclient->__soapCall("GetAccountDetails", array($params));
        if(isset($result->Account)){
            $obj = [
                "id" => $id,
                "pib" => $result->Account->AccountPIB,
                "mbr" => $result->Account->AccountMB,
                "telefon" => $result->Account->AccountMainPhoneNumber,
                "kam" => $result->Account->AccountTeam,
                "segm" => $result->Account->AccountSegment,
                "name" => $result->Account->BusinessName
            ];
            if(isset($result->Account->MainEmailAddress)){
                $obj["email"] = $result->Account->MainEmailAddress;
            }

            $racuni = [];
            if(isset($result->Account->ListOfComInvoiceProfile)){
                if(is_array($result->Account->ListOfComInvoiceProfile->ComInvoiceProfile)) {
                    foreach ($result->Account->ListOfComInvoiceProfile->ComInvoiceProfile as $racun) {
                        if ($racun->BPProfileType !== "Fixed") {
                            $racuni[] = $racun->BPCode;
                        }
                    }
                }
                else{
                    if($result->Account->ListOfComInvoiceProfile->ComInvoiceProfile->BPProfileType !== "Fixed"){
                        $racuni[] = $result->Account->ListOfComInvoiceProfile->ComInvoiceProfile->BPCode;
                    }
                }
            }

            $obj["racuni"] = $racuni;

            return \response($obj, Response::HTTP_OK);
        }

        return \response(null, Response::HTTP_BAD_REQUEST);
    }
    public function getSoapUserTest($id){
        $soapclient = new SoapClient('http://10.1.21.245:7810/services/GetAccountDetails?wsdl');
        $params = array(
            "CustomerId" => $id
        );
        //22563316
        //2109697
        $result = $soapclient->__soapCall("GetAccountDetails", array($params));
        if(is_array($result->Account->ListOfComInvoiceProfile->ComInvoiceProfile)) {
            echo "jeste niz";
        }
        else{
            echo "nije niz";
        }
        return dd($result);
    }

    public function soapTest(){
        /*$soapClient = new SoapClient('http://10.1.32.179/CloudWebService/CloudWebService?wsdl');

        $data = array (
            'CreateIoTService' => array (
                    'IoTServiceCommonDescription' => array (
                            'profileCode' => '22563316.002',
                            'connectivityPlan' => 'Connplan2',
                            'agreementName' => 'Agr name',
                            'agreementId' => 'Agr id',
                            'agreementType' => 'Agr type',
                            'commitmentPeriod' => '12',
                            'serviceDate' => '2022-01-01T09:00:00',
                            'serviceName' => 'Ser name',
                            'serviceType' => 'Ser type',
                            'partnerName' => 'Par name',
                            'technologyType' => 'Techs',
                            'sensorType' => 'Sensors',
                            'applicationLocation' => 'App location',
                            //new \SoapVar('123', 'utf-8','profileCode','test','test','test')
                        ),
                ),
        );

        //return dd($soapClient->_call.getMessageContext().getRequestMessage().getSOAPPartAsString());

        //$response = $soapClient->__soapCall('CreateIoTService', $data);

        $xml = `
        <Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <CreateIoTService xmlns="http://www.telekomsrbija.com/ws/CloudWebService/">
                    <IoTServiceCommonDescription xmlns="">
                        <profileCode xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">22563316.003</profileCode>
                        <connectivityPlan  xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Connplan3</connectivityPlan>
                        <agreementName  xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Agr name</agreementName>
                        <agreementId  xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Agr id</agreementId>
                        <agreementType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/"> Agr type</agreementType>
                        <commitmentPeriod xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">12</commitmentPeriod>
                        <serviceDate xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">2022-01-01T09:00:00</serviceDate>
                        <serviceName xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Ser name</serviceName>
                        <serviceType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Ser type</serviceType>
                        <partnerName xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Par name</partnerName>
                        <technologyType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Techs</technologyType>
                        <sensorType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Sensors</sensorType>
                        <applicationLocation xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">App location</applicationLocation>
                    </IoTServiceCommonDescription>
                </CreateIoTService>
            </Body>
        </Envelope>`;



        $response = $soapClient->__doRequest($xml, 'http://10.1.32.179/CloudWebService/CloudWebService','CreateIoTService', 1);

        return dd($response);*/

        $url = "http://10.1.32.179/CloudWebService/CloudWebService";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array(
            "Content-Type: application/soap+xml",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = <<<DATA
        <Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
            <Body>
                <CreateIoTService xmlns="http://www.telekomsrbija.com/ws/CloudWebService/">
                    <IoTServiceCommonDescription xmlns="">
                        <profileCode xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">22563316.003</profileCode>
                        <connectivityPlan  xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Connplan3</connectivityPlan>
                        <agreementName  xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Agr name</agreementName>
                        <agreementId  xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Agr id</agreementId>
                        <agreementType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/"> Agr type</agreementType>
                        <commitmentPeriod xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">12</commitmentPeriod>
                        <serviceDate xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">2022-01-01T09:00:00</serviceDate>
                        <serviceName xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Ser name</serviceName>
                        <serviceType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Ser type</serviceType>
                        <partnerName xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Par name</partnerName>
                        <technologyType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Techs</technologyType>
                        <sensorType xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">Sensors</sensorType>
                        <applicationLocation xmlns="http://www.telekomsrbija.com/ws/CloudWebService/Entities/">App location</applicationLocation>
                    </IoTServiceCommonDescription>
                </CreateIoTService>
            </Body>
        </Envelope>
        DATA;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($curl);
        curl_close($curl);

        return dd($resp);
    }

    public function exportExcel($searchobj = null){
        $array = explode("&",$searchobj);

        if($searchobj !== null){
            $obj = [
                'pretraga' => explode("=",$array[0])[1] == 0 ? null : explode("=",$array[0])[1],
                'naziv_ugovora' => explode("=",$array[1])[1] == 0 ? null : explode("=",$array[1])[1],
                'naziv_servisa' => explode("=",$array[2])[1] == 0 ? null : explode("=",$array[2])[1],
                'broj_ugovora' => explode("=",$array[3])[1] == 0 ? null : explode("=",$array[3])[1],
                'naziv_kupac' => explode("=",$array[4])[1] == 0 ? null : explode("=",$array[4])[1],
                'connectivity_plan' => explode("=",$array[5])[1] == 0 ? null : explode("=",$array[5])[1],
                'tip_ugovora' => explode("=",$array[6])[1] == 0 ? null : explode("=",$array[6])[1],
                'partner' => explode("=",$array[7])[1] == 0 ? null : explode("=",$array[7])[1],
                'datum_potpisa' => explode("=",$array[8])[1] == 0 ? null : explode("=",$array[8])[1],
                'id_kupac' => explode("=",$array[9])[1] == 0 ? null : explode("=",$array[9])[1],
                'kam' => explode("=",$array[10])[1] == 0 ? null : explode("=",$array[10])[1],
                'tip_servisa' => explode("=",$array[11])[1] == 0 ? null : explode("=",$array[11])[1],
                'tehnologija' => explode("=",$array[12])[1] == 0 ? null : explode("=",$array[12])[1],
                'uo' => explode("=",$array[13])[1] == 0 ? null : explode("=",$array[13])[1],
                'pib' => explode("=",$array[14])[1] == 0 ? null : explode("=",$array[14])[1],
                'segment' =>explode("=",$array[15])[1] == 0 ? null : explode("=",$array[15])[1],
            ];
        }
        else{
            $obj = null;
        }

        return Excel::download(new UgovorExport($obj), 'IoTDashboardExport.xlsx');
    }
    public function exportExcel2($test){
        return dd($test);
    }
}
