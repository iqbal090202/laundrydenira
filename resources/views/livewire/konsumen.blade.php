<div class="container">
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('layouts/sidebar')
        </div>
        <div class="col-md-8 col-lg-9">
            <h2>Halaman Konsumen</h2>

            @include('layouts/konsumen/tambah')
            @include('layouts/konsumen/edit')
            @include('layouts/konsumen/hapus')
            @include('layouts/flashdata')

            <div class="row">
                <div class="col-md-8">
                    @can('kasir')
                        <button wire:click="show_tambah" type="button" class="btn btn-primary btn-sm mb-3">
                            Tambah Konsumen
                        </button>
                    @endcan
                </div>
                <div class="col-md-4">
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
                        <th scope="col">Hp</th>
                        <th scope="col">Alamat</th>
                        @can('kasir')
                            <th width="10%" scope="col">Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($konsumen as $item)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->hp }}</td>
                            <td>{{ $item->alamat }}</td>
                            @can('kasir')
                                <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button wire:click="show_edit({{$item->id}})" type="button" class="btn btn-sm btn-primary mr-2">Edit</button>
                                    <button wire:click="show_hapus({{$item->id}})" type="button" class="btn btn-sm btn-danger">Hapus</button>
                                </div>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $konsumen->links() }}
        </div>
    </div>
</div>
