<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'province' => $this->province,
            'municipality' => $this->municipality,
            'address' => $this->address,
            'description' => $this->description,
            'website' => $this->website,
            'opening_hours' => $this->opening_hours,
            'average_rating' => (float) ($this->reviews_avg_rating ?? 0),
            'total_reviews' => (int) ($this->reviews_count ?? 0),
            'specialties' => SpecialtyResource::collection($this->whenLoaded('specialties')),
            'galleries' => GalleryResource::collection($this->whenLoaded('galleries')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
