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
    OcorrenciaPdfController,
    TemplateController,
    UserController,
    UserPdfController
};
use App\Http\Controllers\Auth\LoginRgController;
use App\Http\Controllers\Web\{
    FeedController,
    Webcontroller
};
use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard\Companies\Companies;
use App\Livewire\Dashboard\Companies\CompanyForm;
use App\Livewire\Dashboard\Messages\ComposeMessage;
use App\Livewire\Dashboard\Messages\Inbox;
use App\Livewire\Dashboard\Messages\MessagesList;
use App\Livewire\Dashboard\Messages\MessageThread;
use App\Livewire\Dashboard\Ocorrencias\ConfigPassagemDeTurno;
use App\Livewire\Dashboard\Ocorrencias\OcorrenciaForm;
use App\Livewire\Dashboard\Ocorrencias\Ocorrencias;
use App\Livewire\Dashboard\Posts\CatPosts;
use App\Livewire\Dashboard\Posts\PostForm;
use App\Livewire\Dashboard\Posts\Posts;
use App\Livewire\Dashboard\Reports\OccurrencesReport;
use App\Livewire\Dashboard\Slides\SlideForm;
use App\Livewire\Dashboard\Slides\Slides;


Route::group(['namespace' => 'Web', 'as' => 'web.'], function () {
    //Institucional
    //Route::get('/', [WebController::class, 'home'])->name('home');
    Route::get('/', function () {
        return redirect()->route('web.login'); // Redireciona para a rota de login
    })->name('home');
    Route::get('/politica-de-privacidade', [WebController::class, 'privacy'])->name('privacy');

    Route::get('/login', [LoginRgController::class, 'show'])->name('login');
    Route::post('/login', [LoginRgController::class, 'login'])->name('login.rg');

    

//     /** FEED */
//     Route::get('feed', [FeedController::class, 'feed'])->name('feed');
    
//     Route::get('/sitemap', [WebController::class, 'sitemap'])->name('sitemap');

//     /** Página de Experiências - Específica de uma categoria */
//     Route::get('/experiencias/{slug}', [FilterController::class, 'experienceCategory'])->name('experienceCategory');

     

     //Client
     Route::get('/atendimento', [WebController::class, 'contact'])->name('contact');
     
     //Blog
     Route::get('/blog/artigo/{slug}', [WebController::class, 'artigo'])->name('blog.artigo');
     Route::get('/blog/noticia/{slug}', [WebController::class, 'noticia'])->name('blog.noticia');
     Route::get('/blog/categoria/{slug}', [WebController::class, 'blogCategory'])->name('blog.category');
     Route::get('/blog', [WebController::class, 'blog'])->name('blog.index');

     //Page
     Route::get('/pagina/{slug}', [WebController::class, 'page'])->name('page');
});



Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'admin'], function () {

    //Somente Gerente e Super Admin e Admin
    Route::middleware('role:super-admin|admin|manager')->group(function () {
        Route::get('configuracoes', Settings::class)->name('settings');   
        
        Route::get('usuarios/colaboradores', Users::class)->name('users.index');        
        Route::get('usuarios/cadastrar', Form::class)->name('users.create');

        Route::get('/relatorios/ocorrencias', OccurrencesReport::class)->name('reports.occurrences');

        Route::get('/ocorrencias/templates/{type}', ConfigPassagemDeTurno::class)->name('ocorrencias.templates.edit');
         
    });

    // Somente Super Admin e Admin
    Route::middleware('role:super-admin|admin')->group(function () {
        Route::get('empresas', Companies::class)->name('companies.index');
        Route::get('empresas/cadastrar-empresa', CompanyForm::class)->name('companies.create');
        Route::get('empresas/{company}/editar-empresa', CompanyForm::class)->name('companies.edit'); 
        
        Route::get('usuarios/time', Time::class)->name('users.time');
    });

    Route::get('/', Dashboard::class)->name('admin');
    Route::get('notificacoes', NotificationsList::class)->name('notifications.index'); 

    Route::get('ocorrencias/{ocorrencia}/visualizar', [OcorrenciaPdfController::class, 'show'])->name('ocorrencia.pdf');
    Route::get('usuarios/{user}/perfil', [UserPdfController::class, 'profile'])->name('users.profile');   

    Route::get('usuarios/{userId}/editar', Form::class)->name('users.edit');

    Route::get('/mensagens', Inbox::class)->name('messages.inbox');
    Route::get('/mensagens/compose', ComposeMessage::class)->name('messages.compose');
    Route::get('/mensagens/{message}', MessageThread::class)->name('messages.thread');
    
    

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

      

});

// Rotas de recuperação de senha (guest)
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', Login::class)->name('login');
    //Route::get('register', Register::class)->name('register');
    Route::get('change-password', ChangePassword::class)->name('password.change');
});
