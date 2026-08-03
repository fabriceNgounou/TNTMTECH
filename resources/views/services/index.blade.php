@extends('layouts.app')
@section('title', 'Services informatiques — TNTMTECH')
@section('content')
<section class="page-head page-head-image"><div class="container"><p class="eyebrow light">Expertise de terrain</p><h1>Des solutions techniques qui restent simples à exploiter.</h1><p>Du diagnostic au suivi, nos équipes structurent vos projets informatiques à Douala et Yaoundé.</p></div></section>
<section class="section container"><div class="service-cards">@foreach($services as $i => $service)<article><a href="{{ route('services.show', $service) }}"><span>0{{ $i + 1 }}</span><h2>{{ $service->name }}</h2><p>{{ $service->summary }}</p><b>Voir la fiche détaillée →</b></a><a class="btn btn-primary" href="{{ route('quote', ['service' => $service->slug]) }}">Demander ce service</a></article>@endforeach</div></section>
<section class="cta-band"><div class="container"><div><p class="eyebrow light">Votre projet</p><h2>Un besoin à cadrer ou une urgence à résoudre ?</h2></div><a href="{{ route('quote') }}" class="btn btn-light">Obtenir un devis</a></div></section>
@endsection
