<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiType;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiTypePool;
use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider;
use tr33m4n\CodeceptionModulePercyEnvironment\GitEnvironment;
use tr33m4n\Di\Container\GetParameters;
use tr33m4n\Di\Container\GetPreference;

return [
    GetPreference::CONFIG_KEY => [
        ClientInterface::class => Client::class
    ],
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
            'base_uri' => 'https://percy.io/api/v1',
            'headers' => [
                'Authorization' => sprintf('Token token=%s', $_ENV['PERCY_TOKEN'] ?? '')
            ]
        ],
        GitEnvironment::class => [
            'gitRepoPath' => codecept_root_dir()
        ],
        EnvironmentProvider::class => [
            'webDriver' => config('webDriver'),
            'packageName' => 'tr33m4n/codeception-module-percy'
        ]
    ]
];
