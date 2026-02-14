   <div class="sidebar-bg w-64 min-h-screen p-6 hidden md:block">
       <div class="flex items-center mb-10">
           <div class="bg-blue-500 p-3 rounded-full mr-4">
               <i class="fas fa-shield-alt text-white text-2xl"></i>
           </div>
           <div>
               <h1 class="text-xl font-bold">Admin Panel</h1>
               <p class="text-blue-300 text-sm">Klinik Anugrah Farma</p>
           </div>
       </div>

       <nav class="space-y-2">

           <a href="{{ route('admin.dashbord') }}"
               class="nav-link {{ request()->routeIs('admin.dashbord') ? 'active' : '' }} flex items-center p-3 rounded-lg">
               <i class="fas fa-tachometer-alt mr-3 text-blue-400"></i>
               Dashboard
           </a>

           <a href="{{ route('admin.petugas') }}"
               class="nav-link {{ request()->routeIs('admin.petugas') ? 'active' : '' }} flex items-center p-3 rounded-lg">
               <i class="fas fa-users mr-3 text-green-400"></i>
               Petugas
           </a>

           <a href="{{ route('admin.antrian') }}"
               class="nav-link {{ request()->routeIs('admin.antrian') ? 'active' : '' }} flex items-center p-3 rounded-lg">
               <i class="fas fa-list-ol mr-3 text-purple-400"></i>
               Antrian
           </a>

       </nav>


   </div>