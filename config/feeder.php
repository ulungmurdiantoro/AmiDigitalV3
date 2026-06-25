<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mode Neo Feeder
    |--------------------------------------------------------------------------
    | 'fake' : gunakan data dummy untuk development/demo
    | 'real' : koneksi nyata ke server PDDikti Neo Feeder
    */
    'mode' => env('FEEDER_MODE', 'fake'),
];
