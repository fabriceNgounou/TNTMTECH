@extends('layouts.app')
@section('title', 'Carrières — TNTMTECH')
@section('content')
<section class="page-head"><div class="container"><p class="eyebrow">Carrières</p><h1>Mettez votre expertise en mouvement.</h1><p>Rejoignez une équipe qui transforme les besoins de terrain en solutions utiles.</p></div></section>
<section class="section container"><div class="jobs-list">@forelse($jobs as $job)<a href="{{ route('jobs.show', $job) }}"><div><span>{{ $job->city }} · {{ $job->contract_type }}</span><h2>{{ $job->title }}</h2></div><b>Voir le poste →</b></a>@empty<div class="empty-state"><h2>Aucun poste ouvert actuellement</h2><p>Vous pouvez néanmoins nous transmettre une candidature spontanée.</p></div>@endforelse</div><div class="spontaneous"><h2>Candidature spontanée</h2><p>Présentez-nous votre profil et le type de mission recherché.</p><a class="btn btn-outline" href="{{ route('contact') }}">Contacter l’équipe RH</a></div></section>
@endsection
