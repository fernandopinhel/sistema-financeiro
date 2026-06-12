<?php
return [

    // O nome da chave no .env é GTM_ID
    'gtm_id' => env('GTM_ID'),

    'ga_measurement_id' => env('GA_MEASUREMENT_ID'),
    // O nome da chave no .env é GOOGLE_ANALYTICS_ID
    'ga_id' => env('GOOGLE_ANALYTICS_ID'),

    // Estes já estavam corretos
    'hotjar_id' => env('HOTJAR_ID'),
    'hotjar_sv' => env('HOTJAR_SV', 6),

];