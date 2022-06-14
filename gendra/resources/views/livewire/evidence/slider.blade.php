
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
                        <div class="row">
                            @foreach($evidencias as $evidencia)
                            <div class="col-md-4">
                              <div class="thumbnail">
                                <a href="{{ asset('storage/'.$evidencia->imagen) }}">
                                  <img src="{{ asset('storage/'.$evidencia->imagen) }}" alt="Lights" style="width:100%">
                                  <div class="caption">
                                    <p>{{$evidencia->description}}</p>
                                  </div>
                                </a>
                              </div>
                            </div>
                            @endforeach
                            
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