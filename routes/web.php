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

////원래는 아래 였으나 “BadMethodCallException  : Method Illuminate\Routing\Route::get does not exist.“
////error가 발생하여 "Illuminate\Support\Facades\Route"으로 바꾸었다.
////stackoverflow.com/questions/58067380/how-to-fix-badmethodcallexception-method-illuminate-routing-routeget-does-n

//use Illuminate\Routing\Route; ////

use Illuminate\Support\Facades\Route; ////

Route::get('/', function () {
    return view('welcome');
});

////Auth::routes();
Auth::routes(['verify' => true]); //for email verification==>['verify' => true]

Route::get('profile', function () { //for email verification test =>temp
    // Only verified users may enter...
    return "This is profile";
})->middleware('verified');

Route::get('showVerificationMsg', function () { //register 후에 email verification을 위하여 삽입.
    //만약 이메일 인증이 되었으면 아래 메세지가 나오고(그럴리는 없지만...) 그렇지 않으면 
    //resources/views/auth/verify.blade.php에 있는 메시가 나온다.[=>middleware('verified')]  
    return "이미 이메일 인증이 되었습니다."; //현재는 register 후에 곧바로 이 url로 오도록 RegisterController에 코딩
})->middleware('verified');                //되어 있다.

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/calendar/index', 'CalendarController@index');
Route::get('/calendar/show', 'CalendarController@show');
Route::post('calendar/store', 'CalendarController@store');
Route::post('calendar/update', 'CalendarController@update');
Route::post('calendar/delete', 'CalendarController@destroy');

Route::get('/booking/index', 'BookingController@index');
Route::get('/booking/showAvailableRooms', 'BookingController@showAvailableRooms');
Route::get('/booking/showRoomType', 'BookingController@showRoomType');
Route::get('/booking/createBooking/{room_number}/{type}', 'BookingController@createBooking');
Route::get('/booking/directBooking', 'BookingController@directBooking');
Route::get('/booking/selectAvailableRoom', 'BookingController@selectAvailableRoom');
Route::post('/booking/addBooking', 'BookingController@addBooking');

////아래는 laravel 6 버젼에서 새로 생긴 middleware 'password.confirm'를 사용한다.
////controller 안에서 middleware auth를 사용하고 아래에서 password.confirm으로 한번더 filttering 한다.
////laravel-news.com/new-password-confirmation-in-laravel-6-2
////www.youtube.com/watch?v=YRuHYNSbrug
////config/auth.php ==> 'password_timeout' => 10800, ==> 변경할 수 있다. 10800은 초를 의미한다.
////https://laravel-news.com/new-password-confirmation-in-laravel-6-2
//Route::get('/admin/index', 'AdminController@index')->middleware('password.confirm');
Route::get('/admin/index', 'AdminController@index')->middleware(['auth','role:admin']);
Route::get('/admin/dateList', 'AdminController@dateList')->middleware(['auth','role:admin']);
Route::get('/admin/showBookingByDate', 'AdminController@showBookingByDate')->middleware(['auth','role:admin']);
Route::get('/admin/showRooms', 'AdminController@showRooms')->middleware(['auth','role:admin']);
Route::get('/admin/showAvailableRooms', 'AdminController@showAvailableRooms')->middleware(['auth','role:admin']);


////아래처럼 middleware를 기술 할 수도 있다. ==> 참고로 할 것
////laravel-news.com/new-password-confirmation-in-laravel-6-2
////www.youtube.com/watch?v=YRuHYNSbrug
//Route::group(['middleware' => 'auth'], function(){
//    Route::get('/admin/index', 'AdminController@index')->middleware('password.confirm');
//});

////아래처럼 middleware를 multi로 기술 할 수 있다.
//Route::get('/settings/security', function () {
    // Users must confirm their password before continuing...
// })->middleware(['auth', 'password.confirm']);//==>mult middleware