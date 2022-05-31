<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UgovorExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tip_ugovora' => $this->getTipUgovora['naziv'],
            'tip_servisa' => $this->getTipServisa['naziv'],
            'naziv_servisa' => $this->getNazivServisa['naziv'],
            'lokacija_app' => $this->getLokacijaApp['naziv'],
            'connectivity_plan' => $this->connectivity_plan,
            'ip_adresa' => $this->ip_adresa,
            'naziv_servera' => $this->naziv_servera,
            'naziv_ugovora' => $this->naziv_ugovra,
            'broj_ugovora' => $this->broj_ugovora,
            'datum_potpisivanja' => $this->datum_potpisivanja,
            'ugovorna_obaveza' => $this->ugovorna_obaveza,
            'zbirni_racun' => $this->zbirni_racun,
            'napomena' => $this->napomena,
            'id_kupac' => $this->id_kupac,
            'naziv_kupac' => $this->naziv_kupac,
            'pib' => $this->pib,
            'mb' => $this->mb,
            'segment' => $this->segment,
            'email' => $this->email,
            'telefon' => $this->telefon,
            'deaktiviran' => $this->deaktiviran,
            'kam' => $this->kam
        ];
    }
}
