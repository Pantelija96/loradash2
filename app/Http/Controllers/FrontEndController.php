<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;

class FrontEndController extends Controller
{
    //

    private $data = [];

    function index(){
      return view('shared.login', $this->data);
    }

    function dashboard(){

      return view('shared.home', $this->data);
    }

    function changePassword(){
      return view('shared.changePassword', $this->data);
    }

    function showAllUsers(){
      $this->data['korisnici'] = User::getAll();
      return view('shared.allUsers', $this->data);
    }

    function addUser(){
      return view('shared.addUser', $this->data);
    }

    function showAllSensors(){
      $this->data['sensors'] = Sensor::getAll();
      return view('shared.allSensors', $this->data);
    }

    function addSensor(){
      return view('shared.addSensor', $this->data);
    }

    function addService(){
      return view('shared.addService', $this->data);
    }

}
