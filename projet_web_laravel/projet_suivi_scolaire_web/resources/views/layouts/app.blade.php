<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Gestion Scolaire') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    *, *::before, *::after { box-sizing: border-box; }

    body {
      margin: 0;
      background-color: #f3f4f6;
      font-family: sans-serif;
    }

    /* ── Layout ── */
    .app-shell {
      display: flex;
      min-height: 100vh;
      background-color: #f9fafb;
    }

    /* ── Sidebar ── */
    .sidebar {
      width: 16rem;
      background-color: #111827;
      color: #d1d5db;
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 50;
      border-right: 1px solid #1f2937;
      box-shadow: 4px 0 12px rgba(0, 0, 0, 0.3);
    }

    /* Logo */
    .sidebar-logo {
      height: 4rem;
      display: flex;
      align-items: center;
      padding: 0 1.5rem;
      background-color: #030712;
      border-bottom: 1px solid #1f2937;
      flex-shrink: 0;
    }

    .sidebar-logo a {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      font-size: 1.125rem;
      font-weight: 900;
      color: white;
      letter-spacing: -0.025em;
      opacity: 1;
      transition: opacity 0.2s;
    }

    .sidebar-logo a:hover { opacity: 0.9; }

    .sidebar-logo .logo-icon { font-size: 1.5rem; }
    .sidebar-logo .logo-name { color: #60a5fa; }

    /* Nav */
    .sidebar-nav {
      flex: 1;
      padding: 1.5rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 0.375rem;
      overflow-y: auto;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: #d1d5db;
      text-decoration: none;
      transition: background-color 0.2s, color 0.2s;
    }

    .nav-link:hover {
      background-color: #1f2937;
      color: white;
    }

    .nav-link.active {
      background-color: #2563eb;
      color: white;
      font-weight: 700;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .nav-link .nav-icon { font-size: 1rem; }

    /* User card */
    .sidebar-user {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.375rem 0.5rem;
      background-color: rgba(17, 24, 39, 0.5);
      border-radius: 0.75rem;
      border: 1px solid rgba(31, 41, 55, 0.6);
      margin: 0 0.5rem 0.5rem;
      flex-shrink: 0;
    }

    .user-avatar {
      width: 2rem;
      height: 2rem;
      border-radius: 50%;
      background-color: #2563eb;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 900;
      font-size: 0.875rem;
      text-transform: uppercase;
      flex-shrink: 0;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .user-info { flex: 1; min-width: 0; }

    .user-name {
      font-size: 0.75rem;
      font-weight: 700;
      color: white;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin: 0;
    }

    .user-email {
      font-size: 0.6875rem;
      color: #6b7280;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin: 0;
    }

    /* Logout */
    .sidebar-logout {
      padding: 1rem;
      border-top: 1px solid #1f2937;
      background-color: #030712;
      flex-shrink: 0;
    }

    .btn-logout {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      padding: 0.625rem 1rem;
      border-radius: 0.75rem;
      font-size: 0.875rem;
      font-weight: 600;
      color: #f87171;
      background-color: rgba(127, 29, 29, 0.3);
      border: 1px solid rgba(127, 29, 29, 0.5);
      cursor: pointer;
      transition: background-color 0.2s, color 0.2s;
    }

    .btn-logout:hover {
      background-color: rgba(127, 29, 29, 0.4);
      color: #fca5a5;
    }

    .btn-logout svg {
      width: 1rem;
      height: 1rem;
      flex-shrink: 0;
    }

    /* ── Main ── */
    .main-content {
      flex: 1;
      padding-left: 16rem;
    }

    /* Header */
    .main-header {
      height: 4rem;
      background-color: white;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .header-inner {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .header-pulse {
      font-size: 1.25rem;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50%       { opacity: 0.4; }
    }

    .header-title {
      font-size: 1rem;
      font-weight: 900;
      color: #1f2937;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin: 0;
    }

    .header-title span { color: #2563eb; }

    /* Page content */
    .page-content { padding: 2rem; }
  </style>
</head>
<body>      
  <div class="app-shell">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">

      <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}">
          <span class="logo-icon">🏫</span>
          <span class="logo-name">École PKK</span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
          <span class="nav-icon">📊</span> Tableau de Bord
        </a>
        <a href="{{ route('eleves.index') }}"
           class="nav-link {{ request()->routeIs('eleves.*') ? 'active' : '' }}">
          <span class="nav-icon">👨‍🎓</span> Élèves
        </a>
        <a href="{{ route('enseignants.index') }}"
           class="nav-link {{ request()->routeIs('enseignants.*') ? 'active' : '' }}">
          <span class="nav-icon">👨‍🏫</span> Enseignants
        </a>
        <a href="{{ route('classes.index') }}"
           class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
          <span class="nav-icon">🏫</span> Classes
        </a>
        <a href="{{ route('notes.saisie') }}"
           class="nav-link {{ request()->routeIs('notes.*') ? 'active' : '' }}">
          <span class="nav-icon">📝</span> Notes
        </a>
        
        <a href="{{ route('versements.index') }}"
          class="nav-link {{ request()->routeIs('versements.*') ? 'active' : '' }}">
          <span class="nav-icon">💵</span> Versements
        </a>

        <a href="{{ route('absences.index') }}" 
          class="nav-link {{ request()->routeIs('absences.*') ? 'active' : '' }}">
          <span class="nav-icon">🔴</span> Absences
        </a>

        <a href="{{ route('annonces.index') }}" 
          class="nav-link {{ request()->routeIs('annonces.*') ? 'active' : '' }}">
          <span class="nav-icon">📢</span> Annonces
        </a>
        <a href="{{ route('parents.index') }}" 
          class="nav-link {{ request()->routeIs('parents.*') ? 'active' : '' }}">
          <span class="nav-icon">👨‍👩‍👧‍👦</span> Parents/Tuteurs
        </a>
      </nav>

      <div class="sidebar-user">
        <div class="user-avatar">
          {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
        </div>
        <div class="user-info">
          <p class="user-name">{{ auth()->user()->name ?? 'Utilisateur' }}</p>
          <p class="user-email">{{ auth()->user()->email }}</p>
        </div>
      </div>

      <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn-logout">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Déconnexion
          </button>
        </form>
      </div>

    </aside>

    {{-- ── Main ── --}}
    <main class="main-content">
      <header class="main-header">
        <div class="header-inner">
          <span class="header-pulse">✨</span>
          <h2 class="header-title">
            École Primaire <span>Koudoubi Kaboré</span>
          </h2>
        </div>
      </header>

      <div class="page-content">
        @yield('content')
      </div>
    </main>

  </div>
</body>
</html>