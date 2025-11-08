<!-- Sidebar -->
<div id="sidebar"
  class="fixed top-0 left-0 z-50 h-full bg-white border-r border-gray-200 flex flex-col w-20 lg:w-64 transition-all duration-300">

  <!-- Header -->
  <header class="p-4 flex items-center justify-center lg:justify-start border-b border-gray-200">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-x-2">
      <!-- Logo Text (responsive) -->
      <span class="block lg:hidden text-xl font-bold text-gray-800">DL</span>
      <span class="hidden lg:block font-semibold text-lg text-gray-800">Djakarta Laundry</span>
    </a>
  </header>

  <!-- Navigation -->
  <nav class="flex-1 overflow-y-auto py-4 px-2 lg:px-3 text-sm text-gray-800 space-y-1">
    @php
        $menu = [
            ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'mdi:home-outline'],
            ['name' => 'Administrator', 'route' => 'administrator', 'icon' => 'mdi:account-circle-outline'],
            ['name' => 'Layanan', 'route' => 'service', 'icon' => 'mdi:cog-outline'],
            ['name' => 'Pelanggan', 'route' => 'customer', 'icon' => 'mdi:account-group-outline'],
            ['name' => 'Transaksi', 'route' => 'order', 'icon' => 'mdi:cart-outline'],
            ['name' => 'WhatsApp', 'route' => 'chat', 'icon' => 'mdi:message-outline'],
            ['name' => 'Laporan', 'route' => 'report', 'icon' => 'mdi:file-chart-outline'],
        ];
        $currentRoute = Route::currentRouteName();
    @endphp

    @foreach ($menu as $item)
      <a href="{{ route($item['route']) }}"
         class="group relative flex items-center gap-x-3.5 py-2 px-2.5 rounded-lg transition w-full
                {{ $currentRoute === $item['route'] ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-blue-50 hover:text-blue-600' }}">
        <iconify-icon icon="{{ $item['icon'] }}" width="20" height="20"></iconify-icon>
        <span class="hidden lg:inline">{{ $item['name'] }}</span>

        <!-- Tooltip (hanya muncul di mobile) -->
        <span class="absolute left-full ml-3 px-2 py-1 rounded-md bg-gray-800 text-white text-xs opacity-0 
                     group-hover:opacity-100 whitespace-nowrap transition duration-200
                     pointer-events-none lg:hidden">
          {{ $item['name'] }}
        </span>
      </a>
    @endforeach
  </nav>

  <!-- Footer -->
  <footer class="border-t border-gray-200 bg-gray-50 p-3">
    <div class="flex items-center justify-between lg:justify-between">

      <!-- User Info -->
      <div class="flex items-center gap-2">
        <div id="userInitial"
             class="flex items-center justify-center size-10 rounded-full bg-gray-800 text-white text-sm font-semibold">
          U
        </div>
        <div class="hidden lg:block">
          <span id="userName" class="block font-medium">User</span>
          <span id="userRole" class="block text-xs text-gray-500">Role</span>
        </div>
      </div>

      <!-- Logout Button Desktop -->
      <button id="logoutBtn"
              class="hidden lg:flex items-center gap-1 text-red-600 hover:bg-red-50 rounded-md px-3 py-2 text-sm font-medium transition">
        <iconify-icon icon="mdi:logout" width="18" height="18"></iconify-icon>
        Keluar
      </button>
    </div>

    <!-- Logout Button Mobile -->
    <button id="logoutBtnMobile"
            class="block lg:hidden mt-3 w-full text-red-600 hover:bg-red-50 rounded-md px-2.5 py-1 text-sm font-medium transition text-center">
      <iconify-icon icon="mdi:logout" width="18" height="18"></iconify-icon>
      Keluar
    </button>
  </footer>

</div>

@vite('resources/js/components/sidebar.js')
