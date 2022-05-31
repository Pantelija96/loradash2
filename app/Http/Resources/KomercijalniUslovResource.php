<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KomercijalniUslovResource extends JsonResource
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
            'stavka_fakture' => $this->getStavkaFakture,
            'vrsta_senzora' => $this->getVrstaSenzora,
            'datum_pocetak' => $this->datum_pocetak,
            'datum_kraj' => $this->datum_kraj,
            'naknada' => $this->naknada,
            'status' => $this->status,
            'min' => $this->min,
            'max' => $this->max,
            'uredjaj' => $this->uredjaj,
            'sim' => $this->sim_kartica
        ];
    }
}
