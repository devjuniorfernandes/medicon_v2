<?php

namespace App\Repositories;

use App\Models\Hospital;

interface HospitalRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug): ?Hospital;
    public function getFeatured($limit = 6);
    public function search(string $query, ?string $province, ?int $specialtyId);
}
