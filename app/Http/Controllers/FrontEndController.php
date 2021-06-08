<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
