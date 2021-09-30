<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::ARRAY);
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::COMMENTS);
    $containerConfigurator->import(SetList::CONTROL_STRUCTURES);
    $containerConfigurator->import(SetList::DOCBLOCK);
    $containerConfigurator->import(SetList::DOCTRINE_ANNOTATIONS);
    $containerConfigurator->import(SetList::NAMESPACES);
    $containerConfigurator->import(SetList::PHP_CS_FIXER);
    $containerConfigurator->import(SetList::PHPUNIT);
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::SPACES);
    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::SYMFONY);
    $containerConfigurator->import(SetList::SYMPLIFY);

    $services = $containerConfigurator->services();
    $services->set(\PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer::class)
        ->call('configure', [[
            'null_adjustment' => 'always_last', 'sort_algorithm' => 'none'
        ]]);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SKIP, [
        \PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer::class,
    ]);
};