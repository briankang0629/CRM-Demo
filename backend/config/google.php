<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Cloud Storage
    |--------------------------------------------------------------------------
    |
    */

    'google_cloud_key_file' => env('GOOGLE_CLOUD_KEY_FILE', storage_path('google-storage-key.json')),
    'google_cloud_project' => env('GOOGLE_CLOUD_PROJECT_ID', 'brian-stage-305415'),
    'google_cloud_storage_bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'application-storage-uploads'),


];
