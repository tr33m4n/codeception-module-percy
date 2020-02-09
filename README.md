# codeception-module-percy
Percy https://percy.io module for Codeception

## Installation
```shell script
composer require tr33m4n/codeception-module-percy
```
If you're using version `4.x` of Codeception, ensure that you have also installed the `codeception/module-webdriver` package. For more information see https://codeception.com/docs/modules/WebDriver

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
        $I->wantToTakeAPercySnapshot('My snapshot name');
    }
}
```
`$I->wantToTakeAPercySnapshot` can optionally be passed additional arguments to override the global settings for `percyCSS`, `minHeight`, `enableJavaScript` and `widths` on the fly, for example:
```php
/**
 * @param string $name             Snapshot name
 * @param int[]  $widths           Browser breakpoint widths
 * @param int    $minHeight        Minimum height of the resulting snapshot
 * @param string $percyCss         Percy specific CSS
 * @param bool   $enableJavaScript Enable/disable JavaScript 
 */
$I->wantToTakeAPercySnapshot('My snapshot name', [1024, 768, 320], 1080, 'iframe { display: none; }', true);
```