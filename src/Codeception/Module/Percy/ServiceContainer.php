<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy;
use Codeception\Module\WebDriver;
use CzProject\GitPhp\Git;
use OndraM\CiDetector\Env as EnvHelper;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiType;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiType\GitHub\EventDataProvider;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiTypePool;
use tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiTypeResolver;
use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProvider;
use tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProviderInterface;
use tr33m4n\CodeceptionModulePercyEnvironment\GitEnvironment;
use tr33m4n\CodeceptionModulePercyEnvironment\PercyEnvironment;

final class ServiceContainer
{
    private ServiceFactory $serviceFactory;

    private WebDriver $webDriver;

    /**
     * @var array<string, mixed>
     */
    private array $moduleConfig;

    /**
     * @var array<string, mixed>
     */
    private array $services = [];

    /**
     * ServiceContainer constructor.
     *
     * @param \Codeception\Module\WebDriver $webDriver
     * @param array<string, mixed>          $moduleConfig
     */
    public function __construct(
        WebDriver $webDriver,
        array $moduleConfig = []
    ) {
        $this->serviceFactory = new ServiceFactory();
        $this->webDriver = $webDriver;
        $this->moduleConfig = $moduleConfig;
    }

    /**
     * Get "env" helper
     *
     * @return \OndraM\CiDetector\Env
     */
    public function getEnvHelper(): EnvHelper
    {
        return $this->resolveService(EnvHelper::class);
    }

    /**
     * Get event data provider
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiType\GitHub\EventDataProvider
     */
    public function getEventDataProvider(): EventDataProvider
    {
        return $this->resolveService(EventDataProvider::class);
    }

    /**
     * Get Git API
     *
     * @return \CzProject\GitPhp\Git
     */
    public function getGitApi(): Git
    {
        return $this->resolveService(Git::class);
    }

    /**
     * Get instantiated CI types
     *
     * @return array<string, \tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiType\CiTypeInterface>
     */
    public function getCiTypes(): array
    {
        $ciTypes = [
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
        ];

        return array_map(function (string $ciTypeClass) {
            if ($ciTypeClass === CiType\GitHubActions::class) {
                return $this->resolveService($ciTypeClass, [$this->getEventDataProvider(), $this->getEnvHelper()]);
            }

            return $this->resolveService($ciTypeClass, [$this->getEnvHelper()]);
        }, $ciTypes);
    }

    /**
     * Get CI type pool
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiTypePool
     */
    public function getCiTypePool(): CiTypePool
    {
        return $this->resolveService(CiTypePool::class, [$this->getCiTypes()]);
    }

    /**
     * Get CI type resolver
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment\CiTypeResolver
     */
    public function getCiTypeResolver(): CiTypeResolver
    {
        return $this->resolveService(CiTypeResolver::class, [$this->getCiTypePool(), $this->getEnvHelper()]);
    }

    /**
     * Get CI environment
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\CiEnvironment
     */
    public function getCiEnvironment(): CiEnvironment
    {
        return $this->resolveService(CiEnvironment::class, [$this->getCiTypeResolver()]);
    }

    /**
     * Get Git environment
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\GitEnvironment
     */
    public function getGitEnvironment(): GitEnvironment
    {
        return $this->resolveService(GitEnvironment::class, [$this->getGitApi(), codecept_root_dir()]);
    }

    /**
     * Get Percy environment
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\PercyEnvironment
     */
    public function getPercyEnvironment(): PercyEnvironment
    {
        return $this->resolveService(PercyEnvironment::class);
    }

    /**
     * Get environment provider
     *
     * @return \tr33m4n\CodeceptionModulePercyEnvironment\EnvironmentProviderInterface
     */
    public function getEnvironmentProvider(): EnvironmentProviderInterface
    {
        return $this->resolveService(
            EnvironmentProvider::class,
            [
                $this->getCiEnvironment(),
                $this->getGitEnvironment(),
                $this->getPercyEnvironment(),
                $this->webDriver,
                Percy::PACKAGE_NAME
            ]
        );
    }

    /**
     * Get config management
     *
     * @return \Codeception\Module\Percy\ConfigManagement
     */
    public function getConfigManagement(): ConfigManagement
    {
        return $this->resolveService(ConfigManagement::class, [$this->moduleConfig]);
    }

    public function getRequestManagement(): RequestManagement
    {
        return $this->resolveService(RequestManagement::class);
    }

    /**
     * Resolve service
     *
     * @param class-string $className
     * @param mixed[]      $parameters
     * @return mixed|object
     */
    private function resolveService(string $className, array $parameters = [])
    {
        if (array_key_exists($className, $this->services)) {
            return $this->services[$className];
        }

        return $this->services[$className] = $this->serviceFactory->create($className, $parameters);
    }
}
