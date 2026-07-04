<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classe;
use App\Models\Matiere;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==================== MATIÈRES ====================
        $matieres = [
            ['nom' => 'Lecture, Expression Ecrite et Orale', 'code' => 'FR'],
            ['nom' => 'Calculs et Opérations', 'code' => 'MATH'],
            ['nom' => 'Observation', 'code' => 'OBS'],
            ['nom' => 'Histoire-Géographie', 'code' => 'HG'],
            ['nom' => 'Dessin', 'code' => 'DES'],
            ['nom' => 'Education Morale et Civique', 'code' => 'EMC'],
            ['nom' => 'Anglais', 'code' => 'ANG'],
            ['nom' => 'Animation Sportive', 'code' => 'AS'],
            ['nom' => 'Activité Physique et Etucative', 'code' => 'APE'],
        ];


        foreach ($matieres as $item) {
            // 🟢 On stocke le résultat de l'écriture dans une variable $matiere
            $matiere = \App\Models\Matiere::updateOrCreate(
                ['code' => $item['code']], // Clé de vérification unique
                [
                    'nom'  => $item['nom'],
                    'slug' => \Illuminate\Support\Str::slug($item['nom']),
                ]
            );

            // 🟢 Maintenant $matiere existe bien et possède la propriété 'wasRecentlyCreated'
            if ($matiere->wasRecentlyCreated) {
                $this->command->line("✅ Matiere créée : {$item['nom']} (Code: {$item['code']})");
            } else {
                $this->command->line("🔄 Matiere mise à jour : {$item['nom']} (Code: {$item['code']})");
            }
        }

        $this->command->info('--- Seeding terminé avec succès ! ---');

        // Liste officielle des classes du cycle primaire (du CP1 au CM2)
        $classes = [
            ['nom' => 'CP1', 'capacite_max' => 30, 'frais_scolarite' => 35000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CP2', 'capacite_max' => 30, 'frais_scolarite' => 35000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CE1', 'capacite_max' => 35, 'frais_scolarite' => 40000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CE2', 'capacite_max' => 35, 'frais_scolarite' => 40000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CM1', 'capacite_max' => 30, 'frais_scolarite' => 45000, 'annee_scolaire' => '2025-2026'],
            ['nom' => 'CM2', 'capacite_max' => 30, 'frais_scolarite' => 45000, 'annee_scolaire' => '2025-2026'],
        ];

        foreach ($classes as $classe) {
            // updateOrCreate évite les doublons si tu lances la commande plusieurs fois
            Classe::updateOrCreate(
                ['nom' => $classe['nom'], 'annee_scolaire' => $classe['annee_scolaire']],
                [
                    'capacite_max' => $classe['capacite_max'],
                    'frais_scolarite' => $classe['frais_scolarite']
                ]
            );
        }
    }
}