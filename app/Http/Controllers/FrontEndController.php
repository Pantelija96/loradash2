<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    //

    private $data = [];

    function index(){
      return view('welcome', $this->data);
    }
}
