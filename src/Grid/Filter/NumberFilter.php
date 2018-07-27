<?php

declare(strict_types=1);

namespace Chang\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

class NumberFilter implements FilterInterface
{
    public const NAME = 'number';

    public const TYPE_EQUAL = 'equal';
    public const TYPE_NOT_EQUAL = 'not_equal';
    public const TYPE_EMPTY = 'empty';
    public const TYPE_NOT_EMPTY = 'not_empty';
    public const TYPE_IN = 'in';
    public const TYPE_NOT_IN = 'not_in';
    public const TYPE_BETWEEN = 'between';
    public const TYPE_LESS_THAN = 'less_than';
    public const TYPE_LESS_THAN_OR_EQUAL = 'less_than_or_equal';
    public const TYPE_GREATER_THAN= 'greater_than';
    public const TYPE_GREATER_THAN_OR_EQUAL = 'greater_than_or_equal';

    /**
     * {@inheritdoc}
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options): void
    {
        $expressionBuilder = $dataSource->getExpressionBuilder();

        if (is_array($data) && !isset($data['type'])) {
            $data['type'] = isset($options['type']) ? $options['type'] : self::TYPE_EQUAL;
        }

        if (!is_array($data)) {
            $data = ['type' => self::TYPE_EQUAL, 'value' => $data];
        }

        $fields = array_key_exists('fields', $options) ? $options['fields'] : [$name];

        $type = $data['type'];
        $value = array_key_exists('value', $data) ? preg_replace('/\s+/', '', $data['value']) : null;

        if (!in_array($type, [self::TYPE_NOT_EMPTY, self::TYPE_EMPTY], true) && (null === $value || '' === $value)) {
            return;
        }

        $patterns = array_map(function($item) {
            return preg_quote($item);
        }, ['>', '>=', '=>', '<', '<=', '=<', '=']);

        if (preg_match(sprintf('/^(%s)([0-9]+)$/', implode('|', $patterns)), $value, $match)) {
            $value = intval($match[2]);

            switch ($match[1]) {
                case '=':
                    $type = self::TYPE_EQUAL;
                    break;
                case '<':
                    $type = self::TYPE_LESS_THAN;
                    break;
                case '<=':
                case '=<':
                    $type = self::TYPE_LESS_THAN_OR_EQUAL;
                    break;
                case '>':
                    $type = self::TYPE_GREATER_THAN;
                    break;
                case '>=':
                case '=>':
                    $type = self::TYPE_GREATER_THAN_OR_EQUAL;
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Could not get an operation "%s"!', $match[2]));
            }
        } elseif (preg_match('/^([0-9]+)(\-)([0-9]+)$/', $value, $match)) {
            if ($match[1] === $match[3]) {
                $value = $match[1];
            } else {
                $value = [$match[1], $match[3]];
                $type = self::TYPE_BETWEEN;
            }
        }

        if (1 === count($fields)) {
            $dataSource->restrict($this->getExpression($expressionBuilder, $type, current($fields), $value));

            return;
        }

        $expressions = [];
        foreach ($fields as $field) {
            $expressions[] = $this->getExpression($expressionBuilder, $type, $field, $value);
        }

        if (self::TYPE_NOT_EQUAL === $type) {
            $dataSource->restrict($expressionBuilder->andX(...$expressions));

            return;
        }

        $dataSource->restrict($expressionBuilder->orX(...$expressions));
    }

    /**
     * @param ExpressionBuilderInterface $expressionBuilder
     * @param string $type
     * @param string $field
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    private function getExpression(
        ExpressionBuilderInterface $expressionBuilder,
        string $type,
        string $field,
        $value
    ) {
        switch ($type) {
            case self::TYPE_EQUAL:
                return $expressionBuilder->equals($field, $value);
            case self::TYPE_NOT_EQUAL:
                return $expressionBuilder->notEquals($field, $value);
            case self::TYPE_EMPTY:
                return $expressionBuilder->isNull($field);
            case self::TYPE_NOT_EMPTY:
                return $expressionBuilder->isNotNull($field);
            case self::TYPE_IN:
                return $expressionBuilder->in($field, $value);
            case self::TYPE_NOT_IN:
                return $expressionBuilder->notIn($field, $value);
            case self::TYPE_LESS_THAN:
                return $expressionBuilder->lessThan($field, $value);
            case self::TYPE_LESS_THAN_OR_EQUAL:
                return $expressionBuilder->lessThanOrEqual($field, $value);
            case self::TYPE_GREATER_THAN:
                return $expressionBuilder->greaterThan($field, $value);
            case self::TYPE_GREATER_THAN_OR_EQUAL:
                return $expressionBuilder->greaterThanOrEqual($field, $value);
            case self::TYPE_BETWEEN:
                return $expressionBuilder->andX(
                    $expressionBuilder->greaterThanOrEqual($field, $value[0]),
                    $expressionBuilder->lessThanOrEqual($field, $value[1])
                );
            default:
                throw new \InvalidArgumentException(sprintf('Could not get an expression for type "%s"!', $type));
        }
    }
}
