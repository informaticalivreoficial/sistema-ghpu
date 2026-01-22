<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-building mr-2"></i> {{ $apartment ? 'Editar' : 'Cadastrar' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('apartments.index') }}">Apartamentos</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $apartment ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline">
        <div class="card-body text-muted">
            <div class="row">            
                <div class="col-12">
                    <div class="card card-info card-outline card-outline-tabs">
                        
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Dados Cadastrais</a>
                                </li>
                                {{--  
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Estrutura</a>
                                </li>  
                                                          
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Fotos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-seo-tab" data-toggle="pill" href="#custom-tabs-four-seo" role="tab" aria-controls="custom-tabs-four-seo" aria-selected="false">Seo</a>
                                </li> 
                                --}}                           
                            </ul>
                        </div>
                        <div class="card-body text-muted">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                    <div class="row mb-2">                                   
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-6">   
                                            <div class="form-group">
                                                <label class="labelforms"><b>*Título</b></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model.defer="name">
                                                @error('name') <span class="error erro-feedback">{{ $message }}</span> @enderror
                                            </div>                                                    
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">   
                                            <div class="form-group">
                                                <label class="labelforms"><b>*Capacidade Adultos</b></label>
                                                <input type="text" class="form-control @error('capacidade_adultos') is-invalid @enderror" wire:model.defer="capacidade_adultos">
                                                @error('capacidade_adultos') <span class="error erro-feedback">{{ $message }}</span> @enderror
                                            </div>                                                    
                                        </div> 
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">   
                                            <div class="form-group">
                                                <label class="labelforms"><b>*Capacidade Crianças</b></label>
                                                <input type="text" class="form-control @error('capacidade_criancas') is-invalid @enderror" wire:model.defer="capacidade_criancas">
                                                @error('capacidade_criancas') <span class="error erro-feedback">{{ $message }}</span> @enderror
                                            </div>                                                    
                                        </div> 
                                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">   
                                                <div class="form-group">
                                                    <label class="labelforms"><b>*Empresa</b></label>
                                                    <select class="form-control @error('company_id') is-invalid @enderror" wire:model.defer="company_id">
                                                        <option value="">Selecione</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->id }}">{{ $company->alias_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('company_id') <span class="error erro-feedback">{{ $message }}</span> @enderror
                                                </div>                                                    
                                            </div> 
                                        @endif
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">   
                                            <div class="form-group">
                                                <label class="labelforms"><b>Status</b></label><br>
                                                <x-forms.switch-toggle wire:model.defer="status" :checked="$status" size="sm" color="green"/>
                                                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>                                                    
                                        </div> 
                                    </div> 
                                </div>
                                {{--  
                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                    <div class="row">
                                        <h4>Estrutura</h4>
                                    </div>
                                    <div class="row mb-4">                                     
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                            <!-- checkbox -->
                                            <div class="form-group p-3 mb-1">
                                                <div class="form-check mb-2">
                                                    <input id="ar_condicionado" class="form-check-input" type="checkbox" name="ar_condicionado" {{ (old('ar_condicionado') == 'on' || old('ar_condicionado') == true ? 'checked' : '' ) }}>
                                                    <label for="ar_condicionado" class="form-check-label">Ar Condicionado</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input id="cafe_manha" class="form-check-input" type="checkbox" name="cafe_manha" {{ (old('cafe_manha') == 'on' || old('cafe_manha') == true ? 'checked' : '') }}>
                                                    <label for="cafe_manha" class="form-check-label">Café da manhã</label>
                                                </div>                                                                     
                                                <div class="form-check mb-2">
                                                    <input id="cofre_individual" class="form-check-input" type="checkbox" name="cofre_individual" {{ (old('cofre_individual') == 'on' || old('cofre_individual') == true ? 'checked' : '') }}>
                                                    <label for="cofre_individual" class="form-check-label">Cofre Individual</label>
                                                </div>                                                                     
                                                <div class="form-check mb-2">
                                                    <input id="frigobar" class="form-check-input" type="checkbox" name="frigobar" {{ (old('frigobar') == 'on' || old('frigobar') == true ? 'checked' : '') }}>
                                                    <label for="frigobar" class="form-check-label">Frigobar</label>
                                                </div>                                                                     
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                            <!-- checkbox -->
                                            <div class="form-group p-3 mb-1">  
                                                <div class="form-check mb-2">
                                                    <input id="servico_quarto" class="form-check-input" type="checkbox"  name="servico_quarto" {{ (old('servico_quarto') == 'on' || old('servico_quarto') == true ? 'checked' : '' ) }}>
                                                    <label for="servico_quarto" class="form-check-label">Serviço de Quarto</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input id="telefone" class="form-check-input" type="checkbox"  name="telefone" {{ (old('telefone') == 'on' || old('telefone') == true ? 'checked' : '' ) }}>
                                                    <label for="telefone" class="form-check-label">Telefone</label>
                                                </div>                                          
                                                <div class="form-check mb-2">
                                                    <input id="estacionamento" class="form-check-input" type="checkbox"  name="estacionamento" {{ (old('estacionamento') == 'on' || old('estacionamento') == true ? 'checked' : '' ) }}>
                                                    <label for="estacionamento" class="form-check-label">Estacionamento</label>
                                                </div>                                            
                                                <div class="form-check mb-2">
                                                    <input id="espaco_fitness" class="form-check-input" type="checkbox"  name="espaco_fitness" {{ (old('espaco_fitness') == 'on' || old('espaco_fitness') == true ? 'checked' : '' ) }}>
                                                    <label for="espaco_fitness" class="form-check-label">Espaço Fitness</label>
                                                </div>                                            
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                            <!-- checkbox -->
                                            <div class="form-group p-3 mb-1">                                            
                                                <div class="form-check mb-2">
                                                    <input id="lareira" class="form-check-input" type="checkbox"  name="lareira" {{ (old('lareira') == 'on' || old('lareira') == true ? 'checked' : '') }}>
                                                    <label for="lareira" class="form-check-label">Lareira</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input id="elevador" class="form-check-input" type="checkbox"  name="elevador" {{ (old('elevador') == 'on' || old('elevador') == true ? 'checked' : '') }}>
                                                    <label for="elevador" class="form-check-label">Elevador</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input id="vista_para_mar" class="form-check-input" type="checkbox"  name="vista_para_mar" {{ (old('vista_para_mar') == 'on' || old('vista_para_mar') == true ? 'checked' : '' ) }}>
                                                    <label for="vista_para_mar" class="form-check-label">Vista para o Mar</label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input id="ventilador_teto" class="form-check-input" type="checkbox"  name="ventilador_teto" {{ (old('ventilador_teto') == 'on' || old('ventilador_teto') == true ? 'checked' : '' ) }}>
                                                    <label for="ventilador_teto" class="form-check-label">Ventilador de Teto</label>
                                                </div>                                                                                        
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">                                        
                                            <!-- checkbox -->
                                            <div class="form-group p-3 mb-1"> 
                                                <div class="form-check mb-2">
                                                    <input id="wifi" class="form-check-input" type="checkbox"  name="wifi" {{ (old('wifi') == 'on' || old('wifi') == true ? 'checked' : '' ) }}>
                                                    <label for="wifi" class="form-check-label">Wifi</label>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                --}}

                                {{--  
                                <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                    <div class="row mb-4">                                   
                                        <div class="col-sm-12">                                        
                                            <div class="form-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="files[]" multiple>
                                                    <label class="custom-file-label" for="exampleInputFile">Escolher Fotos</label>
                                                </div>
                                            </div>                                        
                                            <div class="content_image"></div>
                                        </div>
                                    </div> 
                                </div>
                                
                                <div class="tab-pane fade" id="custom-tabs-four-seo" role="tabpanel" aria-labelledby="custom-tabs-four-seo-tab">
                                    <div class="row mb-2 text-muted">                                   
                                        <div class="col-12 col-md-6 col-lg-6">   
                                            <div class="form-group">
                                                <label class="labelforms"><b>Headline</b></label>
                                                <input type="text" class="form-control" name="headline" value="{{old('headline')}}">
                                            </div>                                                    
                                        </div>                                                                       
                                        <div class="col-12 mb-1"> 
                                            <div class="form-group">
                                                <label class="labelforms"><b>MetaTags</b></label>
                                                <input id="tags_1" class="tags" rows="5" name="metatags" value="{{ old('metatags') }}">
                                            </div>
                                        </div>
                                        <div class="col-12"> 
                                            <div class="form-group">
                                                <label class="labelforms"><b>Youtube Vídeo</b></label>
                                                <textarea id="inputDescription" class="form-control" rows="5" name="youtube_video">{{ old('youtube_video') }}</textarea> 
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                --}}
                                
                            </div>
                            <div class="row text-right">
                                <div class="col-12 mb-4">
                                    <button type="submit" class="btn btn-lg btn-info" wire:click="save"><i class="nav-icon fas fa-check mr-2"></i> {{ $apartment ? 'Atualizar Agora' : 'Cadastrar Agora' }}</button>
                                </div>
                            </div>  
                                                    
                            </form>

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
