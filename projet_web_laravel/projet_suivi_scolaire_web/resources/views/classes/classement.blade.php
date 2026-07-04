@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Classement - {{ $classe->nom }}</h1>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-6 py-4 text-left">Rang</th>
                    <th class="px-6 py-4 text-left">Matricule</th>
                    <th class="px-6 py-4 text-left">Élève</th>
                    <th class="px-6 py-4 text-center">Moyenne</th>
                    <th class="px-6 py-4 text-center">Trimestre</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($eleves as $index => $eleve)
                <tr class="{{ $index < 3 ? 'bg-amber-50' : '' }}">
                    <td class="px-6 py-4 text-center font-bold text-lg">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4 font-mono">{{ $eleve->matricule }}</td>
                    <td class="px-6 py-4">{{ $eleve->nom }} {{ $eleve->prenom }}</td>
                    <td class="px-6 py-4 text-center font-bold text-xl">
                        {{ number_format($eleve->notes_avg ?? 0, 2) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if(request('trimestre'))
                            {{ request('trimestre') }}<sup>e</sup> Trimestre
                        @else
                            Général
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection