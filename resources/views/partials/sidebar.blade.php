<div id="sidebar">
  <div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
      <div class="d-flex justify-content-between align-items-center">
        <div class="w-100 mt-2 px-4">
          <a class="d-flex justify-content-start align-items-center" href="/"><img class="w-100 h-100" src={{ asset("img/logo.svg") }} alt="Logo" srcset="" /></a>
        </div>
       
        <div class="sidebar-toggler x">
          <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
        </div>
      </div>
    </div>
    <div class="sidebar-menu">
      <ul class="menu">
        <li class="sidebar-title">Menu</li>

        <li class="sidebar-item {{ Request::is('/') ? 'active' : '' }}">
          <a href="/" class="sidebar-link">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="sidebar-item {{ Request::is('data-skripsi*') ? 'active' : '' }}">
          <a href="/data-skripsi" class="sidebar-link">
            <i class="bi bi-file-text-fill"></i>
            <span>Data Skripsi</span>
          </a>
        </li>

        <li class="sidebar-item {{ Request::is('data-dosen*') ? 'active' : '' }}">
          <a href="/data-dosen" class="sidebar-link">
            <i class="bi bi-people-fill"></i>
            <span>Data Dosen</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>