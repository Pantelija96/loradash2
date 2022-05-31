<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UgovorResource extends JsonResource
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
            'user' => $this->getUser,
            'tip_ugovora' => $this->getTipUgovora,
            'tip_servisa' => $this->getTipServisa,
            'naziv_servisa' => $this->getNazivServisa,
            'lokacija_app' => $this->getLokacijaApp,
            'connectivity_plan' => $this->connectivity_plan,
            'ip_adresa' => $this->ip_adresa,
            'naziv_servera' => $this->naziv_servera,
            'naziv_ugovora' => $this->naziv_ugovora,
            'broj_ugovora' => $this->broj_ugovora,
            'tehnologije' => $this->getTehnologije
        ];
    }
}
