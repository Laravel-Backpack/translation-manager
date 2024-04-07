<?php

declare (strict_types = 1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\Expression\InlineIfToExplicitIfRector;
use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\Switch_\SingularSwitchToIfRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/config',
        __DIR__.'/resources',
        __DIR__.'/routes',
        __DIR__.'/src',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        privatization: true
    )
    ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
        RemoveUselessReturnTagRector::class,
        LongArrayToShortArrayRector::class,
    ])
    ->withSkip([
        CompactToVariablesRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        ExplicitBoolCompareRector::class,
        CountArrayToEmptyArrayComparisonRector::class,
        SimplifyIfReturnBoolRector::class,
        CombinedAssignRector::class,
        InlineIfToExplicitIfRector::class,
        SingularSwitchToIfRector::class,
        DisallowedEmptyRuleFixerRector::class,
        RemoveDuplicatedCaseInSwitchRector::class,
        CallableThisArrayToAnonymousFunctionRector::class,
        FirstClassCallableRector::class,
    ]);
