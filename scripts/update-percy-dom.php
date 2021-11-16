<?php
/**
 * Update Percy DOM
 *
 * This script will download the Percy CLI package from https://npmjs.org and extract the compiled `bundle.js` for use
 * when inserting into a browser window to extract the serialized DOM.
 */

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://registry.npmjs.org/@percy/dom',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true
]);

$response = curl_exec($curl);
if (curl_errno($curl)) {
    throw new Exception(
        sprintf('Something went wrong when attempting to retrieve package info: %s', curl_error($curl))
    );
}

curl_close($curl);

$packageInfo = json_decode((string) $response, true);
$latestVersion = $packageInfo['dist-tags']['latest'] ?? null;
if (!$latestVersion) {
    throw new Exception('Latest package version not found');
}

$downloadUrl = $packageInfo['versions'][$latestVersion]['dist']['tarball'] ?? null;
if (!$downloadUrl) {
    throw new Exception('Download URL not found');
}

$tarPath = __DIR__ . '/dom-' . $latestVersion . '.tgz';

file_put_contents($tarPath, fopen($downloadUrl, 'r'));

(new PharData($tarPath))->extractTo(__DIR__, 'package/dist/bundle.js', true);

rename(__DIR__ . '/package/dist/bundle.js', __DIR__ . '/../resources/bundle.js');
unlink($tarPath);
