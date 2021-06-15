<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;

use SoapClient;

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
      //soap

      try{
        //$soapclient = new SoapClient('http://dneonline.com/calculator.asmx?wsdl');
        //$response = $soapclient->add(1,2);

        $soapclient = new SoapClient('http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL');
        //$param = array('ubiNum'=>$userId);
        $response = $soapclient->ListOfCurrenciesByName();

        /*var_dump($response);
        echo '<br><br><br>';
        $array = json_decode(json_encode($response), true);
        print_r($array);
         echo '<br><br><br>';
        echo  $array['GetCountriesAvailableResult']['CountryCode']['5']['Description'];
        	  echo '<br><br><br>';
        	foreach($array as $item) {
        		echo '<pre>'; var_dump($item);
        	}*/
      }catch(Exception $e){
      	$response = $e->getMessage();
      }

      $obj = [
        "test" => 123213,
        "test2" => "string",
        "test3" => $userId,
        'response' => $response
      ];
      return json_encode($obj);
    }
}
