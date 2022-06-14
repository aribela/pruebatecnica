<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use App\Models\User;
use DB;


class RolesController extends Component
{
    use WithPagination;

    public $roleName, $search, $selected_id, $permissions, $users, $pageTitle, $componentName;

    private $pagination = 5;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Roles';
    }

    public function render()
    {
        if(strlen($this->search) > 0){
            $roles = Role::where('name', 'like', '%'.$this->search.'%')->paginate($this->pagination);
        }else{
            $roles = Role::orderBy('name', 'asc')->paginate($this->pagination);
        }
        return view('livewire.role.roles', [
            'roles' => $roles,
        ])
        ->extends('layouts.theme.app')
        ->section('content', 'livewire.theme.app');
    }

    public function CreateRole(){
        $rules = [
            'roleName' => 'required|min:2|unique:roles,name',
        ];

        $messages = [
            'roleName.required' => 'El nombre del rol es requerido',
            'roleName.min' => 'El nombre del rol debe tener al menos 2 caracteres',
            'roleName.unique' => 'El nombre del rol ya existe',
        ];

        $this->validate($rules, $messages);

        Role::create([
            'name' => $this->roleName,
        ]);

        $this->emit('role-added','Rol creado correctamente')
        ;
        $this->resetUI();
    }

    public function Edit(Role $role){
        // $role = Role::find($id);
        $this->selected_id = $role->id;
        $this->roleName = $role->name;

        $this->emit('show-modal', 'Show modal');
    }

    public function UpdateRole(){
        $rules = [
            'roleName' => "required|min:2|unique:roles,name,{{$this->selected_id}}",
        ];

        $messages = [
            'roleName.required' => 'El nombre del rol es requerido',
            'roleName.min' => 'El nombre del rol debe tener al menos 2 caracteres',
            'roleName.unique' => 'El nombre del rol ya existe',
        ];

        $this->validate($rules, $messages);

        $role = Role::find($this->selected_id);

        $role->name = $this->roleName;

        $role->save();

        $this->emit('role-updated','Rol actualizado correctamente');
        $this->resetUI();
    }

    protected $listeners = ['destroy' => 'Destroy'];

    public function Destroy($id){
        $permissionsCount = Role::find($id)->permissions->count();
        if($permissionsCount > 0){
            $this->emit('role-error','Este rol tiene permisos asignados, no se puede eliminar');
            return;
        }

        Role::find($id)->delete();

        $this->emit('role-deleted','Rol eliminado correctamente');

    }

    public function resetUI(){
        $this->roleName = '';
        $this->selected_id = 0;
        $this->search = '';
        $this->resetValidation();
    }
}
