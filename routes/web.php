<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FrontendController::class, 'loginPage']);
Route::post('/login', [BackendController::class, 'login'])->name('login');
Route::get('/logout',[BackendController::class, 'logout'])->name('logout');

Route::get('/soaptest', [BackendController::class, 'soapTest']);
Route::get('/usersoaptest/{id}', [BackendController::class, 'getSoapUserTest']);


Route::group(['middleware' => ['auth:web']], function (){
    Route::get('/home/{homeType?}', [FrontendController::class, 'home']);
    Route::get('/search/{homeType?}/', [FrontendController::class, 'search'])->name('search');

    Route::get('/addnew',[FrontendController::class, 'addNewContract']);
    Route::post('/addnewcontract', [BackendController::class, 'addNewContract'])->name('addNewContract');
    Route::get('/editcontract/{id}', [FrontendController::class, 'editContract']);
    Route::post('/editcontract', [BackendController::class, 'editContract'])->name('editCotract');

    Route::get('/addnewuser/{id?}', [FrontendController::class, 'addNewUser']);
    Route::post('/addnewuser', [BackendController::class, 'addNewUser'])->name('addNewUser');
    Route::post('edituser', [BackendController::class, 'editUser'])->name('editUser');

    Route::get('/export/{searchobj?}', [BackendController::class, 'exportExcel']);
    Route::get('/exporttest/{test}', [BackendController::class, 'exportExcel2']);


    Route::prefix('/ajax')->group(function (){
        Route::get('/naknada/{id}', [BackendController::class, 'getStavkaFakture']);
        Route::get('/getuser/{id}', [BackendController::class, 'getSoapUser']);
        Route::get('/deleteuser/{id}', [BackendController::class, 'deleteUser']);

        Route::prefix('/delete')->group(function (){
            Route::get('/stavkafakture/{id}', [BackendController::class, 'deleteStavkaFakture']);
            Route::get('/tipugovora/{id}', [BackendController::class, 'deleteTipUgovora']);
            Route::get('/tipservisa/{id}', [BackendController::class, 'deleteTipServisa']);
            Route::get('/tehnologije/{id}', [BackendController::class, 'deleteTehnologije']);
            Route::get('/partner/{id}', [BackendController::class, 'deletePartnera']);
            Route::get('/nazivservisa/{id}', [BackendController::class, 'deleteServis']);
            Route::get('/vrstasenzora/{id}', [BackendController::class, 'deleteVrstuSenzora']);
            Route::get('/lokacijaapp/{id}', [BackendController::class, 'deleteLokacijuApp']);
            Route::get('/komercijalniuslov/{id}', [BackendController::class, 'deleteKomUslov']);
            Route::get('/dekativirajugovor/{id}', [BackendController::class, 'deaktivirajUgovor']);
        });
    });

    Route::prefix('/menage')->group(function (){
        Route::controller(FrontendController::class)->group(function (){
            Route::get('/stavkafakture/{id?}', 'dodajStavkuFakture');
            Route::get('/tipugovora/{id?}', 'dodajTipUgovora');
            Route::get('/tipservisa/{id?}', 'dodajTipServisa');
            Route::get('/tehnologije/{id?}', 'dodajTehnologije');
            Route::get('/partner/{id?}', 'dodajPartnera');
            Route::get('/nazivservisa/{id?}', 'dodajServis');
            Route::get('/vrstasenzora/{id?}', 'dodajVrstuSenzora');
            Route::get('/lokacijaapp/{id?}', 'dodajLokacijuApp');
        });

        Route::controller(BackendController::class)->group(function(){
            Route::post('/stavkafakture', 'dodajStavkuFakture')->name('insertStavkaFakture');
            Route::post('/tipugovora', 'dodajTipUgovora')->name('insertTipUgovora');
            Route::post('/tipservisa', 'dodajTipServisa')->name('insertTipServisa');
            Route::post('/tehnologije', 'dodajTehnologije')->name('insertTehnologija');
            Route::post('/partner', 'dodajPartnera')->name('insertPartnera');
            Route::post('/nazivservisa', 'dodajNazivServisa')->name('insertServisa');
            Route::post('/vrstasenzora', 'dodajVrstuSenzora')->name('insertVrstuSenzora');
            Route::post('/lokacijaapp', 'dodajLokacijuApp')->name('insertLokacijuApp');
        });
    });

    Route::prefix('/edit')->group(function (){
        Route::controller(BackendController::class)->group(function(){
            Route::post('/stavkafakture', 'editStavkaFakture')->name('editStavkaFakture');
            Route::post('/tipugovora', 'editTipUgovora')->name('editTipUgovora');
            Route::post('/tipservisa', 'editTipServisa')->name('editTipServisa');
            Route::post('/tehnologije', 'editTehnologije')->name('editTehnologija');
            Route::post('/partner', 'editPartnera')->name('editPartnera');
            Route::post('/nazivservisa', 'editNazivServisa')->name('editServisa');
            Route::post('/vrstasenzora', 'editVrstaSenzora')->name('editVrstaSenzora');
            Route::post('/lokacijaapp', 'editLokacijuApp')->name('editLokacijuApp');
        });
    });
});

