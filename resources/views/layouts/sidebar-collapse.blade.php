<div class="d-sm-block d-md-none">
    <a href=" /dashboard" class="nav-link">Dashboard</a>
    @can('admin')
        <a href="/konsumen" class="nav-link">Konsumen</a>
        <a href="/layanan" class="nav-link">Layanan</a>
    @endcan
        <a href="/transaksi" class="nav-link">Buat Transaksi</a>
    @can('admin')
        <a href="/progres" class="nav-link">Kelola Data Pesanan Laundry</a>
    @endcan

    <hr>

    <a class="nav-link" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
        {{ __('Logout') }}
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
