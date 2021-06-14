<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;
use RicorocksDigitalAgency\Soap\Facades\Soap;

use SoapClient;

class BackEndController extends Controller
{
    //Soap
    public $client;
    private function _client($F5srv){
      $opts = array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      );

      $context = stream_context_create($opts);
      $wsdl = "wsdl path";

      try{
        $this->client = new \SoapClient($wsdl, array('stream_context' => $context, 'trace'=> true,'login' => 'test', 'password'=> 'test2'));

        return $this->client;
      }

      catch(\Exception $e){
        Log::info('Caught Exception in client'. $e->getMessage());
      }
    }

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

      $this->client = $this->_client($userId);



      $obj = [
        "test" => 123213,
        "test2" => "string",
        "test3" => $userId
      ];
      return json_encode($obj);
    }
}
