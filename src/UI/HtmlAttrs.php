<?php

declare(strict_types=1);

namespace Chang\UI;

/**
 * @credit https://gist.github.com/mcaskill/0177f151e39b94ee2629f06d72c4b65b
 */
class HtmlAttrs
{
    /**
     * Convert AttrKey to attr-key
     *
     * @param $key
     *
     * @return string
     */
    static private function getKey($key)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $key));
    }

    /**
     * @param array $attr
     * @param callable|null $callback
     *
     * @return string
     */
    static public function build(array $attr, callable $callback = null): string
    {
        if (!count($attr)) {
            return '';
        }

        $html = array_map(function ($val, $key) use ($callback) {
            if (is_bool($val)) {
                return $val ? self::getKey($key) : '';
            }

            if (isset($val)) {
                if ($val instanceof \Closure) {
                    $val = $val();
                } elseif ($val instanceof \JsonSerializable) {
                    $val = \json_encode($val->jsonSerialize(), (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                } elseif (is_callable([$val, 'toArray'])) {
                    $val = $val->toArray();
                } elseif (is_callable([$val, '__toString'])) {
                    $val = strval($val);
                }

                if (is_array($val)) {
                    $val = \json_encode($val);
                }

                if (is_callable($callback)) {
                    $val = call_user_func($callback, $val);
                } else {
                    $val = htmlspecialchars($val, ENT_QUOTES);
                }

                if (is_string($val)) {
                    return sprintf('%1$s="%2$s"', self::getKey($key), $val);
                }
            }

            return self::getKey($key);
        }, $attr, array_keys($attr));

        return implode(' ', $html);
    }
}
