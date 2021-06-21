<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sensor;

class FrontEndController extends Controller
{
    //
	
	function display_xml_error($error, $xml)
{
    $return  = $xml[$error->line - 1] . "\n";
    $return .= str_repeat('-', $error->column) . "^\n";

    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
         case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }

    $return .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";

    if ($error->file) {
        $return .= "\n  File: $error->file";
    }

    return "$return\n\n--------------------------------------------\n\n";
}

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
