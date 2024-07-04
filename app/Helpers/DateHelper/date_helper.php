<?php

namespace App\Helpers;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

function ensureBucketExists1($bucketName)
{
    $s3Client = new S3Client([
        'version' => 'latest',
        'region'  => env('AWS_DEFAULT_REGION'),
        'endpoint' => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => true,
        'credentials' => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
    ]);

    try {
        $result = $s3Client->headBucket(['Bucket' => $bucketName]);
    } catch (AwsException $e) {
        if ($e->getAwsErrorCode() == 'NotFound') {
            $s3Client->createBucket(['Bucket' => $bucketName]);
        } else {
            throw $e;
        }
    }
}

if (!function_exists('randomData2')) {
    function randomData2()
    {
        return __DIR__ . 1;
    }
}