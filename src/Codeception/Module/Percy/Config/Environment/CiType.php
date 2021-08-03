<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\Environment;

use MyCLabs\Enum\Enum;

final class CiType extends Enum
{
    const TRAVIS = 'travis';

    const JENKINS = 'jenkins';

    const JENKINS_PULL_REQUEST_BUILDER = 'jenkins-prb';

    const CIRCLE = 'circle';

    const CODESHIP = 'codeship';

    const DRONE = 'drone';

    const SEMAPHORE = 'semaphore';

    const BUILDKITE = 'buildkite';

    const HEROKU = 'heroku';

    const GITLAB = 'gitlab';

    const AZURE = 'azure';

    const APPVEYOR = 'appveyor';

    const PROBO = 'probo';

    const BITBUCKET = 'bitbucket';

    const GITHUB = 'github';

    const NETLIFY = 'netlify';

    const UNKNOWN = 'CI/unknown';

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function TRAVIS() : CiType
    {
        return self::from(self::TRAVIS);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function JENKINS() : CiType
    {
        return self::from(self::JENKINS);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function JENKINS_PULL_REQUEST_BUILDER() : CiType
    {
        return self::from(self::JENKINS_PULL_REQUEST_BUILDER);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function CIRCLE() : CiType
    {
        return self::from(self::CIRCLE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function CODESHIP() : CiType
    {
        return self::from(self::CODESHIP);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function DRONE() : CiType
    {
        return self::from(self::DRONE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function SEMAPHORE() : CiType
    {
        return self::from(self::SEMAPHORE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function BUILDKITE() : CiType
    {
        return self::from(self::BUILDKITE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function HEROKU() : CiType
    {
        return self::from(self::HEROKU);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function GITLAB() : CiType
    {
        return self::from(self::GITLAB);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function AZURE() : CiType
    {
        return self::from(self::AZURE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function APPVEYOR() : CiType
    {
        return self::from(self::APPVEYOR);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function PROBO() : CiType
    {
        return self::from(self::PROBO);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function BITBUCKET() : CiType
    {
        return self::from(self::BITBUCKET);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function GITHUB() : CiType
    {
        return self::from(self::GITHUB);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function NETLIFY() : CiType
    {
        return self::from(self::NETLIFY);
    }

    /**
     * @return \Codeception\Module\Percy\Config\Environment\CiType
     */
    public static function UNKNOWN() : CiType
    {
        return self::from(self::UNKNOWN);
    }
}
