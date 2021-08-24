<?php

use App\Http\Controllers\UpdateController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

$updateController = new UpdateController();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

// Artisan::command('duplicateLuasTanam', function() use($updateController){
//     $updateController->duplicateLuasTanam();
// })->describe("Migrasi data luas tanam");

Artisan::command('migrasiData', function() use($updateController){
    $updateController->migrasiData();
})->describe("Migrasi dan sesuaikan data");
