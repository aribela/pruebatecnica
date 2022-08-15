<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Livewire\UsersController;
use App\Http\Livewire\CategoriesController;
use App\Jobs\SendMail;
use App\Models\Category;
use App\Models\User;
use App\Models\Evidence;
use App\Models\Subcategories;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use DB;
use Image;

class EvidenceController extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $usuario, $componentName, $userSelected = 0, $selected_id, $image, $description, $category_id, $subcategory_id = null, $subcategory, $subcategorias = null, $status;
    private $pagination = 10;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->componentName = trans("messages.Evidence");
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

        ->paginate($this->pagination);
        return view('livewire.evidence.evidence', [
            'categories' => Category::orderBy('name', 'asc')->get(),
            'usuarios' => User::orderBy('name', 'asc')->get(),
            'evidencias' => $evidencias,
        ])->extends('layouts.theme.app')->section('content', 'livewire.theme.app');
    }

    //Function para actualizar el selector subcategorias cuando cambia la categoria en el formulario
    public function updatedcategoryid($category_id){
        // dd($category_id);
        $this->subcategorias = Subcategories::where('category_id', $category_id)->get();
    }

    public function resetUI(){
        $this->description = '';
        $this->category_id = '';
        $this->image = null;
        $this->selected_id = 0;
        $this->search = '';
    }

    public function store(){
        $rules = [
            'description' => 'required',
            'category_id' => 'required',
            'userSelected' => 'required',
            'status' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $messages = [
            'description.required' => 'La descripción es requerida',
            'category_id.required' => 'La categoria es requerida',
            'userSelected.required' => 'El usuario es requerido',
            'status.required' => 'El estado es requerido',
            'image.image' => 'El archivo debe ser una imagen',
            'image.mimes' => 'El archivo debe ser una imagen',
            'image.max' => 'El archivo debe ser una imagen',
        ];
        

        $this->validate($rules, $messages);

        $evidence = Evidence::create([
            'description' => $this->description,
            'category_id' => $this->category_id,
            'user_id' => $this->userSelected,
            'status' => $this->status,
            'image' => $this->image,
        ]);
        

        $customFileName = '';

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            // $this->image->storeAs('public/evidence', $customFileName);

            if (!file_exists('storage/evidence'.'/')) {
                mkdir('storage/evidence'.'/', 666, true);
            }
            
            $img = Image::make($this->image->path());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save('storage/evidence'.'/'.$customFileName);

            $evidence->image = $customFileName;
            $evidence->save();
        }

        //Jair 11/8/2022 Ejemplo de enviar email por job/queue
        // $email = $user->email;
        //ejecutar en consola: php artisan queue:work
        //Descomentar:
        // $email = "jair.castaneda@ejemplo.com";
        // SendMail::dispatch("Email enviado por colas", $email);

        $this->resetUI();
        $this->emit('evidence-added', 'Evidencia agregada correctamente');
    }

    public function Edit(Evidence $evidence){

        $this->description = $evidence->description;
        $this->category_id = $evidence->category_id;
        $this->userSelected = $evidence->user_id;
        $this->selected_id = $evidence->id;
        $this->status = $evidence->status;
        $this->image = null;

        $this->emit('show-modal', 'show modal!');

    }

    public function update(){
        $rules = [
            'description' => 'required',
            'category_id' => 'required',
            'userSelected' => 'required',
            'status' => 'required',

        ];

        $messages = [
            'description.required' => 'La descripción es requerida',
            'category_id.required' => 'La categoria es requerida',
            'userSelected.required' => 'El usuario es requerido',
            'status.required' => 'El estado es requerido',
        ];

        $this->validate($rules, $messages);

        $evidence = Evidence::find($this->selected_id);

        $evidence->update([
            'description' => $this->description,
            'category_id' => $this->category_id,
            'status' => $this->status,
        ]);

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            // $this->image->storeAs('public/evidence', $customFileName);
            $imageName = $evidence->image;//imagen anterior
            
            if (!file_exists('storage/evidence'.'/')) {
                mkdir('storage/evidence'.'/', 666, true);
            }
            
            $img = Image::make($this->image->path());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save('storage/evidence'.'/'.$customFileName);
            
            $evidence->image = $customFileName;
            $evidence->save();
            if($imageName != null){
                if(file_exists('public/evidence/'.$imageName)){
                    unlink('public/evidence/'.$imageName);
                }
            }
        }

        $this->resetUI();
        $this->emit('evidence-updated', 'Evidencia actualizada correctamente');
    }

    
    protected $listeners = [
        'deleteRow' => 'Destroy',
    ];

    public function Destroy(Evidence $evidence){
        $imageName = $evidence->image;
        $evidence->delete();

        if($imageName != null){
            if(file_exists('public/evidence/'.$imageName)){
                unlink('public/evidence/'.$imageName);
            }
        }

        $this->resetUI();
        $this->emit('evidence-deleted', 'Evidencia eliminada correctamente');
    }

    public function slider()
    {
        $this->componentName = 'Evidencias';
        $this->usuario = 'Elegir usuario';
        
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

    public function index()
    {
        header("Access-Control-Allow-Origin: *");
        $evidencias = Evidence::join('users as u', 'evidence.user_id', '=', 'u.id')
        ->join('categories as c', 'c.id', 'evidence.category_id')
        ->select('evidence.*', 'c.name as category_name', 'u.name as user_name', 
        DB::raw('IF(evidence.status = 1, "Activo", "Inactivo") as status_name'),
        DB::raw('IF(evidence.image IS NULL, "default.png", CONCAT(\'evidence/\',evidence.image)) as url_image')
        );
        return $evidencias->get();
    }

    public function storeApi(Request $request)
    {
        // $request = request();
        header("Access-Control-Allow-Origin: *");
        $evidence = Evidence::create([
            'description' => $request->description,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'image' => $request->image,
        ]);
        

        $customFileName = '';

        if($request->image){
            $customFileName = uniqid().'_.'.$request->image->extension();
            $request->image->storeAs('public/evidence', $customFileName);
            $evidence->image = $customFileName;
            $evidence->save();
        }

        return $evidence;
    }
}
