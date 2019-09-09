<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'city' => $this->city,
            'country' => $this->country,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => new UserResource($this->creator),
            // 'vacancies' => VacancyResource::collection($this->vacancies),
            // if(isset($this->workers)){
            // 'workers' => $this->workers,
            // }
        ];
        if (isset($this->_vacancies) and $this->_vacancies != 0) {
            $data['vacancies'] = $this->vacancies;
            if (isset($this->workers)) {
                $data['workers'] = $this->workers;
            }
        }
        return $data;
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
