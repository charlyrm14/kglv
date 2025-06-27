<?php 

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ContentTypeResource
 * 
 * This resource is responsible for transforming the ContentType model into a JSON-friendly array.
 * It includes the basic information such as id, title, and slug.
 * 
 * @package App\Resources
 */
class ContentTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request  The current request instance.
     * @return array<string, mixed>  The array representation of the resource.
     */
    public function toArray($content_type)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug
        ];
    }
}