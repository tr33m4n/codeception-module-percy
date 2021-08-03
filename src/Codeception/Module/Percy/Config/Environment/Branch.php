<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class Branch
{
    public static function resolve() : ?string
    {
        if (isset($_ENV['PERCY_BRANCH'])) {
            return $_ENV['PERCY_BRANCH'];
        }

        switch ((string) Ci::resolve()) {
            case CiType::TRAVIS:
                $branch = PullRequest::resolve()
                    ? $_ENV['TRAVIS_PULL_REQUEST_BRANCH'] ?? null
                    : $_ENV['TRAVIS_BRANCH'] ?? null;
                break;
            case CiType::JENKINS_PULL_REQUEST_BUILDER:
                $branch = $_ENV['ghprbSourceBranch'] ?? null;
                break;
            case CiType::JENKINS:
                $branch = $_ENV['CHANGE_BRANCH'] ?? $_ENV['GIT_BRANCH'] ?? null;
                break;
            case CiType::CIRCLE:
                $branch = $_ENV['CIRCLE_BRANCH'] ?? null;
                break;
            case CiType::CODESHIP:
                $branch = $_ENV['CI_BRANCH'] ?? null;
                break;
            case CiType::DRONE:
                $branch = $_ENV['DRONE_BRANCH'] ?? null;
                break;
            case CiType::SEMAPHORE:
                $branch = $_ENV['BRANCH_NAME']
                    ?? $_ENV['SEMAPHORE_GIT_PR_BRANCH']
                    ?? $_ENV['SEMAPHORE_GIT_BRANCH']
                    ?? null;
                break;
            case CiType::BUILDKITE:
                $branch = $_ENV['BUILDKITE_BRANCH'] ?? null;
                break;
            case CiType::HEROKU:
                $branch = $_ENV['HEROKU_TEST_RUN_BRANCH'] ?? null;
                break;
            case CiType::GITLAB:
                $branch = $_ENV['CI_COMMIT_REF_NAME'] ?? null;
                break;
            case CiType::AZURE:
                $branch = $_ENV['SYSTEM_PULLREQUEST_SOURCEBRANCH'] ?? $_ENV['BUILD_SOURCEBRANCHNAME'] ?? null;
                break;
            case CiType::APPVEYOR:
                $branch = $_ENV['APPVEYOR_PULL_REQUEST_HEAD_REPO_BRANCH'] ?? $_ENV['APPVEYOR_REPO_BRANCH'] ?? null;
                break;
            case CiType::PROBO:
                $branch = $_ENV['BRANCH_NAME'] ?? null;
                break;
            case CiType::BITBUCKET:
                $branch = $_ENV['BITBUCKET_BRANCH'] ?? null;
                break;
            case CiType::GITHUB:
                $branch = 'TODO';
                break;
            case CiType::NETLIFY:
                $branch = $_ENV['HEAD'] ?? null;
                break;
            default:
                $branch = null;
        }

        // TODO: Test https://github.com/percy/cli/blob/4b2a4da4acafd6fd7f5e3084af0642a7eba433db/packages/env/src/environment.js#L155
        return preg_replace('/^refs\/\w+?\//', '', $branch);
    }
}
