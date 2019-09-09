<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationCollection extends ResourceCollection
{

    public $collects = 'App\Http\Resources\OrganizationResource';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'data' => $this->collection,
        ];
    }
}
