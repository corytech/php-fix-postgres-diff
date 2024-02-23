<?php

declare(strict_types=1);

namespace Corytech\FixPostgresDiff\EventListener\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

/**
 * listener to remove `CREATE SCHEMA public` from migration diff.
 *
 * @see https://github.com/doctrine/dbal/issues/1110
 */
#[AsDoctrineListener(ToolEvents::postGenerateSchema)]
final class FixPostgreSQLDefaultSchemaListener
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args
            ->getEntityManager()
            ->getConnection()
            ->createSchemaManager();

        if ($schemaManager::class !== PostgreSQLSchemaManager::class) {
            return;
        }

        $schema = $args->getSchema();

        foreach ($schemaManager->listSchemaNames() as $namespace) {
            if (!$schema->hasNamespace($namespace)) {
                $schema->createNamespace($namespace);
            }
        }
    }
}
