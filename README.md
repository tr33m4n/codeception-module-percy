# codeception-module-percy
Percy https://percy.io module for Codeception

## Requirements
- Node.js `>=14.0.0`
- PHP `>= 7.4`
- Composer `v2`

## Installation
```shell script
composer require --dev tr33m4n/codeception-module-percy
```

## Example Configuration
The following example `acceptance.suite.yml` configuration assumes the `WebDriver` module has been configured correctly for your test suite and
shows enabling the Percy module and setting some basic configuration:
```yaml
modules:
    enabled:
        - WebDriver
        - Percy
    config:
      Percy:
        snapshotConfig:
          widths:
            - 1024
            - 768
            - 320
          minHeight: 1080
```
The following example shows how to configure the `percy:process-snapshots` in the `codeception.yml` file:
```yaml
extensions:
    commands:
        - Codeception\Module\Percy\Command\ProcessSnapshots
```

### Configuration Options
| Parameter                          | Type      | Default                      | Description                                                                                                                                                                                                                           |
|------------------------------------|-----------|------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `snapshotConfig`                   | object    | `{}`                         | Additional configuration to pass to the "snapshot" functionality                                                                                                                                                                      |
| `snapshotConfig.percyCSS`          | string    | `null`                       | Percy specific CSS to apply to the "snapshot"                                                                                                                                                                                         |
| `snapshotConfig.minHeight`         | int       | `null`                       | Minimum height of the resulting "snapshot" in pixels                                                                                                                                                                                  |
| `snapshotConfig.enableJavaScript`  | bool      | `false`                      | Enable JavaScript in the Percy rendering environment                                                                                                                                                                                  |
| `snapshotConfig.widths`            | array     | `null`                       | An array of integers representing the browser widths at which you want to take snapshots                                                                                                                                              |
| `serializeConfig`                  | object    | `{"enableJavaScript": true}` | Additional configuration to pass to the `PercyDOM.serialize` method injected into the web driver DOM                                                                                                                                  |
| `collectOnly`                      | bool      | `false`                      | Setting this to `true` will only collect snapshots, rather than collect and then send at the end of the run. They can then be sent manually by calling the `vendor/bin/codecept percy:process-snapshots` command                      |
| `snapshotServerTimeout`            | int       | `null`                       | [debug] The length of the time the Percy snapshot server will listen for incoming snapshots and send on to Percy.io (the amount of time needed to send all snapshots after a successful test suite run). No timeout is set by default |
| `snapshotServerPort`               | int       | `5338`                       | [debug] The port the Percy snapshot server will listen on                                                                                                                                                                             |
| `throwOnAdapterError`              | bool      | `false`                      | [debug] Throw exception on adapter error                                                                                                                                                                                              |
| `instanceId`                       | string    | `null`                       | [debug] An ID is used to differentiate between one Codeception runs output files to another, ensuring only the current runs output files are cleared on failure. Use this config to pass a custom instance ID                         |

## Running
The Percy integration runs automatically with the test suite but will need your `PERCY_TOKEN` to be set to successfully send snapshots. For more information, see https://docs.percy.io/docs/environment-variables#section-required
### Overriding the `node` path
By default, the `node` executable used will be the one defined within the `PATH` of the user running the test suite. This can be overridden however, by setting the environment variable `PERCY_NODE_PATH` to your preferred location.
### Collect only
In some advanced CI setups, it might make sense to collect all snapshots for multiple runs with different parameters and then send them a single time when all runs are complete. This can be achieved by setting the `collectOnly` config to `true`. Once all runs are complete, running the command `vendor/bin/codecept percy:process-snapshots`
will then iterate all collected snapshots, send to Percy and then clean up the snapshot folder
### Example Test
```php
<?php

class FirstCest
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->takeAPercySnapshot('My snapshot name');
    }
}
```
`$I->takeAPercySnapshot` can optionally be passed an array of additional arguments to override the global settings for `percyCSS`, `minHeight`, `enableJavaScript` and `widths` on the fly, for example:
```php

use Codeception\Module\Percy\Exchange\Payload;

$I->takeAPercySnapshot('My snapshot name', [
    Payload::WIDTHS => [1024, 768, 320], // Browser breakpoint widths
    Payload::MIN_HEIGHT => 1080, // Minimum height of the resulting snapshot
    Payload::PERCY_CSS => 'iframe { display: none; }', // Percy specific CSS
    Payload::ENABLE_JAVASCRIPT => true // Enable/disable JavaScript
]);
```
