<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
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
                                <th class="table-th text-white">Usuario</th>
                                <th class="table-th text-white text-center">Telefono</th>
                                <th class="table-th text-white text-center">Correo</th>
                                <th class="table-th text-white text-center">Perfil</th>
                                <th class="table-th text-white text-center">Estatus</th>
                                <th class="table-th text-white text-center">Imagen</th>
                                <th class="table-th text-white text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td> <h6>{{$r->name}}</h6></td>
                                <td class="text-center"> <h6>{{$r->phone}}</h6></td>
                                <td class="text-center"><h6>{{$r->email}}</h6></td>
                                <td class="text-center"><h6>{{$r->profile}}</h6></td>
                                <td class="text-center">
                                    <span class="badge {{$r->status=='Active' ? 'badge-success': 'badge-danger' }} text-uppercase">{{$r->status}}</span>
                                </td>

                                <td class="text-center"> 
                                    @if($r->image != null)
                                    <img src="{{asset('storage/users/'. $r->image)}}" alt="Imagen" class="card-img-top img-fluid">
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                    wire:click="edit({{$r->id}})" class="btn btn-dark mtmobile" title="Edit"><span class="material-icons">edit</span></a>

                                    <a href="javascript:void(0)" 
                                    onclick="Confirm('{{$r->id}}')" class="btn btn-dark" title="Delete"><span class="material-icons">delete</span></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$data->links()}}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.users.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // var elems = document.querySelectorAll('.sales');
        // var instances = M.Sidenav.init(elems, options);

        window.livewire.on('user-added', msg =>{
            $("#theModal").modal('hide');
            noty(msg);
        })

        window.livewire.on('user-updated', msg =>{
            $("#theModal").modal('hide');
            noty(msg);
        })

        window.livewire.on('user-deleted', msg =>{
            noty(msg);
        })

        window.livewire.on('hide-modal', msg =>{
            $("#theModal").modal('hide');
        })

        window.livewire.on('show-modal', msg =>{
            $("#theModal").modal('show');
        })

        window.livewire.on('user-withsales', msg =>{
            noty(msg);
        })
         
    });

    function Confirm(id, products){
        if(products > 0){
            swal(
                'No se puede eliminar este producto porque tiene productos asociados.'
            );
            return;
        }else{
            swal({
                title: 'Confirmar',
                text: "¿Esta seguro de eliminar?",
                type: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                cancelButtonColor: '#fff',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'Aceptar',
            }).then(function (result){
                if (result.value) {
                    window.livewire.emit('deleteRow', id);
                    swal.close();
                }
            });
        }
    }
</script>