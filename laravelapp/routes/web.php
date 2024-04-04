<?php

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
Route::group(['middleware' => 'auth'], function() {

    //ホーム画面
    Route::get('/', 'HomeController@index')->name('home');

    //フォルダ作成
    Route::get('/folders/create', 'FolderController@ShowCreateForm')->name('folders.create');
    Route::post('/folders/create', 'FolderController@create');

    
    Route::group(['middleware' => 'can:view,folder'], function() {
        
        //一覧画面
        Route::get('/folders/{folder}/tasks', 'TaskController@index')->name('tasks.index');

        //タスク作成
        Route::get('/folders/{folder}/tasks/create', 'TaskController@showCreateForm')->name('tasks.create');
        Route::post('/folders/{folder}/tasks/create', 'TaskController@create');

        //タスク編集
        Route::get('/folders/{folder}/tasks/{task}/edit', 'TaskController@showEditForm')->name('tasks.edit');
        Route::post('/folders/{folder}/tasks/{task}/edit', 'TaskController@edit');

    });

});


Auth::routes();
