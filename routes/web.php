<?php

/**
 * The routes for the admin dashboard within the platform.
 */
Route::get('/admin/dashboard', 'Admin\AdminController@index')->name('dashboard');

/**
 * The routes for the dashboard within the platform.
 */
Route::view('/', 'layouts.home')->name('/');
Route::get('/', 'Boardgame\BoardgameController@index')->name('/');
Route::post('/', 'MailController@sendMail');

/**
 * The routes for the authentication within the platform.
 */
Auth::routes();

/**
 * The routes for the 'user' section within the platform.
 */
Route::get('/user/ranking/{id}', 'User\RankingController@index');
Route::get('/user/get/{username}', 'UserController@get');
Route::post('/user/edit/', 'UserController@update')->name('user/edit');
Route::get('/user/edit/', 'UserController@edit')->name('user/edit');
Route::get('/register/verify/{confirmationCode}', 'Auth\RegisterController@confirm');

/**
 * The routes for the 'boardgame' section within the platform.
 */
Route::get('/boardgame/ranking/{id}', 'Boardgame\RankingController@index');
Route::get('/boardgame{search?}', 'Boardgame\BoardgameController@getAllBoardgames')->name('boardgame');
Route::get('/boardgame/get/{id}', 'Boardgame\BoardgameController@get')->name('boardgame/get');
Route::post('/boardgame/get/{id}', 'Boardgame\BoardgameController@updateLike');

/**
 * The routes for the 'notifications' section within the platform
 */
Route::get('/notifications', 'NotificationController@index')->name('notifications')->middleware('auth');
Route::post('/notifications', 'NotificationController@post')->name('notifications_post')->middleware('auth');

/**
 * The routes for the 'category' section within the CMS.
 */
Route::get('/admin/category', 'Admin\Category\CategoryController@index')->name('admin/category');
Route::get('/admin/category/get/{id}', 'Admin\Category\CategoryController@get')->name('admin/category/get');
Route::get('/admin/category/create', 'Admin\Category\CategoryController@create')->name('admin/category/create');
Route::post('/admin/category/create', 'Admin\Category\CategoryController@store')->name('admin/category/create');
Route::get('/admin/category/edit/{id}', 'Admin\Category\CategoryController@edit')->name('admin/category/edit');
Route::post('/admin/category/edit/{id}', 'Admin\Category\CategoryController@update')->name('admin/category/edit');

/**
 * The routes for the 'role' section within the CMS.
 */
Route::get('/admin/role', 'Admin\Role\RoleController@index')->name('admin/role');
Route::get('/admin/role/get/{id}', 'Admin\Role\RoleController@get')->name('admin/role/get');
Route::get('/admin/role/create', 'Admin\Role\RoleController@create')->name('admin/role/create');
Route::post('/admin/role/create', 'Admin\Role\RoleController@store')->name('admin/role/create');
Route::get('/admin/role/edit/{id}', 'Admin\Role\RoleController@edit')->name('admin/role/edit');
Route::post('/admin/role/edit/{id}', 'Admin\Role\RoleController@update')->name('admin/role/edit');

/**
 * The routes for the 'boardgame' section within the CMS.
 */
Route::get('/admin/boardgame', 'Admin\Boardgame\BoardgameController@index')->name('admin/boardgame');
Route::get('/admin/boardgame/get/{id}', 'Admin\Boardgame\BoardgameController@get')->name('admin/boardgame/get');
Route::get('/admin/boardgame/create', 'Admin\Boardgame\BoardgameController@create')->name('admin/boardgame/create');
Route::post('/admin/boardgame/create', 'Admin\Boardgame\BoardgameController@store')->name('admin/boardgame/create');
Route::get('/admin/boardgame/edit/{id}', 'Admin\Boardgame\BoardgameController@edit')->name('admin/boardgame/edit');
Route::post('/admin/boardgame/edit/{id}', 'Admin\Boardgame\BoardgameController@save')->name('admin/boardgame/edit');
Route::get('/admin/boardgame/delete/{id}', 'Admin\Boardgame\BoardgameController@delete');
Route::post('/admin/boardgame/delete/{id}', 'Admin\Boardgame\BoardgameController@delete'); //TODO

/**
 * The routes for the 'user' section within the CMS.
 */
Route::get('/admin/user/edit/{user}', 'Admin\User\UserController@edit')->name('editUser');
Route::post('/admin/user/edit/{user}', 'Admin\User\UserController@update')->name('editUser');
Route::group(['middleware' => 'is-ban'], function () {
    Route::get('admin/user', 'Admin\User\UserController@index')->name('adminUsers');
    Route::get('admin/user/revoke/{id}', array('as' => 'revokeUser', 'uses' => 'Admin\User\UserController@revoke'));
    Route::post('admin/user/ban', array('as' => 'banUser', 'uses' => 'Admin\User\UserController@ban'));
});

/**
 * The routes for the 'tournament' section within the platform.
 */
Route::get('/tournament/create', 'TournamentController@create')->name('/tournament/create');
Route::post('/tournament/create', 'TournamentController@store')->name('/tournament/create');
Route::get('/tournament/get/{token}', 'TournamentController@get')->name('/tournament/get');
Route::post('/tournament/get/{token}', 'TournamentController@get');
Route::get('/tournament/edit/{token}', 'TournamentController@edit')->name('tournament/edit');
Route::post('/tournament/edit/{token}', 'TournamentController@update');
Route::post('/tournament/join', 'TournamentController@join')->name('/tournament/join');
Route::get('/tournament/start/{token}', 'TournamentController@start');
Route::get('/tournament/delete/{token}', 'TournamentController@delete');
Route::get('/tournament/started/{token}', 'TournamentController@nextRound');
Route::get('/tournament{search?}', 'TournamentController@getAllTournaments')->name('tournament');
Route::get('/tournament/own{search?}', 'TournamentController@getMyTournaments')->name('ownTournaments');
Route::post('/tournament/declare_winner/{token}', 'TournamentController@declareWinner')->name('tournament_declare_winner');
Route::post('/tournament/invite/{token}', 'TournamentController@invite')->name('tournament_invite');

/**
 * The routes for the 'tournament' section within the platform.
 */
Route::get('/admin/tournament', 'Admin\Tournament\TournamentController@index')->name('admin/tournament');
Route::get('/admin/tournament/get/{token}', 'Admin\Tournament\TournamentController@get')->name('admin/tournament/get');
Route::get('/admin/tournament/create', 'Admin\Tournament\TournamentController@create')->name('admin/tournament/create');
Route::post('/admin/tournament/create', 'Admin\Tournament\TournamentController@store')->name('admin/tournament/create');
Route::get('/admin/tournament/edit/{token}', 'Admin\Tournament\TournamentController@edit')->name('admin/tournament/edit');
Route::post('/admin/tournament/edit/{token}', 'Admin\Tournament\TournamentController@update')->name('admin/tournament/edit');
Route::get('/admin/tournament/delete/{id}', 'Admin\Tournament\TournamentController@delete')->name('admin/delete');
Route::post('/admin/tournament/delete/{id}', 'Admin\Tournament\TournamentController@delete')->name('admin/delete'); //TODO
