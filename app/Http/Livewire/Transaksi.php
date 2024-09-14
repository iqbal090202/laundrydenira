<?php

namespace App\Http\Livewire;

use App\Mail\TransaksiMail;
use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\Konsumen;
use App\Models\Layanan;
use App\Models\Transaksi as ModelsTransaksi;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Transaksi extends Component
{
    public $nama, $email, $hp, $alamat, $layanan_nama, $berat, $total_bayar, $barang = [];

    public function mount()
    {
        array_push($this->barang, "");
    }

    protected function rules()
    {
        return [
            'layanan_nama' => 'required',
            'berat' => 'required|min:1|numeric',
            'barang' => 'array',
            'barang.*' => 'required'
        ];
    }

    public function tambah_barang()
    {
        array_push($this->barang, "");
    }

    public function hapus_barang($key)
    {
        unset($this->barang[$key]);
    }

    // public function store()
    // {
    //     $this->validate();
    //     $user = auth()->user();

    //     DB::transaction(function () {
    //         $layanan = Layanan::find($this->layanan_nama);


    //         $user = User::create([
    //             'name' => $this->nama,
    //             'email' => $this->email,
    //             'role_id' => 3
    //         ]);

    //         Konsumen::create([
    //             'hp' => $this->hp,
    //             'alamat' => $this->alamat,
    //             'user_id' => $user->id,
    //         ]);

    //         $barang = Barang::create([
    //             'berat' => $this->berat,
    //             'user_id' => $user->id,
    //         ]);

    //         foreach ($this->barang as $item) {
    //             DetailBarang::create([
    //                 'barang_id' => $barang->id,
    //                 'nama' => $item
    //             ]);
    //         }

    //         $urgensi = [
    //             'reguler' => 1,
    //             'kilat' => 2,
    //             'express' => 3,
    //             'exclusive' => 4
    //         ];

    //         $tanggal_diterima = now();
    //         $Tproc = $layanan->durasi;
    //         $U = $urgensi[$layanan->nama];
    //         $a = 0.1;

    //         $lastEntry = DB::table('transaksi')
    //             ->where('layanan_id', $layanan->id)
    //             ->orderBy('tanggal_diterima', 'desc')
    //             ->first();

    //         if ($lastEntry) {
    //             $tanggal_diterima = \Carbon\Carbon::parse($tanggal_diterima);
    //             $lastEntryTanggalDiterima = \Carbon\Carbon::parse($lastEntry->tanggal_diterima);
    //             $Tarr_diff = $tanggal_diterima->diffInMinutes($lastEntryTanggalDiterima);
    //         } else {
    //             $Tarr_diff = 0;
    //         }

    //         $P = ($U / $Tproc) - ($a * $Tarr_diff);

    //         $transaksi = ModelsTransaksi::create([
    //             'user_id' => $user->id,
    //             'layanan_id' => $this->layanan_nama,
    //             'barang_id' => $barang->id,
    //             'total_bayar' => $layanan->harga * $this->berat,
    //             'tanggal_diterima' => $tanggal_diterima,
    //             'tanggal_diambil' => $tanggal_diterima->copy()->addHours($Tproc),
    //             'status' => 0,
    //             'prioritas' => $P
    //         ]);

    //         // Mail::to($this->email)->send(new TransaksiMail($transaksi));

    //         session()->flash('sukses', 'Data berhasil ditambahkan.');


    //         if ($user->role === 'konsumen') {
    //             return redirect('/pesanan'); // Arahkan ke halaman pesanan jika peran pengguna adalah konsumen
    //         }
    //         return redirect('/progres');
    //     });
    // }

    public function store()
{
    $this->validate();
    $loggedUser = auth()->user();

    DB::transaction(function () use ($loggedUser) {
        $layanan = Layanan::find($this->layanan_nama);

        // Periksa apakah user yang login adalah konsumen
        if ($loggedUser->role_id == 3) {
            // Jika user adalah konsumen, gunakan user yang sudah login
            $userId = $loggedUser->id;
        } else {
            // Jika bukan konsumen, buat user baru dan konsumen baru
            $user = User::create([
                'name' => $this->nama,
                'email' => $this->email,
            ]);
            $user->assignRole('pelanggan');

            Konsumen::create([
                'hp' => $this->hp,
                'alamat' => $this->alamat,
                'user_id' => $user->id,
            ]);

            $userId = $user->id;
        }

        // Buat barang baru
        $barang = Barang::create([
            'berat' => $this->berat,
            'user_id' => $userId,
        ]);

        // Buat detail barang
        foreach ($this->barang as $item) {
            DetailBarang::create([
                'barang_id' => $barang->id,
                'nama' => $item
            ]);
        }

        // Hitung prioritas
        $urgensi = [
            'reguler' => 1,
            'kilat' => 2,
            'express' => 3,
            'exclusive' => 4
        ];

        $tanggal_diterima = now();
        $Tproc = $layanan->durasi;
        $U = $urgensi[$layanan->nama];
        $a = 0.1;

        $lastEntry = DB::table('transaksi')
            ->where('layanan_id', $layanan->id)
            ->orderBy('tanggal_diterima', 'desc')
            ->first();

        if ($lastEntry) {
            $tanggal_diterima = \Carbon\Carbon::parse($tanggal_diterima);
            $lastEntryTanggalDiterima = \Carbon\Carbon::parse($lastEntry->tanggal_diterima);
            $Tarr_diff = $tanggal_diterima->diffInMinutes($lastEntryTanggalDiterima);
        } else {
            $Tarr_diff = 0;
        }

        $P = ($U / $Tproc) - ($a * $Tarr_diff);

        // Buat transaksi baru
        $transaksi = ModelsTransaksi::create([
            'user_id' => $userId,
            'layanan_id' => $this->layanan_nama,
            'barang_id' => $barang->id,
            'total_bayar' => $layanan->harga * $this->berat,
            'tanggal_diterima' => $tanggal_diterima,
            'tanggal_diambil' => $tanggal_diterima->copy()->addHours($Tproc),
            'status' => 0,
            'prioritas' => $P
        ]);
        session()->flash('sukses', 'Data berhasil ditambahkan.');
        if ($loggedUser->role_id == 3) {
            return redirect('/dashboard');
        } else {
            return redirect('/progres');
        }
    });
}


    public function render()
    {
        if ($this->layanan_nama && $this->berat) {
            $layanan = Layanan::find($this->layanan_nama);
            $this->total_bayar = $layanan->harga * $this->berat;
        } else {
            $this->total_bayar = 0;
        }
        $layanan = Layanan::all();
        return view('livewire.transaksi', compact('layanan'));
    }
}
