<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Event Id' => $this->id,
            'Event Name' => $this->name,
            'Event Description' => $this->description,
            'Event Start at' => $this->start_time,
            'Event Ends at' =>$this->end_time,
            'user' =>new UserResource($this->whenLoaded('user')),
            'attendees' =>AttendeeResource::collection(
                $this->whenLoaded('attendees')
            )
        ];
    }
}
