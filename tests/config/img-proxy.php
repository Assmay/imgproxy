<?php

/**
 * A remote microservice that resizes images.
 * More info https://github.com/DarthSim/imgproxy
 */

return [
    'base_url'       => env('IMGPROXY_URL'),

    //security
    'key'            => env('IMGPROXY_KEY'),
    'salt'           => env('IMGPROXY_SALT'),

    //possible values (just for reference)
    'resize'         => 'fit',
    'width'          => 640,
    'height'         => 360,
    'gravity'        => 'no',
    /**
     * If set to 0, imgproxy will not enlarge the image
     * if it is smaller than the given size.
     * With any other value, imgproxy will enlarge the image.
     */
    'enlarge'        => 0,
    'extension'      => 'png',

    //limitations
    'resize_values'  => ['fit', 'fill', 'crop'],
    'gravity_values' => [
        'no', // north (top edge)
        'so', // south (bottom edge)
        'ea', // east (right edge)
        'we', // west (left edge)
        'ce', // center
        'sm', // smart
    ],
    /**
     * MaxSrcDimension for width or height in pixels
     */
    'max_dim_px'     => 8192,
    /**
     * The supported formats
     */
    'formats'        => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
];
