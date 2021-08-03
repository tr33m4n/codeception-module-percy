<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class PullRequest
{
    public static function resolve() : ?string
    {
        if (isset($_ENV['PERCY_PULL_REQUEST'])) {
            return $_ENV['PERCY_PULL_REQUEST'];
        }

        switch ((string) Ci::resolve()) {
            case CiType::TRAVIS:
                $pullRequest = isset($_ENV['TRAVIS_PULL_REQUEST']) && $_ENV['TRAVIS_PULL_REQUEST'] !== 'false'
                    ? $_ENV['TRAVIS_PULL_REQUEST']
                    : null;
                break;
            case CiType::JENKINS_PULL_REQUEST_BUILDER:
                $pullRequest = $_ENV['ghprbPullId'] ?? null;
                break;
            case CiType::JENKINS:
                $pullRequest = $_ENV['CHANGE_ID'] ?? null;
                break;
            case CiType::CIRCLE:
                if (!isset($_ENV['CI_PULL_REQUESTS'])) {
                    $pullRequest = null;

                    break;
                }

                $ciPullRequestsParts = explode('/', $_ENV['CI_PULL_REQUESTS']);

                $pullRequest = end($ciPullRequestsParts);
                break;
            case CiType::DRONE:
                $pullRequest = $_ENV['CI_PULL_REQUEST'] ?? null;
                break;
            case CiType::SEMAPHORE:
                $pullRequest = $_ENV['PULL_REQUEST_NUMBER'] ?? $_ENV['SEMAPHORE_GIT_PR_NUMBER'] ?? null;
                break;
            case CiType::BUILDKITE:
                $pullRequest = isset($_ENV['BUILDKITE_PULL_REQUEST']) && $_ENV['BUILDKITE_PULL_REQUEST'] !== 'false'
                    ? $_ENV['BUILDKITE_PULL_REQUEST']
                    : null;
                break;
            case CiType::HEROKU:
                $pullRequest = $_ENV['HEROKU_PR_NUMBER'] ?? null;
                break;
            case CiType::GITLAB:
                $pullRequest = $_ENV['CI_MERGE_REQUEST_IID'] ?? null;
                break;
            case CiType::AZURE:
                $pullRequest = $_ENV['SYSTEM_PULLREQUEST_PULLREQUESTID']
                    ?? $_ENV['SYSTEM_PULLREQUEST_PULLREQUESTNUMBER']
                    ?? null;
                break;
            case CiType::APPVEYOR:
                $pullRequest = $_ENV['APPVEYOR_PULL_REQUEST_NUMBER'] ?? null;
                break;
            case CiType::PROBO:
                if (!isset($_ENV['PULL_REQUEST_LINK'])) {
                    $pullRequest = null;

                    break;
                }

                $ciPullRequestsParts = explode('/', $_ENV['PULL_REQUEST_LINK']);

                $pullRequest = end($ciPullRequestsParts);
                break;
            case CiType::BITBUCKET:
                $pullRequest = $_ENV['BITBUCKET_PR_ID'] ?? null;
                break;
            case CiType::GITHUB:
                $pullRequest = 'TODO';
                break;
            case CiType::NETLIFY:
                $pullRequest = isset($_ENV['PULL_REQUEST']) && $_ENV['PULL_REQUEST'] !== 'false'
                    ? $_ENV['PULL_REQUEST']
                    : null;
                break;
            default:
                $pullRequest = null;
        }

        return $pullRequest;
    }
}
