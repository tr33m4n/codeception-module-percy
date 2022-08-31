<?php

declare(strict_types=1);

namespace Codeception\Module\Percy;

class Debug
{
    /**
     * Output debug to CLI
     *
     * @param array<string, string> $context
     */
    public function out(string $message, array $context = [], ?string $namespace = Definitions::NAMESPACE): void
    {
        codecept_debug($namespace ? sprintf('[%s] %s', $namespace, $message) : $message);

        foreach ($context as $contextKey => $message) {
            $this->out($message, [], sprintf('%s->%s', Definitions::NAMESPACE, $contextKey));
        }
    }
}
