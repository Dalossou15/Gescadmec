<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion des Étudiants')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #5fb4ecc5 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,.2);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card-stats {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,.1);
            transition: transform 0.3s ease;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #5fb4ecc5 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,.2);
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        .badge-status {
            font-size: 0.75rem;
            padding: 0.5em 1em;
            border-radius: 20px;
        }
        .progress-payment {
            height: 8px;
            border-radius: 4px;
        }
    </style>
    
    @stack('styles')
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- CSS de l'application -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>
<body class="font-sans antialiased">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold">
                            <i class="bi bi-mortarboard-fill me-2"></i>
                            Gescadmec
                        </h4>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Tableau de bord
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <i class="bi bi-people me-2"></i>
                            Étudiants
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('inscriptions.*') ? 'active' : '' }}" href="{{ route('inscriptions.index') }}">
                            <i class="bi bi-journal-text me-2"></i>
                            Inscriptions
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                            <i class="bi bi-credit-card me-2"></i>
                            Paiements
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('needs.*') ? 'active' : '' }}" href="{{ route('needs.index') }}">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Besoins
                        </a>
                        
                        <hr class="text-white-50">
                        
                        <a class="nav-link {{ request()->routeIs('reports.balance') ? 'active' : '' }}" href="{{ route('reports.balance') }}">
                            <i class="bi bi-graph-up me-2"></i>
                            Rapports
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('reports.payments-by-level') ? 'active' : '' }}" href="{{ route('reports.payments-by-level') }}">
                            <i class="bi bi-cash-stack me-2"></i>
                            Paiements par Niveau
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content">
                    <!-- Top Navigation -->
                    <nav class="navbar navbar-light bg-white shadow-sm">
                        <div class="container-fluid">
                            <span class="navbar-brand mb-0 h1 text-primary fw-bold">
                                @yield('page-title', 'Tableau de bord')
                            </span>
                            
                            <div class="d-flex align-items-center">
                                <span class="text-muted me-3">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ now()->format('d/m/Y') }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-1"></i>
                                        @auth
                                            {{ Auth::user()->name }}
                                        @else
                                            Administrateur
                                        @endauth
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        @auth
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('login') }}">
                                                    <i class="bi bi-box-arrow-in-right me-2"></i>Connexion
                                                </a>
                                            </li>
                                        @endauth
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Content Area -->
                    <div class="container-fluid py-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- JS de l'application -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>