<?php

namespace App\Http\Controllers;

use App\Models\Usluga;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;

class FrontEndController extends Controller
{
    private $data = [];

    function index()
    {
        return view('shared.login', $this->data);
    }

    function dashboard()
    {
        $usluga = new Usluga();
        $this->data['usluge'] = $usluga->getAll();
        $this->data['brojKupaca'] = Usluga::getNumberOfUsers();
        $this->data['brojSenzora'] = Sensor::brojSenzora();
        $this->data['brojAktivnihSenzora'] = Sensor::brojAktivnihSenzora();
        $this->data['brojAktivnihServisa'] = Usluga::getNumberOfActiveServices();
        //return dd($this->data);
        return view('shared.home', $this->data);
    }

    function changePassword()
    {
        return view('shared.changePassword', $this->data);
    }

    function showAllUsers()
    {
        $this->data['korisnici'] = User::getAll();
        return view('shared.allUsers', $this->data);
    }

    function addUser()
    {
        return view('shared.addUser', $this->data);
    }

    function showAllSensors()
    {
        $this->data['sensors'] = Sensor::getAll();
        return view('shared.allSensors', $this->data);
    }

    function addSensor()
    {
        return view('shared.addSensor', $this->data);
    }

    function addService()
    {
        $this->data['sensors'] = Sensor::getAll();
        return view('shared.addService', $this->data);
    }

}
