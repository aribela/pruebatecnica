@include('common.modalHead')
<div class="row">
    <div class="col-sm-12 col-d-md-8">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" wire:model.lazy="name" class="form-control" placeholder="ej. Luis">
        </div>
    @error('name')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>

    <div class="col-sm-12 col-d-md-4">
        <div class="form-group">
            <label for="phone">Telefono</label>
            <input type="text" wire:model.lazy="phone" class="form-control" placeholder="ej. 222 22 " maxlength="10">
        </div>
    @error('phone')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>

    <div class="col-sm-12 col-d-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" wire:model.lazy="email" class="form-control" placeholder="ej. emil@ejemplo.com">
        </div>
    @error('email')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>

    <div class="col-sm-12 col-d-md-6">
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" wire:model.lazy="password" class="form-control">
        </div>
    @error('password')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>

    <div class="col-sm-12 col-d-md-6">
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" wire:model.lazy="status">
                <option value="Elegir">Elegir</option>
                <option value="Active">Activo</option>
                <option value="Locked">Bloqueado</option>
            </select>
        </div>
    @error('status')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>

    <div class="col-sm-12 col-d-md-6">
        <div class="form-group">
            <label for="profile">Rol</label>
            <select class="form-control" wire:model.lazy="profile">
                <option value="Elegir">Elegir</option>
                @foreach($roles as $role)
                    <option value="{{$role->name}}">{{$role->name}}</option>
                @endforeach
            </select>
        </div>
    @error('profile')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>

    <div class="col-sm-12 col-d-md-6">
        <div class="form-group">
            <label for="image">Imagen de perfil</label>
            <input class="form-control" type="file" wire:model="image" accept="image/x-png, image/jpg, image/gif">
        </div>
    @error('image')<span class="text-danger er">{{ $message }}</span>@enderror
    </div>
</div>
@include('common.modalFooter')