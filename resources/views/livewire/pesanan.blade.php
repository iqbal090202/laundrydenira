<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('layouts/sidebar')
        </div>
        <div class="col-md-9">
            <h2>Halaman Pesanan</h2>

            @include('layouts/flashdata')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Tanggal diterima</label>
                    <input wire:model="tanggal_diterima" type="date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Tanggal diambil</label>
                    <input wire:model="tanggal_diambil" type="date" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Search</label>
                    <div class="input-group">
                        <input wire:model="search" type="text" class="form-control" placeholder="Search">
                        <div class="input-group-prepend" style="cursor: pointer">
                            <div wire:click="format_search" class="input-group-text">x</div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="10%" scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Total Bayar</th>
                        <th scope="col">Layanan</th>
                        <th scope="col">Tanggal Diterima</th>
                        <th scope="col">Tanggal Diambil</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesanan as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->barang->user->name }}</td>
                            <td>Rp. {{ number_format($item->total_bayar) }}</td>
                            <td>{{ $item->layanan->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_diterima)->format('d m Y, H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_diambil)->format('d m Y, H:i') }}</td>
                            <td>
                                @if ($item->status == 0)
                                    <span class="badge badge-secondary">Sudah diterima</span>
                                @elseif ($item->status == 1)
                                    <span class="badge badge-dark">Dicuci</span>
                                @elseif ($item->status == 2)
                                    <span class="badge badge-primary">Dikeringkan</span>
                                @elseif ($item->status == 3)
                                    <span class="badge badge-info">Disetrika</span>
                                @elseif ($item->status == 4)
                                    <span class="badge badge-warning">Menunggu Dibayar</span>
                                @elseif ($item->status == 5)
                                    <span class="badge badge-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
