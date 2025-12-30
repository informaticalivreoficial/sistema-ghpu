<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\{
    NotificationsList,
    Settings,
};
use App\Livewire\Dashboard\Users\{
    Time,
    Users,
    ViewUser,
    Form,
};
use App\Livewire\Dashboard\Permissions\Index as PermissionIndex;
use App\Livewire\Dashboard\Roles\Index as RoleIndex;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ConfigController,
    EmailController,
    TemplateController,
    UserController
};
use App\Http\Controllers\Web\{
    FeedController,
    Webcontroller
};
use App\Livewire\Dashboard\Companies\Companies;
use App\Livewire\Dashboard\Companies\CompanyForm;
use App\Livewire\Dashboard\Ocorrencias\OcorrenciaForm;
use App\Livewire\Dashboard\Ocorrencias\Ocorrencias;
use App\Livewire\Dashboard\Posts\CatPosts;
use App\Livewire\Dashboard\Posts\PostForm;
use App\Livewire\Dashboard\Posts\Posts;
use App\Livewire\Dashboard\Slides\SlideForm;
use App\Livewire\Dashboard\Slides\Slides;


Route::group(['namespace' => 'Web', 'as' => 'web.'], function () {
    //Institucional
    Route::get('/', [WebController::class, 'home'])->name('home');
    Route::get('/politica-de-privacidade', [WebController::class, 'privacy'])->name('privacy');

    // Route::get('/teste-r2', function () {
    //     Storage::disk('s3')->put('example.txt', 'Hello World');
    //     //return Storage::disk('s3')->url('teste.txt');
    // });

    // Route::get('/debug-disk', function () {
    //     return [
    //         'default_disk' => config('filesystems.default'),
    //         'disk_exists' => Storage::disk('s3') !== null,
    //     ];
    // });

    // Route::get('/debug-r2-list', function () {
    //     try {
    //         return Storage::disk('s3')->files('/');
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }
    // });

//     /** FEED */
//     Route::get('feed', [FeedController::class, 'feed'])->name('feed');
    
//     Route::get('/sitemap', [WebController::class, 'sitemap'])->name('sitemap');

//     /** Página de Experiências - Específica de uma categoria */
//     Route::get('/experiencias/{slug}', [FilterController::class, 'experienceCategory'])->name('experienceCategory');

     //Properties
     Route::get('pesquisar-imoveis', [WebController::class, 'pesquisaImoveis'])->name('pesquisar-imoveis');
     Route::get('imoveis/{slug}', [WebController::class, 'Property'])->name('property');
     Route::get('imoveis/categoria/{type}', [WebController::class, 'propertyList'])->name('propertylist');
     Route::get('imoveis/bairro/{neighborhood}', [WebController::class, 'propertyNeighborhood'])->name('properties.neighborhood');
     Route::get('lancamentos', [WebController::class, 'PropertyHighliths'])->name('highliths');
     Route::get('imoveis', [WebController::class, 'Properties'])->name('properties');

     //Client
     Route::get('/atendimento', [WebController::class, 'contact'])->name('contact');
     Route::get('/simulador-de-credito-imobiliario', [WebController::class, 'creditSimulator'])->name('simulator');
     

     //Blog
     Route::get('/blog/artigo/{slug}', [WebController::class, 'artigo'])->name('blog.artigo');
     Route::get('/blog/noticia/{slug}', [WebController::class, 'noticia'])->name('blog.noticia');
     Route::get('/blog/categoria/{slug}', [WebController::class, 'blogCategory'])->name('blog.category');
     Route::get('/blog', [WebController::class, 'blog'])->name('blog.index');

     //Page
     Route::get('/pagina/{slug}', [WebController::class, 'page'])->name('page');
});



Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin'], function () {

    //Somente Gerente e Super Admin
    Route::middleware('role:super-admin|admin')->group(function () {
        Route::get('configuracoes', Settings::class)->name('settings');

        // Companies
        Route::get('empresas', Companies::class)->name('companies.index');
        Route::get('empresas/cadastrar-empresa', CompanyForm::class)->name('companies.create');
        Route::get('empresas/{company}/editar-empresa', CompanyForm::class)->name('companies.edit');
        Route::get('empresas/{company}/visualizar-empresa', ViewUser::class)->name('companies.view');
    });

    Route::get('/', Dashboard::class)->name('admin');
    

    Route::get('notificacoes', NotificationsList::class)->name('notifications.index');    

   
    //*********************** Slides ********************************************/
    Route::get('slides/{slide}/editar', SlideForm::class)->name('slides.edit');
    Route::get('slides/cadastrar', SlideForm::class)->name('slides.create');
    Route::get('slides', Slides::class)->name('slides.index');

    //*********************** Posts *********************************************/
    Route::get('posts/{post}/editar', PostForm::class)->name('posts.edit');
    Route::get('posts/cadastrar', PostForm::class)->name('posts.create');
    Route::get('posts', Posts::class)->name('posts.index');

    //*********************** Categorias de Posts ********************************/
    Route::get('posts/categorias', CatPosts::class)->name('posts.categories.index');
    //Route::get('posts/categorias/cadastrar/{parent?}', CatPostForm::class)->name('posts.categories.create');
    //Route::get('posts/categorias/{category}/editar', CatPostForm::class)->name('posts.categories.edit');

    //*********************** Usuários *******************************************/
    Route::get('/cargos', RoleIndex::class)->name('admin.roles');
    Route::get('/permissoes', PermissionIndex::class)->name('admin.permissions');

    Route::get('ocorrencias', Ocorrencias::class)->name('ocorrencias.index');
    Route::get('ocorrencias/{id}/editar', OcorrenciaForm::class)->name('ocorrencia.edit');
    Route::get('ocorrencias/cadastrar', OcorrenciaForm::class)->name('ocorrencia.create');

    Route::get('usuarios/clientes', Users::class)->name('users.index');
    Route::get('usuarios/time', Time::class)->name('users.time');
    Route::get('usuarios/cadastrar', Form::class)->name('users.create');
    Route::get('usuarios/{userId}/editar', Form::class)->name('users.edit');
    Route::get('usuarios/{user}/visualizar', ViewUser::class)->name('users.view');  

    //*********************** Email **********************************************/
    Route::get('email/suporte', [EmailController::class, 'suporte'])->name('email.suporte');
    Route::match(['post', 'get'], 'email/enviar-email', [EmailController::class, 'send'])->name('email.send');
    Route::post('email/sendEmail', [EmailController::class, 'sendEmail'])->name('email.sendEmail');
    Route::match(['post', 'get'], 'email/success', [EmailController::class, 'success'])->name('email.success');

});


// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});
