<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParentEleve; // 🟢 CORRECTION : Importation du vrai modèle utilisé dans ton projet
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ParentAuthController extends Controller
{
    /**
     * Connexion du parent et génération du Token Sanctum
     */
    public function login(Request $request)
    {
        // 1. Validation des données reçues depuis l'application mobile
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. 🟢 CORRECTION : Utilisation de ParentEleve au lieu de ParentModel
        $parent = ParentEleve::where('email', $request->email)->first();

        // 3. Vérification du mot de passe
        if (!$parent || !Hash::check($request->password, $parent->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        // 4. Génération du Token d'accès sécurisé
        $token = $parent->createToken('PARENT_MOBILE_TOKEN')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Connexion réussie !',
            'token' => $token,
            'parent' => [
                'id' => $parent->id,
                'nom' => $parent->nom,
                'prenom' => $parent->prenom,
                'email' => $parent->email
            ], 
            'enfants' => $enfants
        ], 200);
    }

    /**
     * Déconnexion (Révocation du Token actuel)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Déconnexion réussie.'
        ], 200);
    }

    /**
     * Modification du mot de passe du parent
     */
    public function changePassword(Request $request)
    {
        // 🟢 Note : Côté Android, assure-toi de passer "current_password" et "new_password" dans ta Map
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6', // Retrait de confirmed si tu n'as qu'un seul champ de saisie sur le mobile
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $parent = $request->user();

        // Vérification de l'ancien mot de passe
        if (!Hash::check($request->current_password, $parent->password)) {
            return response()->json([
                'status' => false,
                'message' => 'L\'ancien mot de passe est incorrect.'
            ], 401);
        }

        // Mise à jour du nouveau mot de passe haché
        $parent->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Mot de passe modifié avec succès !'
        ], 200);
    }
}