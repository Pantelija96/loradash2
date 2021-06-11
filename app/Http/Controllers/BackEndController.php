<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;

class BackEndController extends Controller
{
    //

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
}
