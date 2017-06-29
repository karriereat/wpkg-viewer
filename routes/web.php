<?php

Route::get('/', function() {
    return View('index');
});
Route::get('/machine/{hostenameParam}', 'MachineController@detail');
Route::get('/machines', 'MachineController@overview');
Route::get('/profile/{profileNameParam}', 'ProfileController@detail');
Route::get('/profiles', 'ProfileController@overview');
Route::get('/programs', 'ProgramController@overview');