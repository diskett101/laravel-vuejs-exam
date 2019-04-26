<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'type' => $this->type,
            'firstname' => $this->info->firstname,
            'lastname' => $this->info->lastname,
            'fullname' => $this->fullname,
            'address' => $this->info->address,
            'postcode' => $this->info->postcode,
            'phone_number' => $this->info->phone_number,
        ];
    }
}
