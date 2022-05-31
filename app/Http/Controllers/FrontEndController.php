<?php

namespace App\Http\Controllers;

use App\Http\Resources\KomercijalniUslovResource;
use App\Http\Resources\UgovorResource;
use App\Http\Resources\UserResource;
use App\Models\KomercijalniUslovi;
use App\Models\LokacijaApp;
use App\Models\NazivServisa;
use App\Models\Partner;
use App\Models\PartnerUgovor;
use App\Models\StavkaFakture;
use App\Models\Tehnologije;
use App\Models\TehnologijeUgovor;
use App\Models\TipServisa;
use App\Models\TipUgovora;
use App\Models\Ugovor;
use App\Models\User;
use App\Models\VrstaSenzora;
use App\Models\VrstaSenzoraUgovor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Utils;

class FrontendController extends Controller
{
    private $data = [];
    private function homePageData($homeType){
        $this->data = [];

        $this->data['homeType'] = $homeType == null ? 1 : $homeType;
        $this->data['nazivi_servisa'] = NazivServisa::wherePrikazi(true)->get();
        $this->data['tipovi_ugovora'] = TipUgovora::wherePrikazi(true)->get();
        $this->data['partneri'] = Partner::wherePrikazi(true)->get();
        $this->data['tipovi_servisa'] = TipServisa::wherePrikazi(true)->get();
        $this->data['tipovi_tehnologije'] = Tehnologije::wherePrikazi(true)->get();
        $this->data['sviUgovori'] = Ugovor::whereDekativiran(false)->get();
    }

    public function loginPage(){
        return view('pages.login');
    }
    public function home($homeType = null){
        $this->homePageData($homeType);
        return view('pages.home', $this->data);
    }
    public function search(Request $request, $homeTypeVar = null){
        //return dd($request->all());
        $homeType = intval($homeTypeVar == null ? $request->input('view') : $homeTypeVar);
        $this->homePageData($homeType);

        $datum_pretraga = $request->input('datum_potpisa');
        $tehnologija_id = $request->input('tehnologija');
        $partner_id = $request->input('partner');
        $naziv_servisa_id = $request->input('naziv_servisa');
        $tip_ugovora = $request->get("tip_ugovora");
        $tip_servisa = $request->get("tip_servisa");
        $uo = $request->get('uo');

        $ugovori_komplet = Ugovor::select('ugovor.*')
            ->join('tehnologije_ugovor', 'tehnologije_ugovor.id_ugovor', '=', 'ugovor.id')
            ->join('partner_ugovor', 'partner_ugovor.id_ugovor', '=', 'ugovor.id')
            ->where('naziv_ugovra', 'LIKE', '%'.$request->input('pretraga').'%')
            ->where('naziv_ugovra', 'LIKE', '%'.$request->input('naziv_ugovora').'%')
            ->where('broj_ugovora', 'LIKE', '%'.$request->input('broj_ugovora').'%')
            ->where('naziv_kupac', 'LIKE', '%'.$request->input('naziv_kupac').'%')
            ->where('connectivity_plan', 'LIKE', '%'.$request->input('connectivity_plan').'%')
            ->where('id_kupac', 'LIKE', '%'.$request->input('id_kupac').'%')
            ->where('pib', 'LIKE', '%'.$request->input('pib').'%')
            ->where('segment', 'LIKE', '%'.$request->input('segment').'%')
            ->where('kam', 'LIKE', '%'.$request->input('kam').'%')
            ->when($uo, function ($query, $uo){
                $query->where('ugovorna_obaveza', '=', $uo);
            })
            ->when($tip_ugovora, function ($query, $tip_ugovora){
                $query->where('id_tip_ugovora', '=', $tip_ugovora);
            })
            ->when($tip_servisa, function ($query, $tip_servisa){
                $query->where('id_tip_servisa', '=', $tip_servisa);
            })
            ->when($datum_pretraga, function ($query, $datum_pretraga){
                $query->whereDate('datum_potpisivanja', '=', date('Y-m-d',strtotime($datum_pretraga)));
            })
            ->when($tehnologija_id, function ($query, $tehnologija_id){
                $query->where('id_tehnologije', '=', $tehnologija_id);
            })
            ->when($partner_id, function ($query, $partner_id){
                $query->where('id_partner', '=', $partner_id);
            })
            ->when($naziv_servisa_id, function ($query, $naziv_servisa_id){
                $query->where('id_naziv_servisa', '=', $naziv_servisa_id);
            })
            ->groupBy('ugovor.id')
            ->get();

        $this->data['ugovori'] = $ugovori_komplet;

        $this->data['pretraga'] = $request->input('pretraga');
        $this->data['naziv_ugovora'] = $request->input('naziv_ugovora');
        $this->data['naziv_servisa'] = $request->input('naziv_servisa');
        $this->data['broj_ugovora'] = $request->input('broj_ugovora');
        $this->data['naziv_kupac'] = $request->input('naziv_kupac');
        $this->data['connectivity_plan'] = $request->input('connectivity_plan');
        $this->data['tip_ugovora'] = $request->input('tip_ugovora');
        $this->data['partner'] = $request->input('partner');
        $this->data['datum_potpisa'] = $request->input('datum_potpisa');
        $this->data['id_kupac'] = $request->input('id_kupac');
        $this->data['kam'] = $request->input('kam');
        $this->data['tip_servisa'] = $request->input('tip_servisa');
        $this->data['tehnologija'] = $request->input('tehnologija');
        $this->data['uo'] = $request->input('uo');
        $this->data['pib'] = $request->input('pib');
        $this->data['segment'] = $request->input('segment');

        $search = [
            'pretraga' => $request->input('pretraga') == null ? "0" : $request->input('pretraga'),
            'naziv_ugovora' => $request->input('naziv_ugovora') == null ? "0" : $request->input('naziv_ugovora'),
            'naziv_servisa' => $request->input('naziv_servisa') == null ? "0" : $request->input('naziv_servisa'),
            'broj_ugovora' => $request->input('broj_ugovora') == null ? "0" : $request->input('broj_ugovora'),
            'naziv_kupac' => $request->input('naziv_kupac') == null ? "0" : $request->input('naziv_kupac'),
            'connectivity_plan' => $request->input('connectivity_plan') == null ? "0" : $request->input('connectivity_plan'),
            'tip_ugovora' => $request->input('tip_ugovora') == null ? "0" : $request->input('tip_ugovora'),
            'partner' => $request->input('partner') == null ? "0" : $request->input('partner'),
            'datum_potpisa' => $request->input('datum_potpisa') == null ? "0" : $request->input('datum_potpisa'),
            'id_kupac' => $request->input('id_kupac') == null ? "0" : $request->input('id_kupac'),
            'kam' => $request->input('kam') == null ? "0" : $request->input('kam'),
            'tip_servisa' => $request->input('tip_servisa') == null ? "0" : $request->input('tip_servisa'),
            'tehnologija' => $request->input('tehnologija') == null ? "0" : $request->input('tehnologija'),
            'uo' => $request->input('uo') == null ? "0" : $request->input('uo'),
            'pib' => $request->input('pib') == null ? "0" : $request->input('pib'),
            'segment' => $request->input('segment') == null ? "0" : $request->input('segment')
        ];

        $this->data['search_obj'] = http_build_query($search);

        //return dd($this->data);
        return view('pages.search', $this->data);
    }
    public function editContract($id){
        $this->homePageData(null);
        $this->data['lokacije_app'] = LokacijaApp::wherePrikazi(true)->get();
        $this->data['vrste_senzora'] = VrstaSenzora::wherePrikazi(true)->get();
        $this->data['stavke_fakture'] = StavkaFakture::wherePrikazi(true)->get();
        $this->data['ugovor'] = Ugovor::whereId($id)->first();

        $this->data['partneri_naziv'] = "";
        $this->data['partneri_ugovora'] = [];
        $partneri = PartnerUgovor::whereIdUgovor($id)->get();
        foreach ($partneri as $par){
            $this->data['partneri_ugovora'][] = $par->id_partner;
            $this->data['partneri_naziv'] .= Partner::whereId($par->id_partner)->first()->naziv.";";
        }
        $this->data['partneri_naziv'] = substr($this->data['partneri_naziv'], 0, -1);

        $this->data['tehnologije_naziv'] = "";
        $this->data['tehnologije_ugovora'] = [];
        $tehnologije = TehnologijeUgovor::whereIdUgovor($id)->get();
        foreach ($tehnologije as $teh){
            $this->data['tehnologije_ugovora'][] = $teh->id_tehnologije;
            $this->data['tehnologije_naziv'] .=Tehnologije::whereId($teh->id_tehnologije)->first()->naziv.";";
        }
        $this->data['tehnologije_naziv'] = substr($this->data['tehnologije_naziv'], 0, -1);

        $this->data['senzori_naziv'] = "";
        $this->data['vrste_senzora_ugovor'] = [];
        $senzori = VrstaSenzoraUgovor::whereIdUgovor($id)->get();
        foreach ($senzori as $sen){
            $this->data['vrste_senzora_ugovor'][] = $sen->id_vrsta_senzora;
            $this->data['senzori_naziv'] .= VrstaSenzora::whereId($sen->id_vrsta_senzora)->first()->naziv.";";
        }
        $this->data['senzori_naziv'] = substr($this->data['senzori_naziv'], 0, -1);

        $this->data['komercijalni_uslovi'] = KomercijalniUslovResource::collection(KomercijalniUslovi::whereIdUgovor($id)->where('obrisana',false)->get())->resolve();

        //return dd($this->data['komercijalni_uslovi']);
        return view('pages.editContract', $this->data);
    }
    public function addNewContract(){
        $this->homePageData(null);
        $this->data['lokacije_app'] = LokacijaApp::wherePrikazi(true)->get();
        $this->data['vrste_senzora'] = VrstaSenzora::wherePrikazi(true)->get();
        $this->data['stavke_fakture'] = StavkaFakture::wherePrikazi(true)->get();
        return view('pages.addNewContract', $this->data);
    }
    public function addNewUser($id = null){
        if($id){
            $this->data['korisnik'] = UserResource::make(User::whereId($id)->first())->resolve();
        }
        $this->data['korisnici'] = UserResource::collection(User::whereDeaktiviran(false)->get())->resolve();
        //return dd($this->data);
        return view('admin.addnewuser', $this->data);
    }

    public function dodajStavkuFakture($id = null){
        if($id){
            $this->data['stavka_fakture'] = StavkaFakture::whereId($id)->first();
        }
        $this->data['stavke_fakture'] = StavkaFakture::wherePrikazi(true)->get();
        return view('systemManaging.stavkafakture', $this->data);
    }
    public function dodajTipUgovora($id = null){
        if($id){
            $this->data['tip_ugovora'] = TipUgovora::whereId($id)->first();
        }
        $this->data['tipovi_ugovora'] = TipUgovora::wherePrikazi(true)->get();
        return view('systemManaging.tipugovora', $this->data);
    }
    public function dodajTipServisa($id = null){
        if($id){
            $this->data['tip_servisa'] = TipServisa::whereId($id)->first();
        }
        $this->data['tipovi_servisa'] = TipServisa::wherePrikazi(true)->get();
        return view('systemManaging.tipservisa', $this->data);
    }
    public function dodajTehnologije($id = null){
        if($id){
            $this->data['tip_tehnologije'] = Tehnologije::whereId($id)->first();
        }
        $this->data['tipovi_tehnologija'] = Tehnologije::wherePrikazi(true)->get();
        return view('systemManaging.tehnologija', $this->data);
    }
    public function dodajPartnera($id = null){
        if($id){
            $this->data['partner'] = Partner::whereId($id)->first();
        }
        $this->data['partneri'] = Partner::wherePrikazi(true)->get();
        return view('systemManaging.partner', $this->data);
    }
    public function dodajServis($id = null){
        if($id){
            $this->data['servis'] = NazivServisa::whereId($id)->first();
        }
        $this->data['servisi'] = NazivServisa::wherePrikazi(true)->get();
        return view('systemManaging.servis', $this->data);
    }
    public function dodajVrstuSenzora($id = null){
        if($id){
            $this->data['vrsta_senzora'] = VrstaSenzora::whereId($id)->first();
        }
        $this->data['vrste_senzora'] = VrstaSenzora::wherePrikazi(true)->get();
        return view('systemManaging.vrstasenzora', $this->data);
    }
    public function dodajLokacijuApp($id = null){
        if($id){
            $this->data['loakcija_app'] = LokacijaApp::whereId($id)->first();
        }
        $this->data['lokacije_app'] = LokacijaApp::wherePrikazi(true)->get();
        return view('systemManaging.lokacijaapp', $this->data);
    }
}
