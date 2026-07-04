<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ParentEleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Connexion et génération du Token Sanctum
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $parent = ParentEleve::where('email', $request->email)->first();

        if (! $parent || ! Hash::check($request->password, $parent->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        $token = $parent->createToken('PARENT_MOBILE_TOKEN')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Connexion réussie !',
            'token'  => $token,
            'parent'   => [
                'id'        => $parent->id,
                'nom'       => $parent->nom,
                'prenom'    => $parent->prenom,
                'email'     => $parent->email,
                'telephone' => $parent->telephone,
                'photo'     => $parent->photo ? asset('storage/' . $parent->photo) : null,
            ],
        ], 200);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Déconnecté avec succès.'
        ], 200);
    }

    /**
     * Mise à jour du mot de passe (s'aligne sur @PUT("password") en Kotlin)
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $parent = $request->user();

        if (! Hash::check($request->current_password, $parent->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Mot de passe actuel incorrect.'
            ], 401);
        }

        $parent->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Mot de passe mis à jour avec succès.'
        ], 200);
    }

    /**
     * Récupération du profil et des enfants associés
     */
    public function profil(Request $request)
    {
        // 🟢 CORRECTION : Utilisation de la relation 'enfants' définie dans ton projet
        $parent = $request->user()->load('enfants.classe');

        return response()->json([
            'status' => true,
            'parent' => [
                'id' => $parent->id,
                'nom' => $parent->nom,
                'prenom' => $parent->prenom,
                'email' => $parent->email,
            ],
            'eleves' => $parent->enfants->map(fn($e) => [
                'id'      => $e->id,
                'nom'     => $e->nom,
                'prenom'  => $e->prenom,
                'classe'  => $e->classe->libelle ?? 'Non assignée',
                'photo'   => $e->photo ? asset('storage/' . $e->photo) : null,
            ]),
        ], 200);
    }
}