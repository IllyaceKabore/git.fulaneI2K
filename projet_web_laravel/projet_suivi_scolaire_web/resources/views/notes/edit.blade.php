@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-xl">

    <div class="flex items-center gap-3 border-b border-gray-100 pb-5 mb-6">
        <span class="text-3xl">✏️</span>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Modifier la Note</h1>
            <p class="text-sm text-gray-500">Ajustement de la note d'un élève</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Informations de l'élève -->
    <div class="grid grid-cols-2 gap-6 mb-8 bg-gray-50 p-6 rounded-2xl">
        <div>
            <span class="block text-xs text-gray-500 mb-1">Élève</span>
            <p class="font-semibold text-gray-800">{{ $note->eleve->nom }} {{ $note->eleve->prenom }}</p>
        </div>
        <div>
            <span class="block text-xs text-gray-500 mb-1">Classe</span>
            <p class="font-semibold text-gray-800">{{ $note->eleve->classe->nom ?? 'Non assignée' }}</p>
        </div>
        <div>
            <span class="block text-xs text-gray-500 mb-1">Matière</span>
            <p class="font-semibold text-gray-800">{{ $note->matiere->nom }}</p>
        </div>
        <div>
            <span class="block text-xs text-gray-500 mb-1">Trimestre</span>
            <p class="font-semibold text-blue-600">Trimestre {{ $note->trimestre }}</p>
        </div>
    </div>

    <form action="{{ route('notes.update', $note) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nouvelle Note (sur 20)</label>
            <div class="relative">
                <input type="number" 
                       name="note" 
                       value="{{ old('note', $note->note) }}" 
                       step="0.25" 
                       min="0" 
                       max="20" 
                       required
                       class="w-full border border-gray-300 rounded-2xl px-5 py-4 text-2xl font-bold text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 font-medium">/ 20</div>
            </div>
        </div>

        <div class="flex gap-4 pt-6">
            <a href="{{ route('notes.index') }}" 
               class="flex-1 text-center py-4 border border-gray-300 rounded-2xl font-semibold hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-semibold transition">
                💾 Enregistrer la Modification
            </button>
        </div>
    </form>

</div>
@endsection