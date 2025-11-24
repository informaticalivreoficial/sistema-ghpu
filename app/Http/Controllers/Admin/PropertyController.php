<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropertyRequest;
use App\Models\Portal;
use App\Models\PortalImoveis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Property;
use App\Models\PropertyGb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::orderBy('created_at', 'DESC')->paginate(50);
        return view('admin.properties.index', [
            'properties' => $properties,
        ]);
    }

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $properties = Property::where(function($query) use ($request){
            if($request->filter){
                $query->orWhere('title', 'LIKE', "%{$request->filter}%");
                $query->orWhere('reference', 'LIKE', "%{$request->filter}%");
                $query->orWhere('neighborhood', 'LIKE', "%{$request->filter}%");
                $query->orWhere('city', $request->filter);
            }
        })->orderBy('created_at', 'DESC')->paginate(25);

        return view('admin.properties.index',[
            'properties' => $properties,
            'filters' => $filters
        ]);
    }

    public function create()
    {
        $users = User::orderBy('superadmin', 'DESC')
                ->orderBy('admin', 'DESC')
                ->orderBy('editor', 'DESC')
                ->orderBy('name', 'ASC')
                ->get();

        $portais = Portal::orderBy('nome')->available()->get();

        return view('admin.properties.create', [
            'users' => $users,
            'portais' => $portais
        ]);
    }

    public function store(PropertyRequest $request){

        $createProperty = Property::create($request->all());
        $createProperty->fill($request->all());

        $createProperty->setSlug();

        $validator = Validator::make($request->only('files'), [
            'files.*' => 'image'
        ]);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'danger',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }

        if($request->allFiles()){
            $files = count($request->allFiles());
            if($files > env('LIMITE_IMOVEIS')){
                return redirect()->back()->withInput()->with([
                    'color' => 'danger',
                    'message' => 'O sistema só permite o envio de ' . env('LIMITE_IMOVEIS') . ' fotos por Imóvel!!',
                ]);
            }else{
                foreach ($request->allFiles()['files'] as $image) {
                    $propertyGb = new PropertyGb();
                    $propertyGb->property = $createProperty->id;
                    $propertyGb->path = $image->storeAs(env('AWS_PASTA') . 'properties/'. $createProperty->id, Str::slug($request->title) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                    $propertyGb->save();
                    unset($propertyGb);
                }
            }
        }
        
        $portaisRequest = $request->all();
        $portais = null;
        foreach($portaisRequest as $key => $value) {
            if(Str::is('portal_*', $key) == true){
                $f['portal'] = ltrim($key, 'portal_');
                $f['imovel'] = $createProperty->id;
                $createPimovel = PortalImoveis::create($f);
                $createPimovel->save();
            }
        }

        return redirect()->route('property.edit', [
            'id' => $createProperty->id
        ])->with(['color' => 'success', 'message' => 'Imóvel cadastrado com sucesso!']);
    }

    public function edit($id)
    {
        $property = Property::where('id', $id)->first();    
        $users = User::orderBy('superadmin', 'DESC')
                ->orderBy('admin', 'DESC')
                ->orderBy('editor', 'DESC')
                ->orderBy('name', 'ASC')
                ->get();

        $portais = Portal::orderBy('nome')->available()->get(); 
        
        return view('admin.properties.edit', [
            'property' => $property,
            'users' => $users,
            'portais' => $portais
        ]);
    }

    public function update(PropertyRequest $request, $id)
    {      
        $deletePimovel = PortalImoveis::where('imovel', $id)->first();
        if($deletePimovel != null){
            $deletePimovel = PortalImoveis::where('imovel', $id)->get();
            foreach($deletePimovel as $delete){
                $delete->delete();
            }            
        } 

        $portaisRequest = $request->all();
        $portais = null;
        foreach($portaisRequest as $key => $value) {
            if(Str::is('portal_*', $key) == true){
                $f['portal'] = ltrim($key, 'portal_');
                $f['imovel'] = $id;
                $createPimovel = PortalImoveis::create($f);
                $createPimovel->save();
            }
        }

        $property = Property::where('id', $id)->first();  
        $property->fill($request->all());

        $property->setSaleAttribute($request->sale);
        $property->setLocationAttribute($request->location);
        $property->setArCondicionadoAttribute($request->ar_condicionado);
        $property->setAquecedorsolarAttribute($request->aquecedor_solar);
        $property->setBarAttribute($request->bar);
        $property->setBibliotecaAttribute($request->biblioteca);
        $property->setChurrasqueiraAttribute($request->churrasqueira);
        $property->setEstacionamentoAttribute($request->estacionamento);
        $property->setCozinhaAmericanaAttribute($request->cozinha_americana);
        $property->setCozinhaPlanejadaAttribute($request->cozinha_planejada);
        $property->setDispensaAttribute($request->dispensa);
        $property->setEdiculaAttribute($request->edicula);
        $property->setEspacoFitnessAttribute($request->espaco_fitness);
        $property->setEscritorioAttribute($request->escritorio);
        $property->setArmarionauticoAttribute($request->armarionautico);
        $property->setFornodepizzaAttribute($request->fornodepizza);
        $property->setPortaria24hsAttribute($request->portaria24hs);
        $property->setQuintalAttribute($request->quintal);
        $property->setZeladoriaAttribute($request->zeladoria);
        $property->setSalaodejogosAttribute($request->salaodejogos);
        $property->setSaladetvAttribute($request->saladetv);
        $property->setAreadelazerAttribute($request->areadelazer);
        $property->setBalcaoamericanoAttribute($request->balcaoamericano);
        $property->setVarandagourmetAttribute($request->varandagourmet);
        $property->setBanheirosocialAttribute($request->banheirosocial);
        $property->setBrinquedotecaAttribute($request->brinquedoteca);
        $property->setPertodeescolasAttribute($request->pertodeescolas);
        $property->setCondominiofechadoAttribute($request->condominiofechado);
        $property->setInterfoneAttribute($request->interfone);
        $property->setSistemadealarmeAttribute($request->sistemadealarme);
        $property->setJardimAttribute($request->jardim);
        $property->setSalaodefestasAttribute($request->salaodefestas);
        $property->setPermiteanimaisAttribute($request->permiteanimais);
        $property->setQuadrapoliesportivaAttribute($request->quadrapoliesportiva);
        $property->setGeradoreletricoAttribute($request->geradoreletrico);
        $property->setBanheiraAttribute($request->banheira);
        $property->setLareiraAttribute($request->lareira);
        $property->setLavaboAttribute($request->lavabo);
        $property->setLavanderiaAttribute($request->lavanderia);
        $property->setElevadorAttribute($request->elevador);
        $property->setMobiliadoAttribute($request->mobiliado);
        $property->setVistaParaMarAttribute($request->vista_para_mar);
        $property->setPiscinaAttribute($request->piscina);
        $property->setVentiladorTetoAttribute($request->ventilador_teto);
        $property->setInternetAttribute($request->internet);
        $property->setGeladeiraAttribute($request->geladeira);

        $property->save();
        $property->setSlug();  

        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        if ($validator->fails() === true) {
            return redirect()->back()->withInput()->with([
                'color' => 'danger',
                'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.',
            ]);
        }
        
        if($request->allFiles()){
            $files = count($request->allFiles());
            $filesTotal = ($files + $property->images()->count());            
            if($filesTotal > 0 && $filesTotal >= env('LIMITE_IMOVEIS')){
                return redirect()->back()->withInput()->with([
                    'color' => 'danger',
                    'message' => 'O sistema só permite o envio de ' . env('LIMITE_IMOVEIS') . ' fotos por Imóvel!!',
                ]);
            }else{
                foreach ($request->allFiles()['files'] as $image) {
                    $propertyImage = new propertyGb();
                    $propertyImage->property = $property->id;
                    $propertyImage->path = $image->storeAs(env('AWS_PASTA') . 'properties/'. $property->id, Str::slug($request->title) . '-' . str_replace('.', '', microtime(true)) . '.' . $image->extension());
                    $propertyImage->save();
                    unset($propertyImage);
                }
            }
        }

        return redirect()->route('property.edit', [
            'id' => $property->id,
        ])->with(['color' => 'success', 'message' => 'Imóvel atualizado com sucesso!']);
    }

    public function setStatus(Request $request)
    {        
        $property = Property::find($request->id);
        $property->status = $request->status;
        $property->save();
        return response()->json(['success' => true]);
    }

    public function highlightMark(Request $request)
    {
        $property = Property::find($request->id);
        $allProperties = Property::where('id', '!=', $property->id)->get();

        foreach ($allProperties as $all) {
            $all->highlight = 0;
            $all->save();
        }

        $property->highlight = true;
        $property->save();

        $json = [
            'success' => true,
        ];

        return response()->json($json);         
    }

    public function setCover(Request $request)
    {
        $imageSetCover = propertyGb::where('id', $request->image)->first();
        $allImage = propertyGb::where('property', $imageSetCover->property)->get();

        foreach ($allImage as $image) {
            $image->cover = null;
            $image->save();
        }

        $imageSetCover->cover = true;
        $imageSetCover->save();

        $json = [
            'success' => true,
        ];

        return response()->json($json);
    }

    public function imageRemove(Request $request)
    {
        $imageDelete = propertyGb::where('id', $request->image)->first();

        Storage::delete($imageDelete->path);
        $imageDelete->delete();

        $json = [
            'success' => true,
        ];
        return response()->json($json);
    }

    public function delete(Request $request)
    {
        $property = Property::where('id', $request->id)->first();
        $propertyGb = propertyGb::where('property', $request->id)->first();
        $nome = \App\Helpers\Renato::getPrimeiroNome(Auth::user()->name);
        if(!empty($property) && !empty($propertyGb)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este imóvel? Ele possui imagens e todas serão excluídas!";
            return response()->json(['error' => $json,'id' => $property->id]);
        }elseif(!empty($property) && empty($propertyGb)){
            $json = "<b>$nome</b> você tem certeza que deseja excluir este imóvel?";
            return response()->json(['error' => $json,'id' => $property->id]);
        }else{
            return response()->json(['success' => true]);
        }
    }

    public function deleteon(Request $request)
    {
        //deleta as integrações
        // $deletePimovel = PortalImoveis::where('imovel', $request->imovel_id)->first();
        // if($deletePimovel != null){
        //     $deletePimovel = PortalImoveis::where('imovel', $request->imovel_id)->get();
        //     foreach($deletePimovel as $delete){
        //         $delete->delete();
        //     }            
        // } 

        $property = Property::where('id', $request->property_id)->first();  
        $propertyDelete = propertyGb::where('property', $request->property_id)->first();
        $propertyR = $property->title;
        if(!empty($property)){
            if(!empty($propertyDelete)){
                Storage::delete($propertyDelete->path);
                $propertyDelete->delete();
                Storage::deleteDirectory(env('AWS_PASTA').'properties/'.$property->id);
                $property->delete();
            }
            $property->delete();
        }
        return redirect()->route('properties.index')->with(['color' => 'success', 'message' => 'O imóvel '.$propertyR.' foi removido com sucesso!']);
    }
}
