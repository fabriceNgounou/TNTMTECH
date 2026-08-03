@extends('layouts.admin')
@section('heading','Services')
@section('eyebrow','Offre de prestations')
@section('header-action')<a class="btn btn-primary" href="{{ route('admin.services.create') }}">Nouveau service</a>@endsection
@section('content')
<section class="admin-panel"><div class="table-wrap"><table><thead><tr><th>Service</th><th>Positionnement</th><th>Publication</th><th></th></tr></thead><tbody>
@forelse($services as $service)<tr><td><strong>{{ $service->name }}</strong><small>/services/{{ $service->slug }}</small></td><td>{{ $service->eyebrow }}</td><td><span class="status">{{ $service->is_published ? 'Publié' : 'Brouillon' }}</span></td><td><a href="{{ route('admin.services.edit',$service) }}">Modifier</a></td></tr>@empty<tr><td colspan="4">Aucun service.</td></tr>@endforelse
</tbody></table></div>{{ $services->links() }}</section>
@endsection
