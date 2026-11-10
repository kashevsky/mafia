<?php

use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\AccountMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Account;
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

Route::get('/play/{table_id}', 'App\Http\Controllers\PlayController@index')->name('play');


Route::get('/modal/{table_id?}', 'App\Http\Controllers\PlayController@modal')->name('modal');
Route::get('/modal2/{game_id?}', 'App\Http\Controllers\PlayController@modal2')->name('modal2');

Route::post('/send-payment-data', '\App\Http\Controllers\PlayController@sendPaymentData')->name('send.payment.data');


Route::get('/login', '\App\Http\Controllers\Admin\LoginController@index')->name('admin.login');
Route::post('/login', '\App\Http\Controllers\Admin\LoginController@make')->name('admin.login.make');

Route::get('/login/user', '\App\Http\Controllers\LoginController@index')->name('login');
Route::post('/login/user', '\App\Http\Controllers\LoginController@make')->name('login.make');

Route::get('/register/user', '\App\Http\Controllers\LoginController@register')->name('register');
Route::post('/register/user', '\App\Http\Controllers\LoginController@registerMake')->name('register.make');
Route::group(['middleware' => AccountMiddleware::class], function () {
    Route::get('/account', 'App\Http\Controllers\AccountController@show')->name('account.show');

    Route::get('/', 'App\Http\Controllers\MainController@index')->name('club.index');


    Route::get('/events', '\App\Http\Controllers\GameController@index')->name('game.index');
    Route::post('/change-status-game', '\App\Http\Controllers\GameController@changeStatus')->name('game.change-status');
    Route::get('/event/{id}', '\App\Http\Controllers\GameController@show')->name('game.show');
    Route::post('/booking-game', '\App\Http\Controllers\GameController@booking')->name('game.booking');
    Route::get('/test', function () {
        return view('test');
    });
    // Route::get('/')->name('admin.game.save-seating');


    Route::get('/participants', '\App\Http\Controllers\MainController@participants')->name('participants');
    Route::get('/account/index', function () {
        return view('account.index');
    });
    Route::get('/rating', '\App\Http\Controllers\MainController@rating')->name('rating');
    Route::get('/store', 'App\Http\Controllers\StoreController@index')->name('store.index');
    Route::get('/orders', '\App\Http\Controllers\PaymentController@index')->name('payment.index');
    Route::post('/payment/receipt', '\App\Http\Controllers\PaymentController@makeReceipt')->name('payment.receipt.make');

    Route::post('/buy-item', '\App\Http\Controllers\PaymentController@buyItem')->name('payment.buy.item');
});
Route::group(['prefix' => 'admin', 'middleware' => AdminMiddleware::class], function () {
    //клубы
    Route::get('/', '\App\Http\Controllers\Admin\MainController@index')->name('admin.index');
    Route::get('/clubs', '\App\Http\Controllers\Admin\ClubController@index')->name('admin.club.index');
    Route::get('/club/edit/{id}', '\App\Http\Controllers\Admin\ClubController@edit')->name('admin.club.edit');
    Route::get('/club/delete/{id}', '\App\Http\Controllers\Admin\ClubController@delete')->name('admin.club.delete');
    Route::get('/club/create', '\App\Http\Controllers\Admin\ClubController@create')->name('admin.club.create');
    Route::post('/club/store', '\App\Http\Controllers\Admin\ClubController@store')->name('admin.club.store');
    Route::post('/club/update', '\App\Http\Controllers\Admin\ClubController@update')->name('admin.club.update');
    //игры
    Route::get('/games', '\App\Http\Controllers\Admin\GameController@index')->name('admin.game.index');
    Route::get('/game/delete/{id}', '\App\Http\Controllers\Admin\GameController@delete')->name('admin.game.delete');
    Route::get('/game/create', '\App\Http\Controllers\Admin\GameController@create')->name('admin.game.create');
    Route::get('/game/edit/{id}', '\App\Http\Controllers\Admin\GameController@edit')->name('admin.game.edit');
    Route::post('/game/store', '\App\Http\Controllers\Admin\GameController@store')->name('admin.game.store');
    Route::post('/game/update', '\App\Http\Controllers\Admin\GameController@update')->name('admin.game.update');

    Route::get('/game/slide/delete/{id}', '\App\Http\Controllers\Admin\GameController@slideDelete')->name('admin.game.slide.delete');
    //админы
    Route::get('/admins', '\App\Http\Controllers\Admin\AdminController@index')->name('admin.admin.index');
    Route::get('/admin/create', '\App\Http\Controllers\Admin\AdminController@create')->name('admin.admin.create');
    Route::get('/admin/edit/{id}', '\App\Http\Controllers\Admin\AdminController@edit')->name('admin.admin.edit');
    Route::post('/admin/store', '\App\Http\Controllers\Admin\AdminController@store')->name('admin.admin.store');
    Route::post('/admin/delete', '\App\Http\Controllers\Admin\AdminController@delete')->name('admin.admin.delete');
    Route::post('/admin/update/{id}', '\App\Http\Controllers\Admin\AdminController@update')->name('admin.admin.update');

    Route::get('/club/generate-invite-link', '\App\Http\Controllers\Admin\MainController@makeLink')->name('make.link');

    Route::get('/logout', '\App\Http\Controllers\Admin\LoginController@logout')->name('admin.logout');


    //аккаунты
    Route::get('/accounts', '\App\Http\Controllers\Admin\AccountController@index')->name('admin.account.index');
    Route::get('/account/create', '\App\Http\Controllers\Admin\AccountController@create')->name('admin.account.create');
    Route::get('/account/edit/{id}', '\App\Http\Controllers\Admin\AccountController@edit')->name('admin.account.edit');
    Route::post('/account/store', '\App\Http\Controllers\Admin\AccountController@store')->name('admin.account.store');
    Route::post('/account/update', '\App\Http\Controllers\Admin\AccountController@update')->name('admin.account.update');
    Route::get('/account/delete/{id}', '\App\Http\Controllers\Admin\AccountController@delete')->name('admin.account.delete');

    //заказы
    Route::get('/orders', '\App\Http\Controllers\Admin\PaymentController@index')->name('payment.index');
    Route::post('/orders/confirm/{id}', '\App\Http\Controllers\Admin\PaymentController@confirm')->name('payment.confirm');
    Route::post('/orders/unconfirm/{id}', '\App\Http\Controllers\Admin\PaymentController@unconfirm')->name('payment.unconfirm');

    //мерч
    Route::get('/merch', '\App\Http\Controllers\Admin\MerchController@index')->name('admin.merch.index');
    Route::get('/merch/delete/{id}', '\App\Http\Controllers\Admin\MerchController@delete')->name('admin.merch.delete');
    Route::get('/merch/create', '\App\Http\Controllers\Admin\MerchController@create')->name('admin.merch.create');
    Route::get('/merch/edit/{id}', '\App\Http\Controllers\Admin\MerchController@edit')->name('admin.merch.edit');
    Route::post('/merch/update/{id}', '\App\Http\Controllers\Admin\MerchController@update')->name('admin.merch.update');
    Route::post('/merch/store', '\App\Http\Controllers\Admin\MerchController@store')->name('admin.merch.store');


    //отчеты
    Route::get('/reports', '\App\Http\Controllers\Admin\ReportController@index')->name('admin.report.index');

    Route::post('/orders/confirm/{id}', '\App\Http\Controllers\Admin\PaymentController@confirm')->name('payment.confirm');
    Route::post('/orders/unconfirm/{id}', '\App\Http\Controllers\Admin\PaymentController@unconfirm')->name('payment.unconfirm');

    //места
    // Route::get('/seats/{id}', '\App\Http\Controllers\Admin\SeatsController@show')->name('seats.show');
    // Route::post('/seats-save', '\App\Http\Controllers\Admin\SeatsController@save')->name('admin.seats.save');

    Route::get('/roles/{id?}', '\App\Http\Controllers\Admin\RolesController@index')->name('roles.index');


    Route::get('/broadcast', '\App\Http\Controllers\Admin\BroadcastController@index')->name('broadcast.index');

    Route::post('/send-test-broadcast', '\App\Http\Controllers\Admin\BroadcastController@testSend')->name('broadcast.test.send');
    Route::post('/broadcast-store', '\App\Http\Controllers\Admin\BroadcastController@store')->name('broadcast.store');
    Route::post('/send-broadcast', '\App\Http\Controllers\Admin\BroadcastController@send')->name('broadcast.send');


    Route::get('/categories', '\App\Http\Controllers\Admin\CategoryController@index')->name('admin.category.index');
    Route::get('/category/create', '\App\Http\Controllers\Admin\CategoryController@create')->name('admin.category.create');
    Route::get('/category/{id}', '\App\Http\Controllers\Admin\CategoryController@show')->name('admin.category.show');
    Route::get('/category/delete/{id}', '\App\Http\Controllers\Admin\CategoryController@delete')->name('admin.category.delete');
    Route::get('/category/edit/{id}', '\App\Http\Controllers\Admin\CategoryController@edit')->name('admin.category.edit');
    Route::post('/category/update', '\App\Http\Controllers\Admin\CategoryController@update')->name('admin.category.update');
    Route::post('/category/store', '\App\Http\Controllers\Admin\CategoryController@store')->name('admin.category.store');

    Route::get('/product/create/{id?}', '\App\Http\Controllers\Admin\ProductController@create')->name('admin.product.create');
    Route::get('/products', '\App\Http\Controllers\Admin\ProductController@index')->name('admin.product.index');
    Route::get('/product/{id}', '\App\Http\Controllers\Admin\ProductController@show')->name('admin.product.show');
    Route::get('/product/edit/{id}', '\App\Http\Controllers\Admin\ProductController@edit')->name('admin.product.edit');
    Route::post('/product/update', '\App\Http\Controllers\Admin\ProductController@update')->name('admin.product.update');

    Route::post('/product/store', '\App\Http\Controllers\Admin\ProductController@store')->name('admin.product.store');
    Route::get('/product/delete/{id}', '\App\Http\Controllers\Admin\ProductController@delete')->name('admin.product.delete');
    Route::get('/product/slide/delete/{id}', '\App\Http\Controllers\Admin\ProductController@slideDelete')->name('admin.product.slide.delete');
    Route::get('/blog', 'App\Http\Controllers\Admin\BlogController@index')->name('admin.blog.index');
    Route::get('/blog/create', 'App\Http\Controllers\Admin\BlogController@create')->name('admin.blog.create');
    Route::get('/blog/edit/{id}', 'App\Http\Controllers\Admin\BlogController@edit')->name('admin.blog.edit');
    Route::get('/blog/delete/{id}', 'App\Http\Controllers\Admin\BlogController@delete')->name('admin.blog.delete');
    Route::post('/blog/store', 'App\Http\Controllers\Admin\BlogController@store')->name('admin.blog.store');
    Route::post('/blog/update', 'App\Http\Controllers\Admin\BlogController@update')->name('admin.blog.update');


    Route::get('/review', 'App\Http\Controllers\Admin\ReviewController@index')->name('admin.review.index');
    Route::get('/review/create', 'App\Http\Controllers\Admin\ReviewController@create')->name('admin.review.create');
    Route::get('/review/edit/{id}', 'App\Http\Controllers\Admin\ReviewController@edit')->name('admin.review.edit');
    Route::get('/review/delete/{id}', 'App\Http\Controllers\Admin\ReviewController@delete')->name('admin.review.delete');
    Route::post('/review/store', 'App\Http\Controllers\Admin\ReviewController@store')->name('admin.review.store');
    Route::post('/review/update', 'App\Http\Controllers\Admin\ReviewController@update')->name('admin.review.update');

    Route::get('/info', 'App\Http\Controllers\Admin\InfoController@edit')->name('admin.info.edit');
    Route::post('/info/update', 'App\Http\Controllers\Admin\InfoController@update')->name('admin.info.update');
});

Route::get('/invite/{code}', '\App\Http\Controllers\MainController@invite')->name('invite');
Route::post('/account/update', '\App\Http\Controllers\AccountController@update')->name('account.update');




Route::get('delegate', function () {
    return view('delegate');
});
Route::post('/admin/games/{game}/confirm-players', [GameController::class, 'confirmPlayers'])
    ->name('admin.game.confirm-players');


Route::post('/log-step', '\App\Http\Controllers\StepController@log')->name('log.step');
Route::get('/info', '\App\Http\Controllers\MainController@info')->name('info');
Route::post('/info-send', '\App\Http\Controllers\MainController@infoSend')->name('info-send');
Route::post('/admin/mafia/save-roles', [RolesController::class, 'saveRoles'])->name('admin.mafia.save');


Route::get('/admin/table/create/{game_id}/{is_dop}', '\App\Http\Controllers\Admin\TableController@create')->name('table.create');
Route::get('/admin/table/create-after-game/{table_id}', '\App\Http\Controllers\Admin\TableController@createAfterGame')->name('table.create-after-game');
Route::post('/admin/table/seats/save', '\App\Http\Controllers\Admin\TableController@save')->name('admin.seats.save');
Route::get('/table/edit/{id}', '\App\Http\Controllers\Admin\TableController@edit')->name('table.edit');
Route::post('/admin/table/seats/update', '\App\Http\Controllers\Admin\TableController@update')->name('admin.seats.update');
Route::get('/table/delete/{id}', '\App\Http\Controllers\Admin\TableController@delete')->name('table.delete');
Route::get('/table/run/{id}', '\App\Http\Controllers\Admin\TableController@run')->name('table.run');
Route::get('/table/stop/{id}', '\App\Http\Controllers\Admin\TableController@stop')->name('table.stop');
Route::get('/random-roles/{game_id}', '\App\Http\Controllers\Admin\TableController@randomRoles');
Route::get('/random-roles-next-tables/{game_id}', '\App\Http\Controllers\Admin\TableController@randomRolesNextTables')->name('admin.table.random-next');
Route::get('/make-game-public/{id}', '\App\Http\Controllers\PlayController@makePublic')->name('admin.game.makePublic');


Route::post('/kill-player', '\App\Http\Controllers\PlayController@killPlayer')->name('kill.player');
Route::get('/results/{id}', '\App\Http\Controllers\GameController@results')->name('results');
Route::post('/admin/game/skip/{id}', '\App\Http\Controllers\Admin\GameController@skip')->name('game.skip');
Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramWebhookController::class, 'webhook']);

Route::post('/game/{game}/cancel', [GameController::class, 'cancel'])->name('game.cancel');
Route::post('/game/{game}/publish', [GameController::class, 'publish'])->name('game.publish');


Route::get('/admin/table/make-public-question/{id}', '\App\Http\Controllers\Admin\TableController@makePublicQuestion')->name('admin.table.make-public-question');
Route::get('/admin/table/make-public-game/{id}', '\App\Http\Controllers\Admin\TableController@makePublicGame')->name('admin.table.make-public-game');

Route::get('/test-screen', function () {
    return view('test-screen');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Страница с тестовыми результатами
    Route::get('/game/results-test', [App\Http\Controllers\Admin\GameController::class, 'resultsTest'])
        ->name('game.results-test');

    // Приём скриншота
    Route::post('/game/screenshot', [App\Http\Controllers\Admin\GameController::class, 'storeScreenshot'])
        ->name('game.screenshot.store');
    Route::post('/game/screenshot-finish', [App\Http\Controllers\Admin\GameController::class, 'storeScreenshotFinish'])
        ->name('game.screenshot_finish.store');
});

Route::post('/change-role', '\App\Http\Controllers\AccountController@changeRole')->name('account.change-role');
Route::get('/page', function () {
    return view('page');
});

Route::get('/save-results/{id}', function ($id) {
    $temp = \App\Models\TempPublic::find($id);
    return view('admin.table.save-results', compact('temp'));
});

Route::get('/save-results-finish/{src}', function ($src) {

    return view('admin.table.save-results-finish', compact('src'));
});

Route::post('/pay-entities',        [PaymentController::class, 'store']);
Route::put('/pay-entities/{payEntity}', [PaymentController::class, 'update']);

Route::get('/t', function () {
    return view('t');
});


Route::get('/activate-immunity/{id}', '\App\Http\Controllers\AccountController@activateImmunity')->name('account.activateImmunity');
Route::post('/broadcast-delete/{id}', '\App\Http\Controllers\Admin\BroadcastController@delete')->name('broadcast.delete');
Route::post('/cancel-booking-game', '\App\Http\Controllers\GameController@cancelBooking')->name('game.cancel-booking');


Route::post('/make-oboroten', '\App\Http\Controllers\PlayController@makeOboroten');



// лендинг ФОКС
Route::get('/fox', function () {
    return view('fox-site.index');
});
Route::get('/temp-public/{id}', '\App\Http\Controllers\Admin\TableController@showSnapshot')->name('temp-public.show');

Route::get('/thanks', '\App\Http\Controllers\AccountController@thanks')->name('account.thanks');


Route::post('/update-booking-game', '\App\Http\Controllers\GameController@updateBooking')->name('game.update-booking');


Route::get('/account/show/{id}', '\App\Http\Controllers\AccountController@showTarget');/
