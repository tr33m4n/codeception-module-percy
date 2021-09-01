<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Config\CiEnvironment;

use MyCLabs\Enum\Enum;

final class CiType extends Enum
{
    public const TRAVIS = 'travis';

    public const JENKINS = 'jenkins';

    public const CIRCLE = 'circle';

    public const CODESHIP = 'codeship';

    public const DRONE = 'drone';

    public const GITLAB = 'gitlab';

    public const AZURE_PIPELINES = 'azure';

    public const APPVEYOR = 'appveyor';

    public const BITBUCKET_PIPELINES = 'bitbucket';

    public const GITHUB_ACTIONS = 'github';

    public const AWS_CODEBUILD = 'aws-codebuild';

    public const BAMBOO = 'bamboo';

    public const BUDDY = 'buddy';

    public const CONTINUOUSPHP = 'continuousphp';

    public const SOURCEHUT = 'sourcehut';

    public const TEAMCITY = 'teamcity';

    public const WERCKER = 'wercker';

    public const UNKNOWN = 'CI/Unknown';

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function TRAVIS(): CiType
    {
        return self::from(self::TRAVIS);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function JENKINS(): CiType
    {
        return self::from(self::JENKINS);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function AWS_CODEBUILD(): CiType
    {
        return self::from(self::AWS_CODEBUILD);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function CIRCLE(): CiType
    {
        return self::from(self::CIRCLE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function CODESHIP(): CiType
    {
        return self::from(self::CODESHIP);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function DRONE(): CiType
    {
        return self::from(self::DRONE);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function BAMBOO(): CiType
    {
        return self::from(self::BAMBOO);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function BUDDY(): CiType
    {
        return self::from(self::BUDDY);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function CONTINUOUSPHP(): CiType
    {
        return self::from(self::CONTINUOUSPHP);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function GITLAB(): CiType
    {
        return self::from(self::GITLAB);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function AZURE_PIPELINES(): CiType
    {
        return self::from(self::AZURE_PIPELINES);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function APPVEYOR(): CiType
    {
        return self::from(self::APPVEYOR);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function SOURCEHUT(): CiType
    {
        return self::from(self::SOURCEHUT);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function BITBUCKET_PIPELINES(): CiType
    {
        return self::from(self::BITBUCKET_PIPELINES);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function GITHUB_ACTIONS(): CiType
    {
        return self::from(self::GITHUB_ACTIONS);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function TEAMCITY(): CiType
    {
        return self::from(self::TEAMCITY);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function WERCKER(): CiType
    {
        return self::from(self::WERCKER);
    }

    /**
     * @return \Codeception\Module\Percy\Config\CiEnvironment\CiType
     */
    public static function UNKNOWN(): CiType
    {
        return self::from(self::UNKNOWN);
    }
}
