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

Route::get('/',[
	'as'=>'home',
	'uses'=>'MyController@getIndex'
]);
Route::get('video',[
	'as'=>'video',
	'uses'=>'MyController@getVideo'
]);
Route::get('dang-nhap',[
	'as'=>'login',
	'uses'=>'MyController@getLogin'
]);
Route::post('dang-nhap',[
	'as'=>'login',
	'uses'=>'MyController@postLogin'
]);
Route::get('mysentences/{id}',[
	'as'=>'mysentences',
	'uses'=>'MyController@getMySentences'
]);
Route::get('mysentencesadd/{id1}/{id2}/{id3}/{id4}/{id5}/{id6}',[
	'as'=>'mysentences',
	'uses'=>'MyController@getAddMySentences'
]);
Route::get('dang-ki',[
	'as'=>'signin',
	'uses'=>'MyController@getSignin'
]);

Route::post('dang-ki',[
	'as'=>'signin',
	'uses'=>'MyController@postSignin'
]);
Route::get('mysentences/xoa/{id}',[
	'as'=>'xoa',
	'uses'=>'MyController@postXoa'
]);
Route::get('dang-xuat',[
	'as'=>'logout',
	'uses'=>'MyController@postLogout'
]);
Route::get('myvideo',[
	'as'=>'myvideo',
	'uses'=>'MyController@getMyvideo'
]);