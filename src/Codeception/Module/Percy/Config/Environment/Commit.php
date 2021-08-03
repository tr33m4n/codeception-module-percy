<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class Commit
{
    public static function resolve() : ?string
    {
        if (isset($_ENV['PERCY_COMMIT'])) {
            return $_ENV['PERCY_COMMIT'];
        }

        switch ((string) Ci::resolve()) {
            case CiType::TRAVIS:
                $commit = $_ENV['TRAVIS_COMMIT'] ?? null;
                break;
            case CiType::JENKINS_PULL_REQUEST_BUILDER:
                $commit = $_ENV['ghprbActualCommit'] ?? $_ENV['GIT_COMMIT'] ?? null;
                break;
            case CiType::JENKINS:
                $commit = 'TODO' ?? $_ENV['GIT_COMMIT'] ?? null;
                break;
            case CiType::CIRCLE:
                $commit = $_ENV['CIRCLE_SHA1'] ?? null;
                break;
            case CiType::CODESHIP:
                $commit = $_ENV['CI_COMMIT_ID'] ?? null;
                break;
            case CiType::DRONE:
                $commit = $_ENV['DRONE_COMMIT'] ?? null;
                break;
            case CiType::SEMAPHORE:
                $commit = $_ENV['REVISION']
                    ?? $_ENV['SEMAPHORE_GIT_PR_SHA']
                    ?? $_ENV['SEMAPHORE_GIT_SHA']
                    ?? null;
                break;
            case CiType::BUILDKITE:
                $commit = isset($_ENV['BUILDKITE_COMMIT']) && $_ENV['BUILDKITE_COMMIT'] !== 'HEAD'
                    ? $_ENV['BUILDKITE_COMMIT']
                    : null;
                break;
            case CiType::HEROKU:
                $commit = $_ENV['HEROKU_TEST_RUN_COMMIT_VERSION'] ?? null;
                break;
            case CiType::GITLAB:
                $commit = $_ENV['CI_COMMIT_SHA'] ?? null;
                break;
            case CiType::AZURE:
                $commit = $_ENV['SYSTEM_PULLREQUEST_SOURCECOMMITID'] ?? $_ENV['BUILD_SOURCEVERSION'] ?? null;
                break;
            case CiType::APPVEYOR:
                $commit = $_ENV['APPVEYOR_PULL_REQUEST_HEAD_COMMIT'] ?? $_ENV['APPVEYOR_REPO_COMMIT'] ?? null;
                break;
            case CiType::BITBUCKET:
                $commit = $_ENV['BITBUCKET_COMMIT'] ?? null;
                break;
            case CiType::GITHUB:
                $commit = 'TODO';
                break;
            case CiType::PROBO:
            case CiType::NETLIFY:
                $commit = $_ENV['COMMIT_REF'] ?? null;
                break;
            default:
                $commit = null;
        }

        return $commit;
    }
}
