<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>SEDAPP - @yield('title', 'Dashboard')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

  <!-- Styles -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
      /* Custom styles for scrollbar and other minor adjustments */
      .scroll-area-thumb {
          background-color: hsl(210 40% 96.1%); /* gray-200 */
          border-radius: 9999px; /* full rounded */
      }
      .scroll-area-thumb:hover {
          background-color: hsl(210 40% 90%); /* gray-300 */
      }
      .scroll-area-viewport {
          height: 100%;
          width: 100%;
      }
      .scroll-area-viewport > div {
          display: block !important; /* Override radix-ui default display: table */
      }
  </style>
</head>
<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-50 flex flex-col">
      <!-- Fixed Header -->
      <header class="bg-white border-b border-gray-200 px-6 py-4 fixed top-0 left-0 right-0 z-50">
          <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                  <div class="flex items-center space-x-2">
                      <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 relative overflow-hidden">
                          <div class="absolute inset-0 flex items-center justify-center">
                              <span class="text-gray-400 text-xs font-medium">IMG</span>
                          </div>
                          <img
                              src="{{ asset('public/seda.jpg') }}"
                              alt="SEDApp Logo"
                              class="w-full h-full object-contain"
                          />
                      </div>
                      <span class="text-xl font-bold text-gray-900">SEDAPP</span>
                  </div>
              </div>

              <div class="flex-1 max-w-md mx-8">
                  <div class="relative">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                      <input
                          type="text"
                          placeholder="Búsqueda"
                          class="flex h-9 w-full rounded-md border border-input bg-gray-50 px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-10 bg-gray-50 border-gray-200"
                          onchange="window.addActivity('buscó: &quot;' + this.value + '&quot;');"
                      />
                  </div>
              </div>

              <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                  <button @click="open = !open" type="button" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 flex items-center space-x-3 p-2">
                      <div class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full">
                          <span class="flex h-full w-full items-center justify-center rounded-full bg-orange-500 text-white">HS</span>
                      </div>
                      <div class="text-left">
                          <div class="text-sm font-medium">{{ Auth::user()->name ?? 'Henry Sagastegui' }}</div>
                          <div class="text-xs text-gray-500">Administrador</div>
                      </div>
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-more-vertical w-4 h-4"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                  </button>
                  <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-56 origin-top-right rounded-md border bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                      <div class="py-1" role="none">
                          <a href="#" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 w-full text-left" role="menuitem" tabindex="-1" id="menu-item-0" onclick="window.addActivity('accedió al perfil')">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user w-4 h-4 mr-2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                              Ir al perfil
                          </a>
                          <form method="POST" action="{{ route('logout') }}">
                              @csrf
                              <button type="submit" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 w-full text-left" role="menuitem" tabindex="-1" id="menu-item-1" onclick="window.addActivity('cerró sesión')">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out w-4 h-4 mr-2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                  Cerrar sesión
                              </button>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </header>

      <div class="flex pt-20">
          <!-- Fixed Sidebar -->
          <aside class="w-64 bg-white border-r border-gray-200 fixed left-0 top-20 bottom-0 overflow-hidden flex flex-col">
              <!-- Navigation -->
              <nav class="p-4 space-y-2 flex-shrink-0">
                  @php
                      $navigationItems = [
                          ['name' => 'Dashboard', 'icon' => 'BarChart3', 'path' => route('dashboard')],
                          ['name' => 'Gestión de usuarios', 'icon' => 'Users', 'path' => route('usuarios.index')],
                          ['name' => 'Gestión de roles', 'icon' => 'Settings', 'path' => route('roles.index')],
                          ['name' => 'Zonas', 'icon' => 'MapPin', 'path' => route('zonas.index')],
                          ['name' => 'Órdenes de corte', 'icon' => 'FileText', 'path' => route('orden-cortes.index')],
                          ['name' => 'Evidencias', 'icon' => 'Camera', 'path' => route('evidencias.index')],
                      ];
                  @endphp

                  @foreach($navigationItems as $item)
                      @php
                          $isActive = (request()->url() === $item['path']);
                          $iconComponent = 'lucide-' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $item['icon']));
                      @endphp
                      <a
                          href="{{ $item['path'] }}"
                          onclick="window.addActivity('navegó a {{ $item['name'] }}')"
                          class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-left transition-colors
                              {{ $isActive ? 'text-white border border-[#023A91]' : 'text-gray-600 hover:bg-gray-50' }}"
                          style="{{ $isActive ? 'background-color: #023A91;' : '' }}"
                      >
                          <svg class="lucide {{ $iconComponent }} w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              @if($item['icon'] === 'BarChart3')
                                  <path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/>
                              @elseif($item['icon'] === 'Users')
                                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                              @elseif($item['icon'] === 'Settings')
                                  <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 .73 2.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 .73 2.73l-.43.25a2 2 0 0 0-1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-.73-2.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-.73-2.73l.43-.25a2 2 0 0 0 1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>
                              @elseif($item['icon'] === 'MapPin')
                                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                              @elseif($item['icon'] === 'FileText')
                                  <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/>
                              @elseif($item['icon'] === 'Camera')
                                  <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3L14.5 4z"/><circle cx="12" cy="13" r="3"/>
                              @elseif($item['icon'] === 'MessageSquare')
                                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                              @endif
                          </svg>
                          <span class="text-sm">{{ $item['name'] }}</span>
                      </a>
                  @endforeach
              </nav>

              <!-- Recent Activities - Compact Design -->
              <div class="mx-4 mt-4 mb-6 flex-shrink-0">
                  <div
                      class="rounded-xl border bg-card text-card-foreground shadow bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200 cursor-pointer hover:shadow-md transition-all duration-200 h-64"
                      onclick="window.openActivityModal(); window.addActivity('accedió al historial de actividades')"
                  >
                      <div class="flex flex-col space-y-1.5 p-3 pb-2">
                          <h3 class="text-sm font-semibold flex items-center text-[#023A91]">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity w-4 h-4 mr-2"><path d="M22 12A10 10 0 1 1 12 2v10Z"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                              Actividades Recientes
                          </h3>
                      </div>
                      <div class="p-3 pt-0 h-48">
                          <div class="relative overflow-hidden h-full">
                              <div class="h-full w-full rounded-md" id="recent-activities-scroll-area">
                                  <div class="space-y-2 pr-2" id="recent-activities-list">
                                      <!-- Activities will be loaded here by JavaScript -->
                                  </div>
                              </div>
                              <div class="absolute inset-x-0 bottom-0 h-8 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                          </div>
                          <div class="mt-2 pt-2 border-t border-blue-200">
                              <p class="text-xs text-[#023A91] font-medium text-center">Clic para ver historial completo</p>
                          </div>
                      </div>
                  </div>
              </div>
          </aside>

          <!-- Main Content Area -->
          <main class="flex-1 ml-64 min-h-screen">
              @yield('content')
          </main>
      </div>

      <!-- Activity History Modal -->
      <div id="activity-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50" x-data="{ open: false }" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
          <div class="relative max-w-4xl w-[90vw] h-[80vh] flex flex-col p-0 rounded-lg bg-white shadow-lg" @click.outside="window.closeActivityModal()">
              <div class="flex-shrink-0 px-6 py-4 border-b flex items-center justify-between">
                  <h2 class="text-xl font-semibold" style="color: #023A91;">Historial de Actividades</h2>
                  <button type="button" onclick="window.closeActivityModal()" class="text-gray-400 hover:text-gray-600">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-6 h-6"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                  </button>
              </div>

              <div class="flex-1 min-h-0 flex flex-col">
                  <!-- Filters -->
                  <div class="flex-shrink-0 p-4 bg-gray-50 border-b">
                      <div class="flex items-center space-x-4">
                          <div class="flex items-center space-x-2">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4 text-gray-500"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                              <label for="date-range" class="text-sm">Rango de fecha:</label>
                              <select id="activity-date-range" onchange="window.filterActivities()" class="flex h-9 w-36 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50">
                                  <option value="all">Todas</option>
                                  <option value="today">Hoy</option>
                                  <option value="week">Última semana</option>
                                  <option value="month">Último mes</option>
                              </select>
                          </div>

                          <div class="flex items-center space-x-2 flex-1">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter w-4 h-4 text-gray-500"><path d="M22 3H2L10 12.46V19L14 21V12.46L22 3Z"/></svg>
                              <input
                                  type="text"
                                  placeholder="Buscar en actividades..."
                                  id="activity-search-term"
                                  oninput="window.filterActivities()"
                                  class="flex h-9 max-w-xs rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                              />
                          </div>

                          <button type="button" onclick="window.exportActivityHistory()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-primary-foreground shadow hover:bg-green-700 h-9 px-4 py-2">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                              Exportar CSV
                          </button>
                      </div>
                  </div>

                  <!-- Activity List - Scrollable Container -->
                  <div class="flex-1 min-h-0 p-4">
                      <div class="relative overflow-hidden h-full">
                          <div class="h-full w-full rounded-md" id="activity-modal-scroll-area">
                              <div class="space-y-3 pr-4" id="activity-modal-list">
                                  <!-- Activities will be loaded here by JavaScript -->
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Footer -->
                  <div class="flex-shrink-0 px-6 py-3 border-t bg-gray-50">
                      <div class="text-sm text-gray-500 text-center">
                          Total de actividades: <span class="font-medium" id="total-activities-count">0</span>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Footer -->
      <footer class="bg-white border-t border-gray-200 px-6 py-4 ml-64">
          <div class="text-center text-sm text-gray-500">© SedaChimbote 2025</div>
      </footer>
  </div>
</body>
</html>