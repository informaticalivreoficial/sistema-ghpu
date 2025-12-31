<div>
    <div class="row">
        <div class="col-12 col-md-6 col-lg-6">   
            <div class="form-group">
                <label class="labelforms"><b>*Título</b></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"  wire:model.live="title" placeholder="Título da Ocorrência..."/>
                @error('title')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>                                                    
        </div>
        <div class="col-12"> 
            <label class="labelforms text-muted"><b>Conteúdo</b></label> 
            <x-editor-quill :value="$content" model="content" />
            @error('content')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>                     
    </div>
</div>