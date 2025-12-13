<div>
    <h1 class="text-xl text-center pb-4">Ocorrências Diárias - Hotel São Charbel</h1> 
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12"> 
                    <label class="labelforms text-muted"><b>Conteúdo</b></label> 
                    <x-editor-trix model="content" :value="$content ?? ''" />
                </div>                     
            </div>
        </div>            
    </div>
</div>