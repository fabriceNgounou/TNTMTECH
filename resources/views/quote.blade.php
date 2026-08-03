@extends('layouts.app')
@section('title', 'Demander un devis — TNTMTECH')
@section('content')
<section class="form-page"><div class="container form-layout">
<div class="form-intro"><p class="eyebrow">Parlons de votre projet</p><h1>Recevez une proposition adaptée à vos besoins.</h1><p>Décrivez votre contexte. Un spécialiste de l’agence la plus proche vous recontactera pour préciser la solution.</p><div class="contact-points"><span><strong>Réponse qualifiée</strong><small>Votre demande est orientée vers le bon expert.</small></span><span><strong>Confidentialité</strong><small>Vos informations restent accessibles aux équipes autorisées.</small></span><span><strong>Sans engagement</strong><small>La demande de devis est gratuite.</small></span></div></div>
<form class="form-panel" action="{{ route('quote.store') }}" method="post" enctype="multipart/form-data">@csrf<h2>Votre demande</h2>
<div class="field-row"><label>Nom complet *<input name="name" value="{{ old('name') }}" required></label><label>Entreprise<input name="company" value="{{ old('company') }}"></label></div>
<div class="field-row"><label>Email professionnel *<input type="email" name="email" value="{{ old('email') }}" required></label><label>Téléphone / WhatsApp *<input name="phone" value="{{ old('phone') }}" required></label></div>
<div class="field-row"><label>Ville *<select name="city" required><option>Douala</option><option>Yaoundé</option><option>Autre</option></select></label><label>Service *<select name="service" required><option value="">Sélectionner</option>@foreach($services as $service)<option value="{{ $service->name }}" @selected(old('service',$selectedService) === $service->name)>{{ $service->name }}</option>@endforeach</select></label></div>
<label>Décrivez votre besoin *<textarea name="description" rows="6" required placeholder="Objectif, contexte, nombre d’utilisateurs, contraintes...">{{ old('description') }}</textarea></label>
<div class="field-row"><label>Budget indicatif<select name="budget"><option value="">Non défini</option><option>Moins de 500 000 FCFA</option><option>500 000 à 2 000 000 FCFA</option><option>Plus de 2 000 000 FCFA</option></select></label><label>Date souhaitée<input type="date" name="deadline" value="{{ old('deadline') }}"></label></div>
<label>Pièce jointe <small>PDF, image ou document · 5 Mo max.</small><input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
<label class="check"><input type="checkbox" name="consent" required><span>J’accepte que TNTMTECH utilise ces informations pour traiter ma demande.</span></label><button class="btn btn-primary btn-block">Envoyer ma demande</button>
</form></div></section>
@endsection
