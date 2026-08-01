@extends('layouts.app')
@section('title', 'Formations professionnelles — TNTMTECH')
@section('content')
<section class="page-head"><div class="container"><p class="eyebrow">Développer les compétences</p><h1>Des formations ancrées dans la pratique.</h1><p>Bureautique, systèmes, réseaux et web pour particuliers, équipes et entreprises.</p></div></section>
<section class="section container"><div class="training-grid">@foreach($trainings as $training)<article><div><span>{{ $training->code }}</span><span>{{ $training->format }}</span></div><h2>{{ $training->title }}</h2><p>{{ $training->summary }}</p><dl><div><dt>Durée</dt><dd>{{ $training->duration }}</dd></div><div><dt>Tarif</dt><dd>{{ $training->price ? number_format($training->price, 0, ',', ' ').' FCFA' : 'Sur devis' }}</dd></div></dl><a class="text-link" href="{{ route('trainings.show', $training) }}">Voir le programme →</a></article>@endforeach</div></section>
@endsection
