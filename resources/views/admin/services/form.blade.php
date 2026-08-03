@extends('layouts.admin')
@section('heading',$service->exists ? 'Modifier le service' : 'Nouveau service')
@section('eyebrow','Fiche détaillée')
@section('content')
<form class="admin-form admin-panel" method="post" action="{{ $service->exists ? route('admin.services.update',$service) : route('admin.services.store') }}">@csrf @if($service->exists)@method('PUT')@endif
<div class="field-row"><label>Nom *<input name="name" value="{{ old('name',$service->name) }}" required></label><label>Slug<input name="slug" value="{{ old('slug',$service->slug) }}" placeholder="Généré depuis le nom"></label></div>
<label>Positionnement<input name="eyebrow" value="{{ old('eyebrow',$service->eyebrow) }}" placeholder="Ex. Infrastructure"></label>
<label>Résumé *<textarea name="summary" rows="3" required>{{ old('summary',$service->summary) }}</textarea></label>
<label>Description détaillée *<textarea name="description" rows="7" required>{{ old('description',$service->description) }}</textarea></label>
<label>Livrables <small>Un élément par ligne</small><textarea name="deliverables" rows="6">{{ old('deliverables',implode("\n",$service->deliverables ?? [])) }}</textarea></label>
<label>URL de l’image<input type="url" name="image" value="{{ old('image',$service->image) }}"></label>
<label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$service->exists ? $service->is_published : true))><span>Publier ce service</span></label>
<div class="button-row"><button class="btn btn-primary">Enregistrer</button><a class="btn btn-outline" href="{{ route('admin.services.index') }}">Annuler</a></div></form>
@if($service->exists)<form method="post" action="{{ route('admin.services.destroy',$service) }}" onsubmit="return confirm('Supprimer définitivement ce service ?')">@csrf @method('DELETE')<button class="link-button">Supprimer le service</button></form>@endif
@endsection
