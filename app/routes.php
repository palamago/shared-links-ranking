<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*Route::get('/', function()
{
	return View::make('hello');
});
*/
Route::get('/login', function()
{
    if(Auth::check()){
       return Redirect::to('admin'); 
    } else {
        return View::make('site/user/login');
    }
});

Route::post('login', function()
{
        /* Get the login form data using the 'Input' class */
        $userdata = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        );
 
        /* Try to authenticate the credentials */
        if(Auth::attempt($userdata)) 
        {
            // we are now logged in, go to admin
            return Redirect::to('admin');
        }
        else
        {
            return Redirect::to('login');
        }
});

Route::get('logout', function()
{
    Auth::logout();
    return Redirect::to('login');
});

Route::get('/', 'TopController@getTop');

Route::get('/api/newspaper', 'ApiController@getNewspapers');
Route::get('/api/rss', 'ApiController@getRss');
Route::get('/api/tag', 'ApiController@getTags');
Route::get('/api/topnews', 'ApiController@getTopNews');

Route::get('/api/sparklines/{ids}', 'ApiController@getSparklinesData');

Route::get('/api/link/{id}', 'ApiController@getLinkData');

Route::get('/api/historynews', 'ApiController@getHistoryNews');
