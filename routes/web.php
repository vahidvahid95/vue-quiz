<?php
use Illuminate\Support\Facades\Route;

//Route::group(array('domain' => 'app.{domain}'), function () {
    Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');
//});
