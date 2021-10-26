# codeception-module-percy
Percy https://percy.io module for Codeception

## Requirements
### `v1.1.x`
- Node.js `>=10.0.0`
- PHP `>= 7.2`
- Composer `v1`

### `v2.0.x`
- Node.js `>=10.0.0`
- PHP `>= 7.3`
- Composer `v2`

### `v3.0.x`
- Node.js `>=12.0.0`
- PHP `>= 7.3`
- Composer `v2`

## Installation
```shell script
composer require --dev tr33m4n/codeception-module-percy
```

## Upgrading
### `v1.0.x` to `v1.1.x`
The way in which the Percy agent is started and stopped in `v1.1.x` changes significantly from `v1.0.x`. You no longer need to prefix your Codeception run command with `npx percy exec --` :tada:

### `v1.1.x` to `v2.0.x`
`v2.0.x` only supports PHP `7.3` and composer `v2` or later, however the base functionality is the same as `v1.1.x`

### `v2.0.x` to `v3.0.x`
`v3.0.x` only supports Node `12` or later, however the base functionality is the same as `v2.0.x`. Configuration settings will need to be migrated however due to the change in naming convention. Most notable is `agentConfig` becoming `serializeConfig` and the removal of the `handleAgentCommunication` option.

## Example Configuration
The following example configuration assumes the `WebDriver` module has been configured correctly for your test suite
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

### Configuration Options
| Parameter                         | Type     | Default                               | Description                                                                                                                                                                                                                           |
| --------------------------------- | -------- | ------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `driver`                          | string   | `WebDriver`                           | Set an alternative driver                                                                                                                                                                                                             |
| `snapshotEndpoint`                | string   | `http://localhost:5338`               | The endpoint used for operations within the Percy agent                                                                                                                                                                               |
| `snapshotPath`                    | string   | `percy/snapshot`                      | The path relative to the agent endpoint to post a snapshot to                                                                                                                                                                         |
| `snapshotConfig`                  | object   | `{}`                                  | Additional configuration to pass to the "snapshot" functionality                                                                                                                                                                      |
| `snapshotConfig.percyCSS`         | string   | `null`                                | Percy specific CSS to apply to the "snapshot"                                                                                                                                                                                         |
| `snapshotConfig.minHeight`        | int      | `null`                                | Minimum height of the resulting "snapshot" in pixels                                                                                                                                                                                  |
| `snapshotConfig.enableJavaScript` | bool     | `false`                               | Enable JavaScript in the Percy rendering environment                                                                                                                                                                                  |
| `snapshotConfig.widths`           | array    | `null`                                | An array of integers representing the browser widths at which you want to take snapshots                                                                                                                                              |
| `serializeConfig`                 | object   | `{"enableJavaScript": true}`          | Additional configuration to pass to the `PercyDOM.serialize` method injected into the web driver DOM                                                                                                                                  |
| `snapshotServerTimeout`           | int\|null | `null`                               | [debug] The length of the time the Percy snapshot server will listen for incoming snapshots and send on to Percy.io (the amount of time needed to send all snapshots after a successful test suite run). No timeout is set by default |
| `throwOnAdapterError`             | bool     | `false`                               | [debug] Throw exception on adapter error                                                                                                                                                                                              |
| `cleanSnapshotStorage`            | bool     | `false`                               | [debug] Clean stored snapshot HTML after run                                                                                                                                                                                          |

## Running
The Percy integration runs automatically with the test suite but will need your `PERCY_TOKEN` to be set to successfully send snapshots. For more information, see https://docs.percy.io/docs/environment-variables#section-required
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
