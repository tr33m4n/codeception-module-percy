<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action\Response;

class CreateBuildAttribute
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * CreateBuildAttribute constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * From key and value
     *
     * @param string $key
     * @param mixed  $value
     * @return \Codeception\Module\Percy\Exchange\Action\Response\CreateBuildAttribute
     */
    public static function from(string $key, $value): CreateBuildAttribute
    {
        $createBuildAttribute = new self();
        $createBuildAttribute->key = $key;
        $createBuildAttribute->value = $value;

        return $createBuildAttribute;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
