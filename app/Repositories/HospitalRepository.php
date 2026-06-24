<?php

namespace App\Repositories;

use App\Models\Hospital;

class HospitalRepository extends BaseRepository implements HospitalRepositoryInterface
{
    public function __construct(Hospital $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug): ?Hospital
    {
        return $this->model->where('slug', $slug)->with('specialties', 'galleries')->first();
    }

    public function getFeatured($limit = 6)
    {
        return $this->model->where('is_featured', true)
            ->where('is_active', true)
            ->take($limit)
            ->get();
    }

    public function search(string $query, ?string $province, ?int $specialtyId)
    {
        $q = $this->model->query()->where('is_active', true);

        if ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%{$query}%")
                    ->orWhere('city', 'like', "%{$query}%")
                    ->orWhere('municipality', 'like', "%{$query}%");
            });
        }

        if ($province) {
            $q->where('province', $province);
        }

        if ($specialtyId) {
            $q->whereHas('specialties', function ($sub) use ($specialtyId) {
                $sub->where('specialties.id', $specialtyId);
            });
        }

        return $q->paginate(12);
    }
}
