<?php

namespace App\Resources;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource 
{
    /**
     * The toArray function converts event data into an associative array with specific keys and
     * formatted date values.
     * 
     * @param event It looks like you are trying to convert an event object into an array using the
     * `toArray` method. The method extracts specific properties from the event object and formats them
     * accordingly.
     * 
     * @return An array is being returned with the following keys and values:
     * - 'id' => the id of the event
     * - 'title' => the title of the event
     * - 'short_description' => the short description of the event
     * - 'content' => the content of the event
     * - 'slug' => the slug of the event
     * - 'start_hour' => the start hour of the
     */
    public function toArray($event)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'slug' => $this->slug,
            'cover_image' => $this->cover_image,
            'location' => $this->location,
            'start_hour' => Carbon::parse($this->start_date)->format('H:i'),
            'end_hour' => Carbon::parse($this->end_date)->format('H:i'),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'active' => $this->active,
            'content_type_id' => $this->content_type_id,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'content_type' => new ContentTypeResource($this->whenLoaded('contentType'))
        ];
    }
}