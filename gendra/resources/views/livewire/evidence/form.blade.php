@include('common.modalHead')

<div class="row">
    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <input type="text" wire:model.lazy="description" class="form-control" placeholder="ej. avance 11/6">
        </div>
    @error('description')
        <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <select name="" id="" class="form-control" wire:model.lazy="category_id">
                <option value="">Seleccione una categoria</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach

            </select>
        </div>
    </div>
    @if(!is_null($subcategorias))
    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <select name="" id="" class="form-control" wire:model.lazy="subcategory_id">
                <option value="">Seleccione una subcategoria</option>
                @foreach($subcategorias as $subcategoria)
                    <option value="{{$subcategoria->id}}">{{$subcategoria->subcategory}}</option>
                @endforeach

            </select>
        </div>
    </div>
    @endif

    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <select name="" id="" class="form-control" wire:model.lazy="status">
                    <option value="">Seleccione...</option>
                    <option value="0">Inactive</option>
                    <option value="1">Active</option>
            </select>
        </div>
    </div>

    <div class="col-sm-12 mt-3">
        <div class="form-group custom-file">
            <input type="file" class="custom-file-input form-control" wire:model="image" accept="image/x-png, image/gif, image/jpeg">
            <label class="custom-file-label" >Imagen {{$image}}</label>
            @error('image')
        <span class="text-danger er">{{ $message }}</span>
        @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')