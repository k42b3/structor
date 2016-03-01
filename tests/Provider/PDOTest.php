<?php
/*
 * This file is part of the Structor package.
 *
 * (c) Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE file 
 * that was distributed with this source code.
 */

namespace Structor\Provider;

use Structor\ProviderTestCase;
use Structor\Reference;

/**
 * PDOTest
 *
 * @author Christoph Kappestein <christoph.kappestein@gmail.com>
 */
class PDOTest extends ProviderTestCase
{
    protected function getDefinition()
    {
        $provider = new PDO\Factory($this->connection->getWrappedConnection());

        return [
            'totalEntries' => 2,
            'entries' => $provider->newCollection('SELECT id, authorId, title, createDate FROM news ORDER BY id ASC', [], [
                'id' => 'id',
                'title' => 'title',
                'author' => $provider->newEntity('SELECT id, name, uri FROM author WHERE id = :id', ['id' => new Reference('authorId')], [
                    'displayName' => 'name',
                    'uri' => 'uri',
                ]),
            ])
        ];
    }
}
