<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    Property,
    User
};
use Illuminate\Http\Request;
use Analytics;
use Spatie\Analytics\Period;

class AdminController extends Controller
{
    public function home()
    {
        //Users
        $time = User::where('admin', 1)
                        ->orWhere('editor', 1)
                        ->where('client', 0)
                        ->count();
        $usersAvailable = User::where('client', 1)
                        ->where('admin', 0)
                        ->where('editor', 0) 
                        ->where('superadmin', 0)
                        ->available()
                        ->count();
        $usersUnavailable = User::where('client', 1)
                        ->where('admin', 0)
                        ->where('editor', 0)
                        ->where('superadmin', 0)
                        ->unavailable()
                        ->count();
        //Artigos
        // $postsArtigos = Post::where('tipo', 'artigo')->where('tenant_id', auth()->user()->tenant->id)->count();
        // $postsPaginas = Post::where('tipo', 'pagina')->where('tenant_id', auth()->user()->tenant->id)->count();
        // $postsNoticias = Post::where('tipo', 'noticia')->where('tenant_id', auth()->user()->tenant->id)->count();
        // $artigosTop = Post::where('tipo', 'artigo')
        //         ->limit(4)
        //         ->where('tenant_id', auth()->user()->tenant->id)
        //         ->postson()
        //         ->get()
        //         ->sortByDesc('views');
        // $totalViewsArtigos = Post::selectRaw('SUM(views) AS VIEWS')
        //         ->where('tipo', 'artigo')
        //         ->where('tenant_id', auth()->user()->tenant->id)
        //         ->postson()
        //         ->first();
        // $paginasTop = Post::where('tipo', 'pagina')
        //         ->limit(4)
        //         ->where('tenant_id', auth()->user()->tenant->id)
        //         ->postson()
        //         ->get()
        //         ->sortByDesc('views');
        // $totalViewsPaginas = Post::selectRaw('SUM(views) AS VIEWS')
        //         ->where('tipo', 'pagina')
        //         ->where('tenant_id', auth()->user()->tenant->id)
        //         ->postson()
        //         ->first(); 
        // $imoveisTop = Imovel::limit(6)
        //         ->available()
        //         ->where('tenant_id', auth()->user()->tenant->id) 
        //         ->get()               
        //         ->sortByDesc('views');
        // $totalViewsImoveis = Imovel::selectRaw('SUM(views) AS VIEWS')
        //         ->where('tenant_id', auth()->user()->tenant->id)
        //         ->available()
        //         ->first();          
           
        //Properties
        $propertyAvailable = Property::available()->count();
        $propertyUnavailable = Property::unavailable()->count();
        //Empresas
        //$empresasAvailable = Empresa::available()->count();
        //$empresasUnavailable = Empresa::unavailable()->count();
        //$empresasTotal = Empresa::all()->count();
        //Pedidos
        // $pedidosApproved = Pedido::approved()->count();
        // $pedidosInprocess = Pedido::inprocess()->count();
        // $pedidosRejected = Pedido::rejected()->count();

        //Analitcs
        // $visitasHoje = Analytics::fetchMostVisitedPages(Period::days(1));
        
        // $visitas365 = Analytics::fetchTotalVisitorsAndPageViews(Period::months(5));
        
        // $top_browser = Analytics::fetchTopBrowsers(Period::months(5), 10);

        // $analyticsData = Analytics::get(
        //         Period::months(6), 
        //         metrics: ['totalUsers', 'sessions', 'screenPageViews'], 
        //         dimensions: ['month'],
        // );   
        // $sortedData = $analyticsData->sortBy('month'); 
         
        return view('admin.dashboard',[
            'time' => $time,
            'usersAvailable' => $usersAvailable,
            'usersUnavailable' => $usersUnavailable,
            //Artigos
        //     'postsArtigos' => $postsArtigos,
        //     'postsPaginas' => $postsPaginas,
        //     'postsNoticias' => $postsNoticias,
        //     'artigosTop' => $artigosTop,
        //     'artigostotalviews' => $totalViewsArtigos->VIEWS,
        //     'paginasTop' => $paginasTop,
        //     'paginastotalviews' => $totalViewsPaginas->VIEWS,
            //Imóveis            
            //'imoveisTop' => $imoveisTop,
            //'imoveistotalviews' => $totalViewsImoveis->VIEWS, 
            //Imóveis
            'propertyAvailable' => $propertyAvailable,
            'propertyUnavailable' => $propertyUnavailable,
            //Analytics
            // 'visitasHoje' => $visitasHoje,
            // //'visitas365' => $visitas365,
            // 'analyticsData' => $analyticsData,
            // 'top_browser' => $top_browser
        ]);
    }
}
