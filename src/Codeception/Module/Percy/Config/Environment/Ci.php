<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class Ci
{
    public static function resolve() : ?CiType
    {
        switch (true) {
            case isset($_ENV['TRAVIS_BUILD_ID']):
                $ci = CiType::TRAVIS();
                break;
            case isset($_ENV['JENKINS_URL']) && isset($_ENV['ghprbPullId']):
                $ci = CiType::JENKINS_PULL_REQUEST_BUILDER();
                break;
            case isset($_ENV['JENKINS_URL']):
                $ci = CiType::JENKINS();
                break;
            case isset($_ENV['CIRCLECI']):
                $ci = CiType::CIRCLE();
                break;
            case isset($_ENV['CI_NAME']) && $_ENV['CI_NAME'] === 'codeship':
                $ci = CiType::CODESHIP();
                break;
            case isset($_ENV['DRONE']) && $_ENV['DRONE'] === 'true':
                $ci = CiType::DRONE();
                break;
            case isset($_ENV['SEMAPHORE']) && $_ENV['SEMAPHORE'] === 'true':
                $ci = CiType::SEMAPHORE();
                break;
            case isset($_ENV['BUILDKITE']) && $_ENV['BUILDKITE'] === 'true':
                $ci = CiType::BUILDKITE();
                break;
            case isset($_ENV['HEROKU_TEST_RUN_ID']):
                $ci = CiType::HEROKU();
                break;
            case isset($_ENV['GITLAB_CI']) && $_ENV['GITLAB_CI'] === 'true':
                $ci = CiType::GITLAB();
                break;
            case isset($_ENV['TF_BUILD']) && $_ENV['TF_BUILD'] === 'True':
                $ci = CiType::AZURE();
                break;
            case isset($_ENV['APPVEYOR']) && ($_ENV['APPVEYOR'] === 'True' || $_ENV['APPVEYOR'] === 'true'):
                $ci = CiType::APPVEYOR();
                break;
            case isset($_ENV['PROBO_ENVIRONMENT']) && $_ENV['PROBO_ENVIRONMENT'] === 'TRUE':
                $ci = CiType::PROBO();
                break;
            case isset($_ENV['BITBUCKET_BUILD_NUMBER']):
                $ci = CiType::BITBUCKET();
                break;
            case isset($_ENV['GITHUB_ACTIONS']) && $_ENV['GITHUB_ACTIONS'] === 'true':
                $ci = CiType::GITHUB();
                break;
            case isset($_ENV['NETLIFY']) && $_ENV['NETLIFY'] === 'true':
                $ci = CiType::NETLIFY();
                break;
            case isset($_ENV['CI']):
                $ci = CiType::UNKNOWN();
                break;
            default:
                $ci = null;
        }

        return $ci;
    }
}
