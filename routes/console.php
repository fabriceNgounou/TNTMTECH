<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('La technologie utile, proche de vous.');
})->purpose('Afficher la devise TNTMTECH');
