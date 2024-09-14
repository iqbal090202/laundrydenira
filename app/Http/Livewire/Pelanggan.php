<?php

namespace App\Http\Livewire;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Pelanggan extends Component
{
    use WithPagination;
    public $tanggal_diterima, $tanggal_diambil;

    public function render()
    {
        $userId = Auth::id();
        $query = Transaksi::query();

        $query->where('user_id', $userId);

        if ($this->tanggal_diterima || $this->tanggal_diambil) {
            $query->where('tanggal_diterima', 'like', '%' . $this->tanggal_diterima . '%')
                ->where('tanggal_diambil', 'like', '%' . $this->tanggal_diambil . '%');
        }

        $progres = $query->orderBy('created_at', 'desc')
            ->latest()
            ->paginate(5);
        return view('livewire.progres', compact('progres'));
    }
}
