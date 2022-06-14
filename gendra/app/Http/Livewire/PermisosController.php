<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use App\Models\User;
use DB;


class PermisosController extends Component
{
    use WithPagination;

    public $permissionName, $search, $selected_id, $permissions, $users, $pageTitle, $componentName;

    private $pagination = 10;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Permisos';
    }

    public function render()
    {
        if(strlen($this->search) > 0){
            $permisos = Permission::where('name', 'like', '%'.$this->search.'%')->paginate($this->pagination);
        }else{
            $permisos = Permission::orderBy('name', 'asc')->paginate($this->pagination);
        }
        return view('livewire.permisos.permisos', [
            'permisos' => $permisos,
        ])
        ->extends('layouts.theme.app')
        ->section('content', 'livewire.theme.app');
    }

    public function CreatePermission(){
        $rules = [
            'permissionName' => 'required|min:2|unique:permissions,name',
        ];

        $messages = [
            'permissionName.required' => 'El nombre del permiso es requerido',
            'permissionName.min' => 'El nombre del permiso debe tener al menos 2 caracteres',
            'permissionName.unique' => 'El nombre del permiso ya existe',
        ];

        $this->validate($rules, $messages);

        Permission::create([
            'name' => $this->permissionName,
        ]);

        $this->emit('permiso-added','Permiso creado correctamente')
        ;
        $this->resetUI();
    }

    public function Edit(Permission $permiso){
        $this->selected_id = $permiso->id;
        $this->permissionName = $permiso->name;

        $this->emit('show-modal', 'Show modal');
    }

    public function UpdatePermission(){
        $rules = [
            'permissionName' => "required|min:2|unique:permissions,name,{{$this->selected_id}}",
        ];

        $messages = [
            'permissionName.required' => 'El nombre del permiso es requerido',
            'permissionName.min' => 'El nombre del permiso debe tener al menos 2 caracteres',
            'permissionName.unique' => 'El nombre del permiso ya existe',
        ];

        $this->validate($rules, $messages);

        $permiso = Permission::find($this->selected_id);

        $permiso->name = $this->permissionName;

        $permiso->save();

        $this->emit('permiso-updated','Rol actualizado correctamente');
        $this->resetUI();
    }

    protected $listeners = ['destroy' => 'Destroy'];

    public function Destroy($id){
        $rolesCount = Permission::find($id)->getRoleNames()->count();
        if($rolesCount > 0){
            $this->emit('permiso-error','Este permiso tiene roles asignados, no se puede eliminar');
            return;
        }

        Permission::find($id)->delete();

        $this->emit('permiso-deleted','Permiso eliminado correctamente');

    }

    public function resetUI(){
        $this->permissionName = '';
        $this->selected_id = 0;
        $this->search = '';
        $this->resetValidation();
    }
}
