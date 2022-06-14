<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CategoriesController extends Component
{
    use withFileUploads;
    use withPagination;

    public $name, $search, $image, $selected_id, $pageTitle, $componentName;
    private $pagination = 2;

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Categorias';
    }

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0) {
            $data = Category::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        } else {
            $data = Category::orderBy('id', 'desc')->paginate($this->pagination);
        }
        // $data = Category::all();
        return view('livewire.category.categories', ['categories' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit($id){
        $record = Category::find($id, ['id', 'name', 'image']);
        $this->name = $record->name;
        $this->selected_id = $record->id;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');

    }

    public function store(){
        $rules = [
            'name' => 'required|unique:categories|min:3|max:50',
            
        ];

        $messages = [
            'name.required' => 'Nombre de la categoria es requerido',
            'name.unique' => 'El nombre de la categoria ya existe',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener como maximo 50 caracteres',
        ];

        $this->validate($rules, $messages);

        $category = Category::create([
            'name' => $this->name,
            'image' => $this->image,
        ]);

        $customFileName;

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/categories', $customFileName);
            // $customFileName = $category->id . '.' . $this->image->getClientOriginalExtension();
            // $this->image->storeAs('categories', $customFileName, 'public');
            $category->image = $customFileName;
            $category->save();
        }

        $this->resetUI();
        $this->emit('category-added', 'Categoria agregada correctamente');
    }

    public function update(){
        $rules = [
            'name' => "required|unique:categories,name, {$this->selected_id}|min:3|max:50",
        ];

        $messages = [
            'name.required' => 'Nombre de la categoria es requerido',
            'name.unique' => 'El nombre de la categoria ya existe',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'name.max' => 'El nombre debe tener como maximo 50 caracteres',
        ];

        $this->validate($rules, $messages);

        $category = Category::find($this->selected_id);

        $category->update([
            'name' => $this->name,
        ]);

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/categories', $customFileName);
            $imageName = $category->image;//imagen anterior
            $category->image = $customFileName;
            $category->save();

            if($imageName != null){
                if(file_exists('public/categories/'.$imageName)){
                    unlink('public/categories/'.$imageName);
                }
            }
        }

        $this->resetUI();
        $this->emit('category-updated', 'Categoria actualizada correctamente');
    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function Destroy(Category $category){
        // $category = Category::find($id);
        // dd($category);
        $imageName = $category->image;
        $category->delete();

        if($imageName != null){
            if(file_exists('public/categories/'.$imageName)){
                unlink('public/categories/'.$imageName);
            }
        }

        $this->resetUI();
        $this->emit('category-deleted', 'Categoria eliminada correctamente');
    }

    public function resetUI(){
        $this->name = '';
        $this->image = null;
        $this->selected_id = 0;
        $this->search = '';
    }
}
