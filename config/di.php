<?php

declare(strict_types=1);

use Codeception\Module\Percy\Config\CiEnvironment\CiType;
use Codeception\Module\Percy\Config\CiEnvironment\CiTypePool;
use Codeception\Module\Percy\Config\Url;
use GuzzleHttp\Client;
use tr33m4n\Di\Container\GetParameters;
use tr33m4n\Di\Container\GetPreference;

return [
    GetPreference::CONFIG_KEY => [],
    GetParameters::CONFIG_KEY => [
        CiTypePool::class => [
            'ciTypes' => [
                (string) CiType::APPVEYOR() => CiType\AppVeyor::class,
                (string) CiType::AWS_CODEBUILD() => CiType\AwsCodeBuild::class,
                (string) CiType::AZURE_PIPELINES() => CiType\AzurePipelines::class,
                (string) CiType::BAMBOO() => CiType\Bamboo::class,
                (string) CiType::BITBUCKET_PIPELINES() => CiType\BitbucketPipelines::class,
                (string) CiType::BUDDY() => CiType\Buddy::class,
                (string) CiType::CIRCLE() => CiType\Circle::class,
                (string) CiType::CODESHIP() => CiType\CodeShip::class,
                (string) CiType::CONTINUOUSPHP() => CiType\Continuousphp::class,
                (string) CiType::DRONE() => CiType\Drone::class,
                (string) CiType::GITHUB_ACTIONS() => CiType\GitHubActions::class,
                (string) CiType::GITLAB() => CiType\GitLab::class,
                (string) CiType::JENKINS() => CiType\Jenkins::class,
                (string) CiType::SOURCEHUT() => CiType\SourceHut::class,
                (string) CiType::TEAMCITY() => CiType\TeamCity::class,
                (string) CiType::TRAVIS() => CiType\Travis::class,
                (string) CiType::WERCKER() => CiType\Wercker::class,
            ]
        ],
        Client::class => [
            'base_uri' => Url::BASE_API_URL,
            'headers' => [
                'Authorization' => sprintf('Token token=%s', $_ENV['PERCY_TOKEN'] ?? ''),
                'User-Agent' => 'TODO: see https://github.com/percy/cli/blob/4b2a4da4acafd6fd7f5e3084af0642a7eba433db/packages/client/src/client.js#L69',
                'Content-Type' => 'application/vnd.api+json'
            ]
        ]
    ]
];
