# codeception-module-percy
Percy https://percy.io module for Codeception

## Requirements
- Node.js `>=10.0.0`
- PHP `>= 7.2` for `v1.0.x`, `>= 7.3` for `v1.1.x`
- Composer `v1` for `>= v1.0.x`, `v2` for `v1.1.x`

### Difference between versions
The difference between `v1.0.x` and `v1.1.x` is `v1.1.x` does not launch the Percy agent until after a successful test suite run. This means that on failure, nothing is sent to Percy. Due to the EOL of PHP 7.2 however and some limitations of supporting modules, `v1.0.x` sends an empty Percy job to the Percy dashboard which is listed as failed.

## Installation
```shell script
composer require --dev tr33m4n/codeception-module-percy
```

## Upgrading from v1.0.x to v1.1.x
The way in which the Percy agent is started and stopped in `v1.1.x` changes significantly from `v1.0.x`. You no longer need to prefix your Codeception run command with `npx percy exec --` :tada:

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
| Parameter                         | Type   | Default                               | Description                                                                                       |
| --------------------------------- | ------ | ------------------------------------- | ------------------------------------------------------------------------------------------------- |
| `driver`                          | string | `WebDriver`                           | Set an alternative driver                                                                         |
| `agentEndpoint`                   | string | `http://localhost:5338`               | The endpoint used for operations within the Percy agent                                           |
| `agentJsPath`                     | string | `percy-agent.js`                      | The path relative to the agent endpoint to retrieve the agent JS                                  |
| `agentPostPath`                   | string | `percy/snapshot`                      | The path relative to the agent endpoint to post a snapshot to                                     |
| `agentConfig`                     | object | `{"handleAgentCommunication": false}` | Additional configuration to pass the the `PercyAgent` class when initialised within Chrome driver |
| `snapshotConfig`                  | object | `{}`                                  | Additional configuration to pass to the "snapshot" functionality                                  |
| `snapshotConfig.percyCSS`         | string | `null`                                | Percy specific CSS to apply to the "snapshot"                                                     |
| `snapshotConfig.minHeight`        | int    | `null`                                | Minimum height of the resulting "snapshot" in pixels                                              |
| `snapshotConfig.enableJavaScript` | bool   | `false`                               | Enable JavaScript in the Percy rendering environment                                              |
| `snapshotConfig.widths`           | array  | `null`                                | An array of integers representing the browser widths at which you want to take snapshots          |
| `throwOnAdapterError`             | bool   | `false`                               | [debug] Throw exception on adapter error
| `cleanSnapshotStorageOnFail`      | bool   | `false`                               | [debug] Clean stored snapshot HTML after failure
| `cleanSnapshotStorageOnSuccess`   | bool   | `false`                               | [debug] Clean stored snapshot HTML after success

## Running
For Percy snapshot collection to work, Codeception needs to be wrapped in the Percy agent `exec` command, for example:
```shell script
npx percy exec -- php vendor/bin/codecept run --steps
```
This will require your `PERCY_TOKEN` to be set before running. For more information, see https://docs.percy.io/docs/environment-variables#section-required

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
