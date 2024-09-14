<div class="list-group d-none d-sm-none d-md-block">
    @canany(['kasir', 'produksi', 'manager'])
        <a href="/dashboard" class="list-group-item list-group-item-action">Dashboard</a>
    @endcanany
    @canany(['kasir', 'manager'])
        <a href="/konsumen" class="list-group-item list-group-item-action">Konsumen</a>
    @endcanany
    @can('kasir')
        <a href="/layanan" class="list-group-item list-group-item-action">Layanan</a>
        <a href="/transaksi" class="list-group-item list-group-item-action">Buat Transaksi</a>
    @endcan
    @canany(['kasir', 'produksi', 'manager'])
        <a href="/progres" class="list-group-item list-group-item-action">Kelola Data Pesanan Laundry</a>
    @endcanany
</div>
