<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
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
            'status' => ($this->workers_amount <= $this->workers_booked)?'closed':'active',
            'vacancy_name' => $this->vacancy_name,
            'workers_amount' => $this->workers_amount,
            'workers_booked' => $this->workers_booked,
            'organization' => $this->organization->title,
            'salary' => $this->salary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if($this->_workers){
            $data['workers'] = $this->workers;
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
