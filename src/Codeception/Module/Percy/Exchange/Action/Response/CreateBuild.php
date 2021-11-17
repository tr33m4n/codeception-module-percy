<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Response;

class CreateBuild
{
    public const ID_FIELD = 'id';

    public const ATTRIBUTES_FIELD = 'attributes';

    /**
     * @var int
     */
    private $id;

    /**
     * @var \Codeception\Module\Percy\Exchange\Action\Response\CreateBuildAttribute[]
     */
    private $attributes;

    /**
     * CreateBuild constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Create from response array
     *
     * @param array<string, mixed> $response
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateBuild
     */
    public static function create(array $response): CreateBuild
    {
        $createBuild = array_reduce(
            array_keys($response[self::ATTRIBUTES_FIELD] ?? []),
            static function (CreateBuild $createBuild, string $attributeKey) use ($response): CreateBuild {
                return $createBuild = $createBuild->withAttribute(
                    $attributeKey,
                    $response[self::ATTRIBUTES_FIELD][$attributeKey]
                );
            },
            new self()
        );

        $createBuild->id = $response[self::ID_FIELD];

        return $createBuild;
    }

    /**
     * With attribute
     *
     * @param string $key
     * @param mixed       $value
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateBuild
     */
    public function withAttribute(string $key, $value): CreateBuild
    {
        $createBuild = clone $this;
        $createBuild->attributes[$key] = CreateBuildAttribute::from($key, $value);

        return $createBuild;
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get attribute
     *
     * @param string $key
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateBuildAttribute|null
     */
    public function getAttribute(string $key): ?CreateBuildAttribute
    {
        return $this->attributes[$key] ?? null;
    }
}
