<?php

namespace App\Http\Livewire;

use App\Models\Denomination;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CoinsController extends Component
{
    use withFileUploads;
    use withPagination;

    public $componentName, $pageTitle, $selected_id, $image, $search, $type, $value;
    private $pagination = 4;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Denominaciones';
        $this->selected_id = 0;
    }

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }


    public function render()
    {
        if(strlen($this->search) > 0) {
            $data = Denomination::where('type', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        } else {
            $data = Denomination::orderBy('id', 'desc')->paginate($this->pagination);
        }
        // $data = Category::all();
        return view('livewire.denomination.coins', ['data' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit($id){
        $record = Denomination::find($id, ['id', 'type', 'value', 'image']);
        $this->type = $record->type;
        $this->value = $record->value;
        $this->selected_id = $record->id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');

    }

    public function store(){
        $rules = [
            'type' => 'required|not_in:Elegir',
            'value' => 'required|unique:denominations',
            
        ];

        $messages = [
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'Elije un valor para el tipo distinto a Elegir',
            'value.required' => 'El value es requerido',
            'value.unique' => 'El value ya existe',
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::create([
            'type' => $this->type,
            'value' => $this->value,
        ]);

        
        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/denominations', $customFileName);
            // $customFileName = $category->id . '.' . $this->image->getClientOriginalExtension();
            // $this->image->storeAs('categories', $customFileName, 'public');
            $denomination->image = $customFileName;
            $denomination->save();
        }

        $this->resetUI();
        $this->emit('item-added', 'Denominacion agregada correctamente');
    }

    public function update(){
        $rules = [
            'type' => "required|not_in:Elegir",
            'value' => "required|unique:denominations,value, {$this->selected_id}",
        ];
        

        $messages = [
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'Elije un valor para el tipo distinto a Elegir',
            'value.required' => 'El value es requerido',
            'value.unique' => 'El value ya existe',
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::find($this->selected_id);

        $denomination->update([
            'type' => $this->type,
            'value' => $this->value,
        ]);

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/denominations', $customFileName);
            $imageName = $denomination->image;//imagen anterior
            $denomination->image = $customFileName;
            $denomination->save();

            if($imageName != null){
                if(file_exists('public/denominations/'.$imageName)){
                    unlink('public/denominations/'.$imageName);
                }
            }
        }

        $this->resetUI();
        $this->emit('item-updated', 'Denominacion actualizada correctamente');
    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function Destroy(Denomination $denomination){
        // $category = Category::find($id);
        // dd($category);
        $imageName = $denomination->image;
        $denomination->delete();

        if($imageName != null){
            if(file_exists('public/denominations/'.$imageName)){
                unlink('public/denominations/'.$imageName);
            }
        }

        $this->resetUI();
        $this->emit('item-deleted', 'Demominacion eliminada correctamente');
    }

    public function resetUI(){
        $this->value = '';
        $this->type = '';
        $this->selected_id = 0;
        $this->search = '';
        $this->image = null;
    }
}
