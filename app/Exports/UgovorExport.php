<?php

namespace App\Exports;

use App\Http\Resources\UgovorExportResource;
use App\Models\Ugovor;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UgovorExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithCustomStartCell
{
    private $obj = null;

    public function __construct($searchObj){
        $this->obj = $searchObj;
    }

    public function array(): array
    {
        $array = [];

        if($this->obj == null){
            $ugovori = UgovorExportResource::collection(Ugovor::all())->resolve();
        }
        else{
            $search_obj = $this->obj;

            $datum_pretraga = $search_obj['datum_potpisa'];
            $tehnologija_id = $search_obj['tehnologija'];
            $partner_id = $search_obj['partner'];
            $naziv_servisa_id = $search_obj['naziv_servisa'];

            $ugovori_result = Ugovor::select('ugovor.*')
                ->join('tehnologije_ugovor', 'tehnologije_ugovor.id_ugovor', '=', 'ugovor.id')
                ->join('partner_ugovor', 'partner_ugovor.id_ugovor', '=', 'ugovor.id')
                ->where('naziv_ugovra', 'LIKE', '%'.$search_obj['pretraga'].'%')
                ->where('naziv_ugovra', 'LIKE', '%'.$search_obj['naziv_ugovora'].'%')
                ->where('broj_ugovora', 'LIKE', '%'.$search_obj['broj_ugovora'].'%')
                ->where('naziv_kupac', 'LIKE', '%'.$search_obj['naziv_kupac'].'%')
                ->where('connectivity_plan', 'LIKE', '%'.$search_obj['connectivity_plan'].'%')
                ->where('id_kupac', 'LIKE', '%'.$search_obj['id_kupac'].'%')
                ->where('pib', 'LIKE', '%'.$search_obj['pib'].'%')
                ->where('segment', 'LIKE', '%'.$search_obj['segment'].'%')
                ->where('kam', 'LIKE', '%'.$search_obj['kam'].'%')
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

            $ugovori = UgovorExportResource::collection($ugovori_result)->resolve();
        }
        foreach ($ugovori as $u){
            $row = [
                $u['id'],
                $u['naziv_kupac'],
                $u['pib'],
                $u['mb'],
                $u['kam'],
                $u['segment'],
                $u['telefon'],
                $u['broj_ugovora'],
                $u['naziv_ugovora'],
                $u['tip_ugovora'],
                $u['tip_servisa'],
                $u['naziv_servisa'],
                $u['lokacija_app'],
                $u['ip_adresa'],
                $u['naziv_servera'],
                $u['datum_potpisivanja'],
                $u['ugovorna_obaveza'],
                $u['zbirni_racun'],
                $u['napomena']
            ];

            $tehnologijeUgovora = DB::table('tehnologije')
                ->select('naziv')
                ->join('tehnologije_ugovor','tehnologije.id', '=','tehnologije_ugovor.id_tehnologije')
                ->where('id_ugovor','=', $u['id'])
                ->get();
            $textTehnologije = '';
            foreach ($tehnologijeUgovora as $tu){
                $textTehnologije = $textTehnologije." ".$tu->naziv.",";
            }
            $row['tehnologije'] = $textTehnologije;


            $partnerUgovor = DB::table('partner')
                ->select('naziv')
                ->join('partner_ugovor','partner.id', '=','partner_ugovor.id_partner')
                ->where('id_ugovor','=', $u['id'])
                ->get();
            $textPartner = '';
            foreach ($partnerUgovor as $pu){
                $textPartner = $textPartner." ".$pu->naziv.",";
            }
            $row['partneri'] = $textPartner;

            $senzorUgovor = DB::table('vrsta_senzora')
                ->select('naziv')
                ->join('vrsta_senzora_ugovor','vrsta_senzora.id', '=','vrsta_senzora_ugovor.id_vrsta_senzora')
                ->where('id_ugovor','=', $u['id'])
                ->get();
            $textSenzor = '';
            foreach ($senzorUgovor as $su){
                $textSenzor = $textSenzor." ".$su->naziv.",";
            }
            $row['senzori'] = $textSenzor;

            $array[] = $row;
        }
        return $array;
    }

    public function headings(): array
    {
        return [
            'Id korisnika',
            'Naziv koirisnika',
            'PIB',
            'Maticni broj',
            'Kam',
            'Segment',
            'Telefon',
            'Broj ugovora',
            'Naziv ugovora',
            'Tip ugovora',
            'Tip servisa',
            'Naziv servisa',
            'Lokacija aplikacije',
            'IP adresa',
            'Naziv servera',
            'Datum potpisivanja',
            'Ugovorna obaveza',
            'Zbirni racun',
            'Napomena',
            'Tehnologije',
            'Partneri',
            'Senzori'

        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            "A" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "B" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "C" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "D" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "E" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "F" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "G" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "H" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "I" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "J" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "K" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "L" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "M" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "N" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "O" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "P" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "Q" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "R" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "S" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "T" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "U" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            "V" => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function startCell(): string
    {
        return "A1";
    }
}
