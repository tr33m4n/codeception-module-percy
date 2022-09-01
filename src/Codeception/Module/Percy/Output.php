<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

class Output
{
    /**
     * Output debug to CLI
     *
     * @param array<string, string> $context
     */
    public function debug(string $message, array $context = [], ?string $namespace = Definitions::NAMESPACE): void
    {
        codecept_debug($namespace ? sprintf('[%s] %s', $namespace, $message) : $message);

        foreach ($context as $contextKey => $message) {
            $this->debug($message, [], sprintf('%s->%s', Definitions::NAMESPACE, $contextKey));
        }
    }
}
