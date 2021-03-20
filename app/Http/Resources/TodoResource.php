<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
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
            'id' => (integer) $this->id,
            'label' => (string) $this->label,
            'created_at' => Carbon::make($this->created_at)->format('d-m-Y H:i:s'),
            'tasks' => TodoTaskResource::collection($this->whenLoaded('tasks'))
        ];
    }
}
