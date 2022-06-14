</div>
      <div class="modal-footer">
        <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">Cerrar</button>

        @if($selected_id < 1)
            <button type="button" wire:click.prevent="store()" class="btn btn-dark close-modal">Agregar</button>
        @else
            <button type="button" wire:click.prevent="update()" class="btn btn-dark close-modal">Editar</button>
        @endif
        
      </div>
    </div>
  </div>
</div>