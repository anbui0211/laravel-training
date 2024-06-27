<?php


use Aws\Exception\AwsException;
use Aws\S3\S3Client;


function ensureBucketExists($bucketName)
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
if (!function_exists('randomData')) {
    function randomData()
    {
        return __DIR__ . '/date_helper/';
    }
}
if (!function_exists('includeRouteFiles')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     */
    function includeRouteFiles($folder)
    {
        try {
            $rdi = new RecursiveDirectoryIterator($folder);
            $it = new RecursiveIteratorIterator($rdi);
            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }
                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
includeRouteFiles(__DIR__ . '/DateHelper');
