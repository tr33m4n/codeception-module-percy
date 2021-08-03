<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

class Info
{
    public static function resolve() : string
    {
        switch ((string) Ci::resolve()) {
            case CiType::SEMAPHORE:
                $info = isset($_ENV['SEMAPHORE_GIT_SHA']) ? sprintf('%s/2.0', CiType::SEMAPHORE) : CiType::SEMAPHORE;
                break;
            case CiType::GITLAB:
                $info = sprintf('%s/%s', CiType::GITLAB, $_ENV['CI_SERVER_VERSION'] ?? '');
                break;
            case CiType::GITHUB:
                $info = isset($_ENV['PERCY_GITHUB_ACTION'])
                    ? sprintf('%s/%s', CiType::GITHUB, $_ENV['PERCY_GITHUB_ACTION'] ?? '')
                    : CiType::GITHUB;
                break;
            default:
                $info = (string) Ci::resolve();
        }

        return $info;
    }
}
