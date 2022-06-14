<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>
            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C;">
                            <tr>
                                <th class="table-th text-white">Id</th>
                                <th class="table-th text-white text-center">Descripcion</th>
                                <th class="table-th text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $permiso)
                            <tr>
                                <td class="text-center"> <h6>{{$permiso->id}}</h6></td>
                                <td class="text-center"><h6>{{$permiso->name}}</h6></td>
                               
                                <td  class="text-center">
                                    <a href="javascript:void(0)" wire:click="Edit({{$permiso->id}})" class="btn btn-dark mtmobile" title="Editar registro"><i class="fas fa-edit"></i></a>

                                    <a href="javascript:void(0)" onclick="Confirm('{{$permiso->id}}')" class="btn btn-dark" title="Eliminar registro"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$permisos->links()}}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.permisos.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // var elems = document.querySelectorAll('.sales');
        // var instances = M.Sidenav.init(elems, options);
        window.livewire.on('permiso-added', msg => {
            $('#theModal').modal('hide');
            noty(msg);
        });

        window.livewire.on('permiso-updated', msg => {
            $('#theModal').modal('hide');
            noty(msg);
        });

        window.livewire.on('permiso-deleted', msg => {
            noty(msg);
            $('#theModal').modal('hide');
        });

        window.livewire.on('permiso-exists', msg => {
            noty(msg);
        });

        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide');
        });

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show');
        });

    });

    function Confirm(id) {
        $('#theModal').modal('hide');
        swal({
            title: "CONFIRMAR",
            text: "¿Estás seguro que quieres eliminar este registro? ¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#3b3f5c'
        }).then((result) => {
            if (result.value) {
                window.livewire.emit('destroy', id);
                $('#theModal').modal('hide');
                swal.close();
            }
        });
    }
</script>