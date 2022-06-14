<?php

namespace App\Http\Livewire;
use App\Models\Sale;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;


class UsersController extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $name,$phone,$email,$status,$image,$password,$selected_id,$fileLoaded,$profile;
    public $pageTitle, $componentName, $search;

    private $pagination = 3;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Users';
        $this->status = "Elegir";
    }

    public function render()
    {
        if(strlen($this->search) > 0){
            $data = User::where('name', 'like', '%'.$this->search.'%')
            ->select('*')->orderBy('name', 'asc')->paginate($this->pagination);
        }else{
            $data = User::select('*')->orderBy('name', 'asc')->paginate($this->pagination);
        }
        return view('livewire.users.users', [
            'data' => $data,
            'roles' => Role::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function resetUI(){
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->image = '';
        $this->user_id = '';
        $this->status = 'Elegir';
        $this->selected_id = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function edit(User $user){
        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->profile = $user->profile;
        $this->status = $user->status;
        $this->email = $user->email;
        $this->password = $user->password;
       
        $this->emit('show-modal', 'open!');
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI',
    ];

    public function store(){
        $rules = [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'status' => 'required|not_in:Elegir',
            'profile' => 'required|not_in:Elegir',
            'password' => 'required|min:6|max:100',
        ];

        $messages = [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.max' => 'El campo nombre debe tener como maximo 100 caracteres',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El campo email debe ser un email valido',
            'email.unique' => 'El email ya existe',
            'status.required' => 'El campo estado es obligatorio',
            'status.not_in' => 'El campo estado es obligatorio',
            'profile.required' => 'El campo perfil es obligatorio',
            'profile.not_in' => 'El campo perfil es obligatorio',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'El campo password debe tener al menos 6 caracteres',
            'password.max' => 'El campo password debe tener como maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'profile' => $this->profile,
            'password' => bcrypt($this->password),
        ]);

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/users', $customFileName);
            $user->image = $customFileName;
            $user->save();
        }

        $this->resetUI();
        $this->emit('user-added', 'Usuario registrado');
    }

    public function update(){
        $rules = [
            'email' => 'required|email|unique:users,email,'.$this->selected_id,
            'name' => 'required|min:3|max:100',
            'status' => 'required|not_in:Elegir',
            'profile' => 'required|not_in:Elegir',
            'password' => 'required|min:6|max:100',
        ];

        $messages = [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.max' => 'El campo nombre debe tener como maximo 100 caracteres',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El campo email debe ser un email valido',
            'email.unique' => 'El email ya existe',
            'status.required' => 'El campo estado es obligatorio',
            'status.not_in' => 'El campo estado es obligatorio',
            'profile.required' => 'El campo perfil es obligatorio',
            'profile.not_in' => 'El campo perfil es obligatorio',
            'password.required' => 'El campo password es obligatorio',
            'password.min' => 'El campo password debe tener al menos 6 caracteres',
            'password.max' => 'El campo password debe tener como maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $user = User::find($this->selected_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'profile' => $this->profile,
            'password' => bcrypt($this->password),
        ]);

        if($this->image){
            $customFileName = uniqid().'_.'.$this->image->extension();
            $this->image->storeAs('public/users', $customFileName);
            $imageTemp = $user->image;
            $user->image = $customFileName;
            $user->save();

            if($imageTemp != null){
                if(file_exists('storage/users/'.$imageTemp)){
                    unlink('storage/users/'.$imageTemp);
                }
            }
        }

        $this->resetUI();
        $this->emit('user-updated', 'Usuario actualizado');
    }

    public function destroy(User $user){
        if($user){
            $sales = Sale::where('user_id', $user->id)->count();

            if($sales > 0){
                $this->emit('user-withsales', 'El usuario no puede ser eliminado porque tiene ventas asociadas');
            }else{
                $imageTemp = $user->image;
                $user->delete();

                if($imageTemp != null){
                    if(file_exists('storage/users/'.$imageTemp)){
                        unlink('storage/users/'.$imageTemp);
                    }
                }

                $this->emit('user-deleted', 'Usuario eliminado');
            }
        }

    }
}
