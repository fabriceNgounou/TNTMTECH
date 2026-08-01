<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield('title','Administration') · TNTMTECH</title><link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('css/app.css') }}"></head>
<body class="admin-body">
<aside class="admin-sidebar">
    <a class="brand brand-light" href="{{ route('admin.dashboard') }}"><span class="brand-mark">T</span><span><strong>TNTMTECH</strong><small>Administration</small></span></a>
    <nav><a href="{{ route('admin.dashboard') }}">Vue d’ensemble</a><a href="{{ route('admin.services.index') }}">Services</a><a href="{{ route('admin.quotes.index') }}">Demandes de devis</a><a href="{{ route('home') }}" target="_blank">Voir le site ↗</a></nav>
    <div class="admin-user"><span>{{ mb_substr(auth()->user()->name,0,1) }}</span><div><strong>{{ auth()->user()->name }}</strong><small>{{ str_replace('_',' ',auth()->user()->role) }}</small></div><form method="post" action="{{ route('logout') }}">@csrf<button>Déconnexion</button></form></div>
</aside>
<main class="admin-main">
    <header class="admin-header"><div><p class="eyebrow">@yield('eyebrow','Espace de gestion')</p><h1>@yield('heading','Administration')</h1></div>@yield('header-action')</header>
    @if(session('success'))<div class="flash success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="flash error"><strong>Impossible d’enregistrer.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>
<script src="{{ asset('js/app.js') }}" defer></script>@stack('scripts')</body></html>
