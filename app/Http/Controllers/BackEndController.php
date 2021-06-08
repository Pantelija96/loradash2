<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

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

    
}
