<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SEDApp') - Sistema de Gestión</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js para interactividad -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Estilos personalizados -->
    <style>
        [x-cloak] { display: none !important; }
        
        .sidebar-active {
            background-color: #1e40af;
            color: white;
        }
        
        .sidebar-item:hover {
            background-color: #f3f4f6;
        }
        
        .sidebar-active:hover {
            background-color: #1d4ed8;
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .btn-primary {
            background-color: #1e40af;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        
        .btn-danger {
            background-color: #dc2626;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #b91c1c;
        }
        
        .btn-success {
            background-color: #059669;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #047857;
        }
        
        .status-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-en-proceso {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-completada {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelada {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .role-admin {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }
        
        .role-supervisor {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
        }
        
        .role-tecnico {
            background: linear-gradient(135deg, #059669, #047857);
        }
        
        .role-cliente {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-blue-600">
                <span class="text-white text-xl font-bold">SEDApp</span>
            </div>
            
            <!-- User Role Badge -->
            @auth
            <div class="px-4 py-3 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full role-{{ auth()->user()->role->name ?? 'cliente' }} flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">
                            {{ auth()->user()->role->name ?? 'Sin rol' }}
                        </p>
                    </div>
                </div>
            </div>
            @endauth
            
            <!-- Navigation -->
            <nav class="mt-4">
                <div class="px-4 space-y-2">
                    <!-- Dashboard - Todos los roles -->
                    <a href="{{ route('dashboard') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    @auth
                    @php
                        $userRole = auth()->user()->role->name ?? null;
                    @endphp
                    
                    <!-- Navegación para ADMINISTRADORES -->
                    @if($userRole === 'Administrador')
                        <a href="{{ route('usuarios.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('usuarios.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            Gestión Usuarios
                        </a>
                        
                        <a href="{{ route('roles.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('roles.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Roles
                        </a>
                        
                        <a href="{{ route('ordenes.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('ordenes.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Todas las Órdenes
                        </a>
                        
                        <a href="{{ route('evidencias.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('evidencias.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Todas las Evidencias
                        </a>
                        
                        <a href="{{ route('zonas.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('zonas.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Zonas
                        </a>
                    @endif
                    
                    <!-- Navegación para SUPERVISORES -->
                    @if($userRole === 'Supervisor')
                        <a href="{{ route('ordenes.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('ordenes.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Órdenes de Corte
                        </a>
                        
                        <a href="{{ route('evidencias.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('evidencias.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Evidencias
                        </a>
                        
                        <a href="{{ route('zonas.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('zonas.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Zonas
                        </a>

                    @endif
                    
                    <!-- Navegación para TÉCNICOS -->
                    @if($userRole === 'Tecnico')
                        <a href="{{ route('mis-ordenes.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('mis-ordenes.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Mis Órdenes
                        </a>
                    @endif
                    
                    <!-- Navegación para CLIENTES -->
                    @if($userRole === 'cliente')
                        <a href="{{ route('mis-ordenes.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('mis-ordenes.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Mis Órdenes
                        </a>
                        
                        <a href="{{ route('mis-evidencias.index') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('mis-evidencias.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Mis Evidencias
                        </a>
                    @endif
                    
                    <!-- Navegación común para todos los roles -->
                    <a href="{{ route('documentos.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('documentos') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Documentos
                    </a>
                    
                    <a href="{{ route('mensajeria.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg transition-colors {{ request()->routeIs('mensajeria') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Mensajería
                    </a>
                    @endauth
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Activity Bell - Solo para Admin y Supervisor -->
                        @auth
                        @if(in_array(auth()->user()->role->name ?? '', ['Administrador', 'Supervisor']))
                        <button onclick="openActivityModal()" class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l3.12 3.12M7.05 5.84l3.12 3.12M4.03 8.86l3.12 3.12M1.01 11.88l3.12 3.12"/>
                            </svg>
                            <span id="activity-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">!</span>
                        </button>
                        @endif
                        @endauth
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900 transition-colors">
                                <div class="w-8 h-8 rounded-full role-{{ auth()->user()->role->name ?? 'cliente' }} flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium">{{ auth()->user()->name ?? 'Usuario' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded fade-in">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded fade-in">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded fade-in">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Activity Modal - Solo para Admin y Supervisor -->
    @auth
    @if(in_array(auth()->user()->role->name ?? '', ['Administrador', 'Supervisor']))
    <div id="activity-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
                    <button onclick="closeActivityModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="activity-list" class="max-h-96 overflow-y-auto space-y-3">
                    <!-- Activities will be populated here -->
                </div>
                <div class="mt-4 flex justify-end">
                    <button onclick="clearActivity()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                        Limpiar Todo
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth
    
    <!-- Activity System JavaScript -->
    <script>
        // Activity tracking system
        let activities = JSON.parse(localStorage.getItem('sedapp_activities') || '[]');
        
        function addActivity(description) {
            const activity = {
                id: Date.now(),
                description: description,
                timestamp: new Date().toISOString(),
                user: '{{ auth()->user()->name ?? "Usuario" }}'
            };
            
            activities.unshift(activity);
            if (activities.length > 50) {
                activities = activities.slice(0, 50);
            }
            
            localStorage.setItem('sedapp_activities', JSON.stringify(activities));
            updateActivityBadge();
        }
        
        function updateActivityBadge() {
            const badge = document.getElementById('activity-badge');
            if (badge && activities.length > 0) {
                badge.style.display = 'flex';
                badge.textContent = activities.length > 9 ? '9+' : activities.length;
            } else if (badge) {
                badge.style.display = 'none';
            }
        }
        
        function openActivityModal() {
            const modal = document.getElementById('activity-modal');
            const list = document.getElementById('activity-list');
            
            if (!modal || !list) return;
            
            list.innerHTML = '';
            
            if (activities.length === 0) {
                list.innerHTML = '<p class="text-gray-500 text-center py-4">No hay actividades recientes</p>';
            } else {
                activities.forEach(activity => {
                    const div = document.createElement('div');
                    div.className = 'bg-gray-50 p-3 rounded-lg';
                    div.innerHTML = `
                        <p class="text-sm text-gray-900">${activity.description}</p>
                        <p class="text-xs text-gray-500 mt-1">${new Date(activity.timestamp).toLocaleString()}</p>
                    `;
                    list.appendChild(div);
                });
            }
            
            modal.classList.remove('hidden');
        }
        
        function closeActivityModal() {
            const modal = document.getElementById('activity-modal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }
        
        function clearActivity() {
            activities = [];
            localStorage.removeItem('sedapp_activities');
            updateActivityBadge();
            closeActivityModal();
        }
        
        // Initialize activity badge on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateActivityBadge();
        });
        
        // Make addActivity globally available
        window.addActivity = addActivity;
    </script>
</body>
</html>
