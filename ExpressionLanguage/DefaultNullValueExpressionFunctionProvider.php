<?php

declare(strict_types=1);

namespace Chang\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class DefaultNullValueExpressionFunctionProvider implements ExpressionFunctionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new ExpressionFunction('defaultNull', function ($result = null) {
                return sprintf('(null !== %1$s) ? %2$s : %1$s', $result, null);
            }, function ($arguments, $result = null) {
                return $result;
            }),
        ];
    }
}
