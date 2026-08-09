<?php

/**
 * Configuración de verticales de negocio.
 * Cada clave coincide con restaurants.type.
 */
return [

    'restaurant' => [
        'label'            => 'Restaurante',
        'items_label'      => 'Platos',
        'category_label'   => 'Categorías',
        'has_stock'        => false,
        'has_sku'          => false,
        'default_template' => 'minimal',
        'url_label'        => 'menú',
        'icon'             => 'M5 4v7a3 3 0 0 0 6 0V4M8 11v9M19 4c-1.7 1-2.5 2.7-2.5 5s.8 3.4 2.5 4v7',
    ],

    'store' => [
        'label'            => 'Tienda',
        'items_label'      => 'Productos',
        'category_label'   => 'Categorías',
        'has_stock'        => true,
        'has_sku'          => true,
        'default_template' => 'store',
        'url_label'        => 'catálogo',
        'icon'             => 'M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0',
    ],

    'services' => [
        'label'            => 'Servicios',
        'items_label'      => 'Servicios',
        'category_label'   => 'Categorías',
        'has_stock'        => false,
        'has_sku'          => false,
        'default_template' => 'carta',
        'url_label'        => 'servicios',
        'icon'             => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
    ],

];
