<div>
    <h1 class="text-xl text-center pb-4">Ocorrências Diárias - Hotel São Charbel</h1>     
    <div class="row">
        <div class="col-12"> 
            <label class="labelforms text-muted"><b>Conteúdo</b></label> 
            <x-editor-quill :value="$content" model="content" />
            @error('content')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>                     
    </div>        
</div>