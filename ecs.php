<?php

use SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/tests',
    ]);
    $parameters->set(Option::SKIP, [
        UnusedParameterSniff::class => [
            __DIR__ . '/app/AutoConvert/Formatters/Exceptions/Decline'
        ]
    ]);
    $containerConfigurator->import(__DIR__ . '/vendor/jumptwentyfour/laravel-coding-standards/ecs.php');
};
