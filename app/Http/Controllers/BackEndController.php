<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;

use SoapClient;
use SoapHeader;

class BackEndController extends Controller
{

    private $user;

    public function login(Request $request){
      $this->user = new User();
      $this->user->email = $request->get('email');
      $this->user->lozinka = $request->get('password');

      $userResult = $this->user->login();

      if($userResult){
        $request->session()->put('korisnik', $userResult);
        return redirect('/home');
      }
      else{
        return redirect('/');
      }
    }

    public function logout(Request $request){
      $request->session()->forget('korisnik');
      $request->session()->flush();
      return redirect('/');
    }

    public function changePassword(Request $request){
      $this->user = new User();
      $this->user->idKorisnikSistema = $request->session()->get('korisnik')->idKorisnikSistema;
      $this->user->lozinka = $request->get('newPassword');

      $result = $this->user->changePassword();

      return dd($result);
    }

    public function addUser(Request $request){
      $this->user = new User();
      $this->user->ime = $request->get('ime');
      $this->user->prezime = $request->get('prezime');
      $this->user->email = $request->get('email');
      $this->user->lozinka = $request->get('password');

      $result = $this->user->insertNewUser();
      return redirect('/allusers');
    }

    public function addSensor(Request $requrest){
      return dd($requrest->all());
    }

    public function findUser($userId){

      try{

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
					<get:PIB>".$userId."</get:PIB>\n
				</get:TS1GetAccountDetailsInputMessage>\n
      	  </soapenv:Body>\n
        </soapenv:Envelope>",
        CURLOPT_HTTPHEADER => array("content-type: text/xml"),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      }catch(Exception $e){
      	$response = $e->getMessage();
      }

      $obj = [
        'response' => $response
      ];
      return ($obj);
    }
}
