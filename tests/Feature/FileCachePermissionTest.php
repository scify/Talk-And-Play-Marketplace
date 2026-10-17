<?php

use Illuminate\Cache\FileStore;
use Illuminate\Filesystem\Filesystem;

test('file cache can write and read with the configured permission', function () {
    $files = new Filesystem;
    $directory = storage_path('framework/testing/file-cache-' . uniqid());
    $store = new FileStore($files, $directory, config('cache.stores.file.permission'));

    try {
        expect($store->put('key', 'value', 60))->toBeTrue()
            ->and($store->get('key'))->toBe('value');

        $folder = dirname($store->path('key'));
        expect(fileperms($folder) & 0007)->toBe(0, 'Other users must have no access to cache folders.');
    } finally {
        // FileStore also applies the permission to the folders it creates.
        exec('chmod -R u+rwx ' . escapeshellarg($directory));
        $files->deleteDirectory($directory);
    }
});
