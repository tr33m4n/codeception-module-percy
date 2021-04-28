# codeception-module-percy
Percy https://percy.io module for Codeception

## Installation
```shell script
composer require --dev tr33m4n/codeception-module-percy
```
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
| Parameter                         | Type   | Default                               | Description                                                                                                                                                                           |
| --------------------------------- | ------ | ------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `driver`                          | string | `WebDriver`                           | Set an alternative driver                                                                                                                                                             |
| `agentEndpoint`                   | string | `http://localhost:5338`               | The endpoint used for operations within the Percy agent                                                                                                                               |
| `agentPostPath`                   | string | `percy/snapshot`                      | The path relative to the agent endpoint to post a snapshot to                                                                                                                         |
| `agentConfig`                     | object | `{"handleAgentCommunication": false}` | Additional configuration to pass the the `PercyAgent` class when initialised within Chrome driver                                                                                     |
| `snapshotConfig`                  | object | `{}`                                  | Additional configuration to pass to the "snapshot" functionality                                                                                                                      |
| `snapshotConfig.percyCSS`         | string | `null`                                | Percy specific CSS to apply to the "snapshot"                                                                                                                                         |
| `snapshotConfig.minHeight`        | int    | `null`                                | Minimum height of the resulting "snapshot" in pixels                                                                                                                                  |
| `snapshotConfig.enableJavaScript` | bool   | `false`                               | Enable JavaScript in the Percy rendering environment                                                                                                                                  |
| `snapshotConfig.widths`           | array  | `null`                                | An array of integers representing the browser widths at which you want to take snapshots                                                                                              |
| `percyAgentTimeout`               | int    | `120`                                 | The length of the time the Percy agent will listen for incoming snapshots and send on to Percy.io (the amount of time needed to send all snapshots after a successful test suite run) |
| `throwOnAdapterError`             | bool   | `false`                               | [debug] Throw exception on adapter error                                                                                                                                              |
| `cleanSnapshotStorage`            | bool   | `false`                               | [debug] Clean stored snapshot HTML after run                                                                                                                                          |

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

use Codeception\Module\Percy\Payload;

$I->takeAPercySnapshot('My snapshot name', [
    Payload::WIDTHS => [1024, 768, 320], // Browser breakpoint widths
    Payload::MIN_HEIGHT => 1080, // Minimum height of the resulting snapshot
    Payload::PERCY_CSS => 'iframe { display: none; }', // Percy specific CSS
    Payload::ENABLE_JAVASCRIPT => true // Enable/disable JavaScript
]);
```
