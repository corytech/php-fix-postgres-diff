<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Corytech\FixPostgresDiff\EventListener\Doctrine\FixPostgreSQLDefaultSchemaListener;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
            ->autoconfigure()
            ->autowire()
        ->set(FixPostgreSQLDefaultSchemaListener::class, FixPostgreSQLDefaultSchemaListener::class)
    ;
};
