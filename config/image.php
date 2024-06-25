<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'profile_image_path_view'                    => 'storage/images/profile/',
    'api_profile_image_path_view'                => 'storage/images/profile/',
    'profile_image_path_store'                   => 'storage/app/public/images/profile',

    'profile_cover_image_path_view'              => 'images/profile_cover',
    'api_profile_cover_image_path_view'          => 'storage/images/profile_cover/',
    'profile_cover_image_path_store'             => 'storage/app/public/images/profile_cover',

    'category_image_path_view'                   => 'storage/images/category/',
    'api_category_image_path_view'               => 'storage/images/category/',
    'category_image_path_store'                  => 'storage/app/public/images/category',

    'product_image_path_view'                    => 'storage/images/product/',
    'api_product_image_path_view'                => 'storage/images/product/',
    'product_image_path_store'                   => 'storage/app/public/images/product',

    'package_image_path_view'                    => 'storage/images/package/',
    'api_package_image_path_view'                => 'storage/images/package/',
    'package_image_path_store'                   => 'storage/app/public/images/package',

    'special_occasion_image_path_view'           => 'storage/images/special_occasion/',
    'api_special_occasion_image_path_view'       => 'storage/images/special_occasion/',
    'special_occasion_image_path_store'          => 'storage/app/public/images/special_occasion',

    'api_image_test_view'                    => 'storage/images/test/',
    'api_image_test_store'                   => 'storage/app/public/images/test',

    'gallery_image_path_view'           => 'storage/images/gallery/',
    'api_gallery_image_path_view'       => 'storage/images/gallery/',
    'gallery_image_path_store'          => 'storage/app/public/images/gallery',

    'homePage_image_path_view'          => 'storage/images/homePageImage/',
    'api_homePage_image_path_view'      => 'storage/images/homePageImage/',
    'homePage_image_path_store'         => 'storage/app/public/images/homePageImage',

    'menuPage_image_path_view'          => 'storage/images/MenuPageImage/',
    'menuPage_image_path_store'         => 'storage/app/public/images/MenuPageImage',

    'logo_image_path_view'              => 'storage/images/logo/',
    'logo_image_path_store'             => 'storage/app/public/images/logo',

    'menuList_image_path_view'          => 'storage/images/menuList/',
    'menuList_image_path_store'         => 'storage/app/public/images/menuList',

];
