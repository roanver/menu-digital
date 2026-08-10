<?php

/**
 * Configuración centralizada de planes.
 * Todos los límites, precios y features viven acá.
 * -1 = sin límite.
 */
return [

    /*
     * Cargos únicos
     */
    'installation_price'   => 35_000,   // CLP, pago único
    'extra_location_price' => 7_990,    // CLP/mes por local adicional

    'plans' => [

        'free' => [
            'name'                 => 'Gratis',
            'price'                => 0,
            'max_items'            => 20,
            'max_categories'       => 3,
            'max_screens'          => 0,
            'max_nfc'              => 0,
            'max_tables'           => 0,
            'ai_imports_per_month' => 0,
        ],

        'basico' => [
            'name'                 => 'Básico',
            'price'                => 9_990,
            'max_items'            => -1,
            'max_categories'       => -1,
            'max_screens'          => 0,
            'max_nfc'              => -1,
            'max_tables'           => 15,
            'ai_imports_per_month' => 3,
        ],

        'pro' => [
            'name'                 => 'Pro',
            'price'                => 19_990,
            'max_items'            => -1,
            'max_categories'       => -1,
            'max_screens'          => 3,
            'max_nfc'              => -1,
            'max_tables'           => -1,
            'ai_imports_per_month' => -1,
        ],

    ],

];
