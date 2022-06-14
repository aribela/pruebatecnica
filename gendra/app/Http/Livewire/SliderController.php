<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\User;
use App\Models\Evidence;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class SliderController extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $usuario, $componentName, $userSelected = 0, $selected_id, $image, $description, $category_id, $status;
    private $pagination = 10;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->componentName = "Slider";
        $this->usuario = trans('messages.Select an user');
    }

    public function render()
    {
        if($this->userSelected > 0){
            $user = User::find($this->userSelected);
            $this->usuario = $user->name;
        }
        $evidencias = Evidence::join('users as u', 'evidence.user_id', '=', 'u.id')
        ->join('categories as c', 'c.id', 'evidence.category_id')
        ->select('evidence.*', 'c.name as category_name')
        ->where('evidence.user_id', $this->userSelected)
        ->where('evidence.status', 1)

        ->paginate($this->pagination);
        return view('livewire.evidence.slider', [
            'categories' => Category::orderBy('name', 'asc')->get(),
            'usuarios' => User::orderBy('name', 'asc')->get(),
            'evidencias' => $evidencias,
        ])->extends('layouts.theme.app')->section('content', 'livewire.theme.app');
    }
}

