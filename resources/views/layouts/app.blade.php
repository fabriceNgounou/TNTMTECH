<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TNTMTECH — Informatique, réseaux et solutions digitales')</title>
    <meta name="description" content="@yield('description', 'TNTMTECH conseille, connecte et accompagne les entreprises avec des services informatiques à Douala et Yaoundé.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <span>Services informatiques à Douala et Yaoundé</span>
            <span>Conseil · Déploiement · Assistance</span>
            <a href="tel:+237676388135">+237 676 38 81 35</a>
        </div>
    </div>
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand" aria-label="TNTMTECH, accueil">
                <span class="brand-mark">T</span>
                <span><strong>TNTMTECH</strong><small>Technology that moves you</small></span>
            </a>
            <button class="icon-btn menu-toggle" type="button" aria-label="Ouvrir le menu" data-menu-toggle>
                <span></span><span></span><span></span>
            </button>
            <nav class="main-nav" data-menu>
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Accueil</a>
                <a href="{{ route('page', 'entreprise') }}">Entreprise</a>
                <a href="{{ route('services.index') }}" @class(['active' => request()->routeIs('services.*')])>Services</a>
                <a href="{{ route('trainings.index') }}">Formations</a>
                <a href="{{ route('page', 'realisations') }}">Réalisations</a>
                <a href="{{ route('contact') }}">Contact</a>
            </nav>
            <div class="header-actions">
                <a class="btn btn-outline hide-mobile" href="{{ route('quote') }}">Demander un devis</a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="container"><div class="flash success">{{ session('success') }}</div></div>
    @endif
    @if($errors->any())
        <div class="container"><div class="flash error"><strong>Veuillez corriger les champs indiqués.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif

    <main>@yield('content')</main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a href="{{ route('home') }}" class="brand brand-light"><span class="brand-mark">T</span><span><strong>TNTMTECH</strong><small>Telecommunications & Mobiles Technology</small></span></a>
                <p>Réseaux, logiciels, cybersécurité, maintenance et formation. Une expertise locale à chaque étape.</p>
            </div>
            <div><h3>Explorer</h3><a href="{{ route('page', 'entreprise') }}">Entreprise</a><a href="{{ route('services.index') }}">Nos services</a><a href="{{ route('trainings.index') }}">Formations</a><a href="{{ route('page', 'realisations') }}">Réalisations</a><a href="{{ route('jobs.index') }}">Carrières</a></div>
            <div><h3>Informations</h3><a href="{{ route('information') }}">Informations</a><a href="{{ route('quote') }}">Demande de devis</a><a href="{{ route('page', 'confidentialite') }}">Confidentialité</a><a href="{{ route('login') }}">Administration</a></div>
            <div><h3>Nous joindre</h3><a href="tel:+237676388135">Douala · +237 676 38 81 35</a><a href="tel:+237650600990">Yaoundé · +237 650 60 09 90</a><a href="tel:+33756992282">France · +33 7 56 99 22 82</a></div>
        </div>
        <div class="container footer-bottom"><span>© {{ date('Y') }} TNTMTECH. Tous droits réservés.</span><span>Douala · Yaoundé · France</span></div>
    </footer>

    <button class="whatsapp-float" type="button" data-whatsapp-open aria-label="Contacter TNTMTECH sur WhatsApp"><svg><use href="#icon-whatsapp"/></svg></button>
    <dialog class="agency-dialog" data-whatsapp-dialog>
        <button class="dialog-close" data-whatsapp-close aria-label="Fermer">×</button>
        <p class="eyebrow">Contact direct</p><h2>Choisissez votre agence</h2>
        <div class="agency-list">
            @foreach($agencies as $key => $agency)
                <a href="https://wa.me/{{ $agency['whatsapp'] }}?text={{ rawurlencode('Bonjour TNTMTECH, je souhaite obtenir des informations.') }}" target="_blank">
                    <strong>{{ $agency['name'] }}</strong><span>{{ $agency['phone'] }}</span>
                </a>
            @endforeach
        </div>
    </dialog>

    <svg class="svg-sprite" aria-hidden="true">
        <symbol id="icon-whatsapp" viewBox="0 0 24 24"><path d="M20.5 11.7a8.5 8.5 0 0 1-12.6 7.5L3 20.5l1.3-4.7A8.5 8.5 0 1 1 20.5 11.7Z"/><path d="M8 7.5c.3-.6.6-.6 1-.6l.5.1.9 2c.1.3 0 .5-.2.8l-.7.8c-.2.2-.1.5 0 .7.8 1.4 1.9 2.5 3.4 3.2.3.1.5.1.7-.1l.9-1.1c.2-.2.5-.3.8-.2l1.9.9c.3.1.5.3.5.5 0 .5-.2 1.4-.6 1.8-.5.5-1.3.8-2.1.8-1.1 0-2.8-.6-4.8-2.3-2.3-2-3.5-4.4-3.5-5.7 0-.6.4-1.2 1.3-1.5Z"/></symbol>
    </svg>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
