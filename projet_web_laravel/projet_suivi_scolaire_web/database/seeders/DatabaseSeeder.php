<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParentEleve;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere; // <-- AJOUT : Importation du modèle Matiere
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // <-- AJOUT : Importation de l'outil Str

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Création du compte GESTIONNAIRE
        User::updateOrCreate(
            ['email' => 'gestionnaire@ecole.bf'], 
            [
                'name' => 'Traoré Abdoul Latif',
                'password' => Hash::make('password'), 
                'role' => 'gestionnaire',
            ]
        );

        // 2. Création du compte ENSEIGNANT
        User::updateOrCreate(
            ['email' => 'mariamkone@ecole.bf'],
            [
                'name' => 'Koné Mariam',
                'password' => Hash::make('password'), 
                'role' => 'enseignant',
            ]
        );

        // 3. Appel du seeder de classes
        $this->call(ClasseSeeder::class,);
        $classe = Classe::first() ?? Classe::create(['libelle' => 'CP1', 'slug' => 'cp1']);
          
        $eleve = Eleve::updateOrCreate(
            ['id' => 1],
            [
                'matricule' => 'MAT-2026-0001',
                'nom' => 'OUEDRAOGO',
                'prenom' => 'Junior',
                'classe_id' => $classe->id,
                'date_naissance' => '2012-05-15',
                'sexe' => 'M',
                'nom_tuteur'     => 'OUEDRAOGO Adama',
                'telephone_tuteur' => '+226 71717171',
                'date_inscription' => '2026-06-01',
            ]
        );

        $parent = ParentEleve::updateOrCreate(
            ['email' => 'adamaouedraogo@gmail.bf'],
            [
                'id' => 1,
                'nom' => 'OUEDRAOGO',
                'prenom' => 'Adama',
                'telephone' => '+226 71717171',
                'password' => Hash::make('password123'), // Correspond à ta saisie mobile !
            ]
        );

        // 4. Maintenant que les deux existent à 100%, on fait la liaison !
        if ($parent && $eleve) {
            $eleve->update([
                'parent_id' => $parent->id 
            ]);
            $this->command->info('--- ✅ Liaison Parent-Enfant effectuée avec succès ! ---');
        } else {
            $this->command->error('❌ Erreur : Parent ou Élève manquant.');
        }
    }    
}