<?php

namespace App\Http\Livewire;

use App\Models\Konsumen as ModelsKonsumen;
use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rules\Password;
use Livewire\WithPagination;

class Konsumen extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $tambah, $edit, $hapus, $search;
    public $nama, $email, $password, $password_confirmation, $alamat, $hp, $konsumen_id;

    protected function rules()
    {
        $konsumen = ModelsKonsumen::find($this->konsumen_id);

        $rule = [
            'nama' => 'required',
            'email' => ['required', 'email', 'unique:App\Models\User,email'],
            'password' => ['required', Password::min(8), 'confirmed'],
            'hp' => ['required', 'numeric', 'digits:12'],
            'alamat' => ['required'],
        ];

        if ($this->edit) {
            if (!$this->password && !$this->password_confirmation) {
                $rule['password'] = '';
            }
            if ($this->email == $konsumen->user->email) {
                $rule['email'] = '';
            }
        }

        return $rule;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function show_tambah()
    {
        $this->tambah = true;
    }

    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->nama, 
            'email' => $this->email, 
            'password' => bcrypt($this->password),
            'role_id' => 2
        ]);

        ModelsKonsumen::create([
            'user_id' => $user->id,
            'hp' => $this->hp,
            'alamat' => $this->alamat
        ]);

        session()->flash('sukses', 'Data berhasil ditambahkan.');
        $this->format();
    }

    public function show_edit(ModelsKonsumen $konsumen)
    {
        $this->edit = true;

        $this->konsumen_id = $konsumen->id;
        $this->nama = $konsumen->user->name;
        $this->email = $konsumen->user->email;
        $this->alamat = $konsumen->alamat;
        $this->hp = $konsumen->hp;
    }

    public function update()
    {
        $this->validate();

        $konsumen = ModelsKonsumen::find($this->konsumen_id);

        $data_user = [
            'name' => $this->nama, 
            'email' => $this->email, 
            'password' => bcrypt($this->password),
        ];

        if (!$this->password) {
            unset($data_user['password']);
        }

        $konsumen->user->update($data_user);

        $konsumen->update([
            'hp' => $this->hp,
            'alamat' => $this->alamat
        ]);

        session()->flash('sukses', 'Data berhasil diubah.');
        $this->format();
    }

    public function show_hapus(ModelsKonsumen $konsumen)
    {
        $this->hapus = true;

        $this->konsumen_id = $konsumen->id;
        $this->nama = $konsumen->user->name;
    }

    public function destroy()
    {
        $konsumen = ModelsKonsumen::find($this->konsumen_id);

        User::whereId($konsumen->user_id)->delete();
        $konsumen->delete();

        session()->flash('sukses', 'Data berhasil dihapus.');
        $this->updatingSearch();
        $this->format();
    }

    public function format()
    {
        unset($this->nama, $this->email, $this->password, $this->password_confirmation, $this->hp, $this->alamat, $this->konsumen_id);
        $this->tambah = false;
        $this->edit = false;
        $this->hapus = false;
    }

    public function format_search()
    {
        $this->search = '';
    }

    public function render()
    {
        if ($this->search) {
            $konsumen = ModelsKonsumen::whereHas('user', function($user){
                $user->where('name', 'like', '%'. $this->search .'%');
            })->paginate(5);
        } else {
            $konsumen = ModelsKonsumen::paginate(5);
        }
        return view('livewire.konsumen', compact('konsumen'));
    }
}
