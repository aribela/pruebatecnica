<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductsController extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $name, $barcode, $cost, $price, $stock, $alerts, $categoryid, $search, $image, $selected_id, $pageTitle, $componentName;

    private $pagination = 3;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
        $this->categoryid = 'Elegir';
    }


    public function render()
    {
        if(strlen($this->search) > 0) {
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
            ->select('products.*', 'c.name as category_name')
            ->where('products.name', 'like', '%' . $this->search . '%')
            ->orwhere('products.barcode', 'like', '%' . $this->search . '%')
            ->orwhere('c.name', 'like', '%' . $this->search . '%')
            ->orderBy('products.name', 'asc')
            ->paginate($this->pagination);
        } else {
            $products = Product::join('categories as c', 'c.id', 'products.category_id')
            ->select('products.*', 'c.name as category_name')
            ->orderBy('products.name', 'asc')
            ->paginate($this->pagination);
        }
        return view('livewire.products.products', [
            'data' => $products,
            'categories' => Category::orderBy('name', 'asc')->get(),
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function store(){
        $rules = [
            'name' => 'required|unique:products|min:3',
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir',
        ];

        $messages = [
            'name.required' => 'El campo nombre es obligatorio',
            'name.unique' => 'El nombre ya existe',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'cost.required' => 'El campo costo es obligatorio',
            'price.required' => 'El campo precio es obligatorio',
            'stock.required' => 'El campo stock es obligatorio',
            'alerts.required' => 'Ingresa el valor minimo de existencias',
            'categoryid.required' => 'El campo categoria es obligatorio',
            'categoryid.not_in' => 'Elije un nombre de categoria diferente de Elegir',
        ];

        $this->validate($rules, $messages);

        $product = Product::create([
            'name' => $this->name,
            'barcode' => $this->barcode,
            'cost' => $this->cost,
            'price' => $this->price,
            'stock' => $this->stock,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid,
        ]);

        if($this->image) {
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $product->image = $customFileName;
            $product->save();
        }

        $this->resetUI();
        $this->emit('product-added', 'Producto agregado correctamente');
    }

    public function Edit(Product $product){
        $this->selected_id = $product->id;
        $this->name = $product->name;
        $this->barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->alerts = $product->alerts;
        $this->categoryid = $product->category_id;
        $this->image = null;

        $this->emit('show-modal', 'Show modal');
    }

    public function update(){
        $rules = [
            'name' => "required|min:3|unique:products,name,{$this->selected_id}|",
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir',
        ];

        $messages = [
            'name.required' => 'El campo nombre es obligatorio',
            'name.unique' => 'El nombre ya existe',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'cost.required' => 'El campo costo es obligatorio',
            'price.required' => 'El campo precio es obligatorio',
            'stock.required' => 'El campo stock es obligatorio',
            'alerts.required' => 'Ingresa el valor minimo de existencias',
            'categoryid.required' => 'El campo categoria es obligatorio',
            'categoryid.not_in' => 'Elije un nombre de categoria diferente de Elegir',
        ];

        $this->validate($rules, $messages);

        $product = Product::find($this->selected_id);
        $product->update([
            'name' => $this->name,
            'barcode' => $this->barcode,
            'cost' => $this->cost,
            'price' => $this->price,
            'stock' => $this->stock,
            'alerts' => $this->alerts,
            'category_id' => $this->categoryid,
        ]);

        if($this->image) {
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $imageTmp = $product->image;
            $product->image = $customFileName;

            $product->save();

            if($imageTmp != null) {
                if(file_exists('storage/products/'.$imageTmp)) {
                    unlink('storage/products/'.$imageTmp);
                }
            }
        }

        $this->resetUI();
        $this->emit('product-updated', 'Producto actualizado correctamente');
    }



    public function resetUI(){
        $this->name = '';
        $this->barcode = '';
        $this->cost = '';
        $this->price = '';
        $this->stock = '';
        $this->alerts = '';
        $this->search = '';
        $this->categoryid = 'Elegir';
        $this->image = null;
        $this->selected_id = 0;
    }

    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function Destroy(Product $product){
        $imageTmp = $product->image;
        $product->delete();

        if($imageTmp != null) {
            if(file_exists('storage/products/'.$imageTmp)) {
                unlink('storage/products/'.$imageTmp);
            }
        }
        $this->resetUI();
        $this->emit('product-deleted', 'Producto eliminado correctamente');
    
    }


}
