<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

use Codeception\Module\Percy\Exception\AbstractException;

class Output
{
    /**
     * Output debug to CLI
     *
     * @param string|\Codeception\Module\Percy\Exception\AbstractException $message
     * @param array<string, string>                                        $context
     */
    public function debug($message, array $context = [], ?string $namespace = Definitions::NAMESPACE): void
    {
        if ($message instanceof AbstractException) {
            $message = str_replace(sprintf('%s: ', Definitions::NAMESPACE), '', $message->getMessage());
        }

        codecept_debug($namespace ? sprintf('[%s] %s', $namespace, $message) : $message);

        foreach ($context as $contextKey => $message) {
            $this->debug($message, [], sprintf('%s->%s', Definitions::NAMESPACE, $contextKey));
        }
    }
}
