@extends('layouts.admin')
@section('heading','Tableau de bord')
@section('eyebrow','Pilotage des prestations')
@section('header-action')
<div class="dashboard-actions"><form method="get" class="period-select"><label for="period">Période</label><select id="period" name="period" onchange="this.form.submit()">@foreach([7=>'7 jours',30=>'30 jours',90=>'90 jours',365=>'12 mois'] as $value=>$label)<option value="{{ $value }}" @selected($days === $value)>{{ $label }}</option>@endforeach</select></form><a class="btn btn-primary" href="{{ route('admin.services.create') }}">Nouveau service</a></div>
@endsection
@section('content')
<div class="metric-grid">
    <article class="metric-primary"><span>Demandes de devis</span><strong>{{ $metrics['quotes'] }}</strong><small>Sur les {{ $days }} derniers jours</small></article>
    <article><span>Nouvelles demandes</span><strong>{{ $metrics['newQuotes'] }}</strong><small>À qualifier et assigner</small></article>
    <article><span>Services publiés</span><strong>{{ $metrics['publishedServices'] }}</strong><small>Sur {{ $metrics['services'] }} fiches</small></article>
    <article><span>Devis clôturés</span><strong>{{ $metrics['quoteConversion'] }}%</strong><small>Sur la période sélectionnée</small></article>
</div>
<div class="admin-grid dashboard-tables">
    <section class="admin-panel"><div class="panel-title"><div><h2>Devis à suivre</h2><p>Dernières demandes de prestations</p></div><a href="{{ route('admin.quotes.index') }}">Tous les devis</a></div><div class="table-wrap"><table><thead><tr><th>Référence</th><th>Client</th><th>Service</th><th>Statut</th></tr></thead><tbody>
    @forelse($quotes as $quote)<tr><td><a href="{{ route('admin.quotes.show',$quote) }}">{{ $quote->reference }}</a><small>{{ $quote->created_at->format('d/m/Y') }}</small></td><td>{{ $quote->name }}<small>{{ $quote->city }}</small></td><td>{{ $quote->service }}</td><td><span class="status">{{ $quote->status }}</span></td></tr>@empty<tr><td colspan="4">Aucune demande enregistrée.</td></tr>@endforelse
    </tbody></table></div></section>
    <section class="admin-panel"><div class="panel-title"><div><h2>Files entrantes</h2><p>Éléments restant à traiter</p></div></div><div class="operational-metrics"><article><span>Messages</span><strong>{{ $metrics['messages'] }}</strong></article><article><span>Préinscriptions</span><strong>{{ $metrics['registrations'] }}</strong></article><article><span>Candidatures</span><strong>{{ $metrics['applications'] }}</strong></article></div></section>
</div>
<section class="admin-panel" id="activite"><div class="panel-title"><div><h2>Activité entrante</h2><p>Messages, formations et candidatures</p></div></div><div class="inbox-columns">
<div><h3>Messages <span>{{ $metrics['messages'] }}</span></h3>@forelse($messages as $message)<article><div><strong>{{ $message->name }}</strong><small>{{ ucfirst($message->agency) }} · {{ $message->subject }}</small></div><a href="mailto:{{ $message->email }}">Répondre</a></article>@empty<p>Aucun message.</p>@endforelse</div>
<div><h3>Formations <span>{{ $metrics['registrations'] }}</span></h3>@forelse($registrations as $registration)<article><div><strong>{{ $registration->name }}</strong><small>{{ $registration->training->title }} · {{ $registration->city }}</small></div><a href="tel:{{ $registration->phone }}">Appeler</a></article>@empty<p>Aucune inscription.</p>@endforelse</div>
<div><h3>Candidatures <span>{{ $metrics['applications'] }}</span></h3>@forelse($applications as $application)<article><div><strong>{{ $application->name }}</strong><small>{{ $application->job?->title ?? 'Spontanée' }} · {{ $application->city }}</small></div><a href="mailto:{{ $application->email }}">Contacter</a></article>@empty<p>Aucune candidature.</p>@endforelse</div>
</div></section>
@endsection
