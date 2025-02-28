<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link {{ Request::is('/') ? 'active' : 'collapsed' }}" href="/">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Pemasukan*') ? 'active' : 'collapsed' }}" href="/Pemasukan">
        <i class="ri-article-fill"></i>
        <span>Pemasukan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Pengeluaran*') ? 'active' : 'collapsed' }}" href="/Pengeluaran">
        <i class="ri-article-fill"></i>
        <span>Pengeluaran</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Penyusutan*') ? 'active' : 'collapsed' }}" href="/Penyusutan">
        <i class="ri-article-fill"></i>
        <span>Penyusutan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Karyawan*') ? 'active' : 'collapsed' }}" href="/Karyawan">
        <i class="ri-article-fill"></i>
        <span>Karyawan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Hutang*') ? 'active' : 'collapsed' }}" href="/Hutang">
        <i class="ri-article-fill"></i>
        <span>Hutang</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Gaji*') ? 'active' : 'collapsed' }}" href="/Gaji">
        <i class="ri-article-fill"></i>
        <span>Gaji</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Piutang_Pemasukan*') ? 'active' : 'collapsed' }}" href="/Piutang_Pemasukan">
        <i class="ri-article-fill"></i>
        <span>Piutang Pemasukan</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Piutang_Pengeluaran*') ? 'active' : 'collapsed' }}" href="/Piutang_Pengeluaran">
        <i class="ri-article-fill"></i>
        <span>Piutang Pengeluaran</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Kas*') ? 'active' : 'collapsed' }}" href="/Kas">
        <i class="ri-article-fill"></i>
        <span>Kas</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('kasKategori*') ? 'active' : 'collapsed' }}" href="/kasKategori">
        <i class="ri-article-fill"></i>
        <span>Kas Kategori</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Kategori*') ? 'active' : 'collapsed' }}" href="/Kategori">
        <i class="ri-article-fill"></i>
        <span>Kategori</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::is('Aset*') ? 'active' : 'collapsed' }}" href="/Aset">
        <i class="ri-article-fill"></i>
        <span>Aset</span>
      </a>
    </li>
  </ul>
</aside>
