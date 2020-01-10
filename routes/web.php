<?php
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

// Route::get('/test', function () {

//     return view('frontend.master');
// });
Route::get('qr-code', function ()
{
    echo "ABC<br>";
    return QrCode::size(200)->backgroundColor(255,255,0)->generate('A basic example of QR code!');
});
// Auth::routes();
Route::post('/register', 'Auth\RegisterController@register')->middleware(['web','guest'])->name('register');

Route::post('/login', 'Auth\LoginController@login')->middleware(['web', 'guest'])->name('login');

Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->middleware(['web', 'guest'])->name('password.email');

Route::post('/logout', 'Auth\LoginController@logout')->middleware(['web', 'auth'])->name('logout');

Route::get('/home', 'HomeController@index')->name('home');
