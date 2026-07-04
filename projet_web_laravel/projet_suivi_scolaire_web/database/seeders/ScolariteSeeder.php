<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\User;
use App\Models\Versement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ScolariteSeeder extends Seeder
{
    public function run(): void
    {

        // ==================== CLASSES ====================
        $classesData = [
            ['nom' => 'CP1', 'capacite_max' => 30, 'frais_scolarite' => 45000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CP2', 'capacite_max' => 30, 'frais_scolarite' => 45000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CE1', 'capacite_max' => 35, 'frais_scolarite' => 50000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CE2', 'capacite_max' => 35, 'frais_scolarite' => 50000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CM1', 'capacite_max' => 30, 'frais_scolarite' => 55000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CM2', 'capacite_max' => 30, 'frais_scolarite' => 55000, 'annee_scolaire' => '2025-2026'],
        ];

        foreach ($classesData as $data) {
            $classe = Classe::create($data);
            $classe->matieres()->attach(Matiere::pluck('id'));
        }

        // ==================== ÉLÈVES (exemple) ====================
        $this->command->info('✅ Seeder exécuté avec succès !');
        $this->command->info('👤 Gestionnaire → gestionnaire@ecole.bf / password');
        $this->command->info('👤 Enseignant → enseignant@ecole.bf / password');
    }
}