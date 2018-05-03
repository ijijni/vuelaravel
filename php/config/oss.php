<?php
return [
                'access_id'     => env('OSS_ACCESS_ID'),
                'access_key'    => env('OSS_ACCESS_KEY'),
                'bucket'        => env('OSS_BUCKET'),
                'endpoint'      => env('OSS_ENDPOINT'),
                'isCName'       => env('OSS_IS_CNAME','false'),
                'debug'         => env('OSS_DEBUG','false'),
];
