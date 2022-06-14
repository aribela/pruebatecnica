
<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card title">
                    <b>{{$componentName}} | {{$usuario}}</b>
                </h4>
                
            </div>
            

            <div class="widget-content">
                <div class="form-inline">
                    <div class="form-group mr-5">
                        <select wire:model="userSelected" class="form-control">
                            <option value="Elegir" selected>@lang("messages.select")</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="button" class="btn btn-dark mbmobile inblock mr-5" data-toggle="modal" data-target="#theModal">
                        @lang("messages.add")
                    </button>

                    
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mt-1">
                                <thead class="text-white" style="background: #3b3f5c">
                                    <tr>
                                        <th class="table-th text-white text-center">ID</th>
                                        <th class="table-th text-white text-center">@lang("messages.category")</th>
                                        <th class="table-th text-white text-center">@lang("messages.description")</th>
                                        <th class="table-th text-white">@lang("messages.image")</th>
                                        <th class="table-th text-white text-center">@lang("messages.status")</th>
                                        <th class="table-th text-white text-center">@lang("messages.actions")</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evidencias as $evidencia)
                                        <tr>
                                            <td>
					    	<h6 class="text-center">
							{{$evidencia->id}}
						</h6>
					    </td>
                                            <td class="text-center">
                                                <h6>
                                                    {{$evidencia->category_name}}
                                                </h6>
                                            </td>
                                            <td class="text-center">
                                                <h6>
                                                    {{$evidencia->description}}
                                                </h6>
                                            </td>
                                            <td class="text-center"> <span><img src="{{ asset('storage/'.$evidencia->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded"></span></td>
                                            <td class="text-center">
                                                <span class="badge {{$evidencia->status==1 ? 'badge-success': 'badge-danger' }} text-uppercase">{{$evidencia->status==1 ? 'Active': 'Inactive' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)"
                                                wire:click="Edit({{$evidencia->id}})" class="btn btn-dark mtmobile" title="Edit"><span class="material-icons">edit</span></a>
            
                                                
                                                <a href="javascript:void(0)"
                                                onclick="Confirm('{{$evidencia->id}}', 0)"
                                                class="btn btn-dark" title="Delete"><span class="material-icons">delete</span></a>
                                                
                                            </td>
                                        </tr>
                                    <tr>
                                        @endforeach
                                        
                                </tbody>
                            </table>
                            {{$evidencias->links()}}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    @include('livewire.evidence.form')
</div>

<script>
   document.addEventListener('DOMContentLoaded', function() {
        // var elems = document.querySelectorAll('.sales');
        // var instances = M.Sidenav.init(elems, options);
        window.livewire.on('evidence-added', msg => {
            $('#theModal').modal('hide');
            noty(msg);
        });

        
        window.livewire.on('evidence-updated', msg => {
            $('#theModal').modal('hide');
            noty(msg);
        });
        
        window.livewire.on('evidence-deleted', msg => {
            noty(msg);
        });

        window.livewire.on('hide-modal', msg => {
            $('#theModal').modal('hide');
        });

        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show');
        });

        window.livewire.on('hidden.bs.modal', msg => {
            $('.er').css('display', 'none');
        });
    });

    function Confirm(id, products){
        if(products > 0){
            swal(
                'No se puede eliminar esta evidencia porque tiene productos asociados.'
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