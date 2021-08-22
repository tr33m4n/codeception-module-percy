<?php

declare(strict_types=1);

namespace Codeception\Module\Percy\Exchange\Action;

use Codeception\Module\Percy\Exchange\ClientFactory;

class CreateBuild
{
    public function __construct(

    ) {

    }

    public function execute()
    {
        return ClientFactory::create()
            ->post(
                'build',
                [
                    'type' => 'builds',
                    'attributes' => [
                        'target-branch' => '',
                        'target-commit-sha' => '',
                        'commit-sha' => '',
                        'commit-committed-at' => '',
                        'commit-author-name' => '',
                        'commit-author-email' => '',
                        'commit-committer-name' => '',
                        'commit-committer-email' => '',
                        'commit-message' => '',
                        'pull-request-number' => '',
                        'parallel-nonce' => '',
                        'parallel-total-shards' => '',
                        'partial' => ''
                    ],
                    'relationships' => [
                        'resources' => []
                    ]
                ]
            );
    }
}
