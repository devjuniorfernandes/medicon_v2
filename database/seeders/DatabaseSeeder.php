<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hospital;
use App\Models\Specialty;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@medicon.ao',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPER_ADMIN->value,
        ]);

        // Specialties
        $specialties = [
            'Cardiologia', 'Pediatria', 'Ortopedia', 'Ginecologia e Obstetrícia', 
            'Oftalmologia', 'Dermatologia', 'Neurologia', 'Urologia', 'Psiquiatria', 
            'Otorrinolaringologia', 'Estomatologia', 'Clínica Geral'
        ];

        foreach ($specialties as $name) {
            Specialty::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Serviços médicos dedicados à especialidade de ' . $name . '.',
            ]);
        }

        $allSpecialtyIds = Specialty::pluck('id')->toArray();

        // Hospitals
        $hospitalsData = [
            ['name' => 'Clínica Girassol', 'prov' => 'Luanda', 'mun' => 'Maianga'],
            ['name' => 'Hospital Sagrada Esperança', 'prov' => 'Luanda', 'mun' => 'Ingombota'],
            ['name' => 'Centro Médico Multiperfil', 'prov' => 'Luanda', 'mun' => 'Luanda'],
            ['name' => 'Hospital Geral de Benguela', 'prov' => 'Benguela', 'mun' => 'Benguela'],
            ['name' => 'Clínica Nossa Senhora da Paz', 'prov' => 'Huíla', 'mun' => 'Lubango'],
            ['name' => 'Hospital Central do Huambo', 'prov' => 'Huambo', 'mun' => 'Huambo'],
        ];

        foreach ($hospitalsData as $index => $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => 'hospital' . ($index + 1) . '@medicon.ao',
                'password' => Hash::make('password'),
                'role' => UserRole::HOSPITAL->value,
            ]);

            $hospital = Hospital::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'email' => $user->email,
                'phone' => '+244 9' . rand(10000000, 99999999),
                'province' => $data['prov'],
                'municipality' => $data['mun'],
                'address' => 'Rua Principal, ' . $data['mun'] . ', ' . $data['prov'],
                'description' => 'O ' . $data['name'] . ' é uma instituição de saúde focada na excelência e cuidado contínuo dos seus pacientes.',
                'opening_hours' => 'Segunda a Sexta, 08h00 - 20h00',
            ]);

            // Attach 3-5 random specialties
            shuffle($allSpecialtyIds);
            $hospital->specialties()->attach(array_slice($allSpecialtyIds, 0, rand(3, 5)));
        }
    }
}
