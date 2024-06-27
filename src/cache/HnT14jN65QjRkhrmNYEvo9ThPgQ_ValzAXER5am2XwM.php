<?php $GLOBALS['__jpv_dotWithArrayPrototype'] = function ($base) {
    $arrayPrototype = function ($base, $key) {
        if ($key === 'length') {
            return count($base);
        }
        if ($key === 'forEach') {
            return function ($callback, $userData = null) use (&$base) {
                return array_walk($base, $callback, $userData);
            };
        }
        if ($key === 'map') {
            return function ($callback) use (&$base) {
                return array_map($callback, $base);
            };
        }
        if ($key === 'filter') {
            return function ($callback, $flag = 0) use ($base) {
                return func_num_args() === 1 ? array_filter($base, $callback) : array_filter($base, $callback, $flag);
            };
        }
        if ($key === 'pop') {
            return function () use (&$base) {
                return array_pop($base);
            };
        }
        if ($key === 'shift') {
            return function () use (&$base) {
                return array_shift($base);
            };
        }
        if ($key === 'push') {
            return function ($item) use (&$base) {
                return array_push($base, $item);
            };
        }
        if ($key === 'unshift') {
            return function ($item) use (&$base) {
                return array_unshift($base, $item);
            };
        }
        if ($key === 'indexOf') {
            return function ($item) use (&$base) {
                $search = array_search($item, $base);

                return $search === false ? -1 : $search;
            };
        }
        if ($key === 'slice') {
            return function ($offset, $length = null, $preserveKeys = false) use (&$base) {
                return array_slice($base, $offset, $length, $preserveKeys);
            };
        }
        if ($key === 'splice') {
            return function ($offset, $length = null, $replacements = array()) use (&$base) {
                return array_splice($base, $offset, $length, $replacements);
            };
        }
        if ($key === 'reverse') {
            return function () use (&$base) {
                return array_reverse($base);
            };
        }
        if ($key === 'reduce') {
            return function ($callback, $initial = null) use (&$base) {
                return array_reduce($base, $callback, $initial);
            };
        }
        if ($key === 'join') {
            return function ($glue) use (&$base) {
                return implode($glue, $base);
            };
        }
        if ($key === 'sort') {
            return function ($callback = null) use (&$base) {
                return $callback ? usort($base, $callback) : sort($base);
            };
        }

        return null;
    };

    $getFromArray = function ($base, $key) use ($arrayPrototype) {
        return isset($base[$key])
            ? $base[$key]
            : $arrayPrototype($base, $key);
    };

    $getCallable = function ($base, $key) use ($getFromArray) {
        if (is_callable(array($base, $key))) {
            return new class(array($base, $key)) extends \ArrayObject
            {
                public function getValue()
                {
                    if ($this->isArrayAccessible()) {
                        return $this[0][$this[1]];
                    }

                    return $this[0]->{$this[1]} ?? null;
                }

                public function setValue($value)
                {
                    if ($this->isArrayAccessible()) {
                        $this[0][$this[1]] = $value;

                        return;
                    }

                    $this[0]->{$this[1]} = $value;
                }

                public function getCallable()
                {
                    return $this->getArrayCopy();
                }

                public function __isset($name)
                {
                    $value = $this->getValue();

                    if ((is_array($value) || $value instanceof ArrayAccess) && isset($value[$name])) {
                        return true;
                    }

                    return is_object($value) && isset($value->$name);
                }

                public function __get($name)
                {
                    return new self(array($this->getValue(), $name));
                }

                public function __set($name, $value)
                {
                    $value = $this->getValue();

                    if (is_array($value)) {
                        $value[$name] = $value;
                        $this->setValue($value);

                        return;
                    }

                    $value->$name = $value;
                }

                public function __toString()
                {
                    return (string) $this->getValue();
                }

                public function __toBoolean()
                {
                    $value = $this->getValue();

                    if (is_object($value) && method_exists($value, '__toBoolean')) {
                        return $value->__toBoolean();
                    }

                    return !!$value;
                }

                public function __invoke(...$arguments)
                {
                    return call_user_func_array($this->getCallable(), $arguments);
                }

                private function isArrayAccessible()
                {
                    return is_array($this[0]) || $this[0] instanceof ArrayAccess && !isset($this[0]->{$this[1]});
                }
            };
        }
        if ($base instanceof \ArrayAccess) {
            return $getFromArray($base, $key);
        }
    };

    $getRegExp = function ($value) {
        return is_object($value) && isset($value->isRegularExpression) && $value->isRegularExpression ? $value->regExp . $value->flags : null;
    };

    $fallbackDot = function ($base, $key) use ($getCallable, $getRegExp) {
        if (is_string($base)) {
            if (preg_match('/^[-+]?\d+$/', strval($key))) {
                return substr($base, intval($key), 1);
            }
            if ($key === 'length') {
                return strlen($base);
            }
            if ($key === 'substr' || $key === 'slice') {
                return function ($start, $length = null) use ($base) {
                    return func_num_args() === 1 ? substr($base, $start) : substr($base, $start, $length);
                };
            }
            if ($key === 'charAt') {
                return function ($pos) use ($base) {
                    return substr($base, $pos, 1);
                };
            }
            if ($key === 'indexOf') {
                return function ($needle) use ($base) {
                    $pos = strpos($base, $needle);

                    return $pos === false ? -1 : $pos;
                };
            }
            if ($key === 'toUpperCase') {
                return function () use ($base) {
                    return strtoupper($base);
                };
            }
            if ($key === 'toLowerCase') {
                return function () use ($base) {
                    return strtolower($base);
                };
            }
            if ($key === 'match') {
                return function ($search) use ($base, $getRegExp) {
                    $regExp = $getRegExp($search);
                    $search = $regExp ? $regExp : (is_string($search) ? '/' . preg_quote($search, '/') . '/' : strval($search));

                    return preg_match($search, $base);
                };
            }
            if ($key === 'split') {
                return function ($delimiter) use ($base, $getRegExp) {
                    if ($regExp = $getRegExp($delimiter)) {
                        return preg_split($regExp, $base);
                    }

                    return explode($delimiter, $base);
                };
            }
            if ($key === 'replace') {
                return function ($from, $to) use ($base, $getRegExp) {
                    if ($regExp = $getRegExp($from)) {
                        return preg_replace($regExp, $to, $base);
                    }

                    return str_replace($from, $to, $base);
                };
            }
        }

        return $getCallable($base, $key);
    };

    foreach (array_slice(func_get_args(), 1) as $key) {
        $base = is_array($base)
            ? $getFromArray($base, $key)
            : (is_object($base)
                ? (isset($base->$key)
                    ? $base->$key
                    : (method_exists($base, $method = "get" . ucfirst($key))
                        ? $base->$method()
                        : (method_exists($base, $key)
                            ? array($base, $key)
                            : $getCallable($base, $key)
                        )
                    )
                )
                : $fallbackDot($base, $key)
            );
    }

    return $base;
};;
$GLOBALS['__jpv_dotWithArrayPrototype_with_ref'] = function (&$base) {
    $arrayPrototype = function (&$base, $key) {
        if ($key === 'length') {
            return count($base);
        }
        if ($key === 'forEach') {
            return function ($callback, $userData = null) use (&$base) {
                return array_walk($base, $callback, $userData);
            };
        }
        if ($key === 'map') {
            return function ($callback) use (&$base) {
                return array_map($callback, $base);
            };
        }
        if ($key === 'filter') {
            return function ($callback, $flag = 0) use ($base) {
                return func_num_args() === 1 ? array_filter($base, $callback) : array_filter($base, $callback, $flag);
            };
        }
        if ($key === 'pop') {
            return function () use (&$base) {
                return array_pop($base);
            };
        }
        if ($key === 'shift') {
            return function () use (&$base) {
                return array_shift($base);
            };
        }
        if ($key === 'push') {
            return function ($item) use (&$base) {
                return array_push($base, $item);
            };
        }
        if ($key === 'unshift') {
            return function ($item) use (&$base) {
                return array_unshift($base, $item);
            };
        }
        if ($key === 'indexOf') {
            return function ($item) use (&$base) {
                $search = array_search($item, $base);

                return $search === false ? -1 : $search;
            };
        }
        if ($key === 'slice') {
            return function ($offset, $length = null, $preserveKeys = false) use (&$base) {
                return array_slice($base, $offset, $length, $preserveKeys);
            };
        }
        if ($key === 'splice') {
            return function ($offset, $length = null, $replacements = array()) use (&$base) {
                return array_splice($base, $offset, $length, $replacements);
            };
        }
        if ($key === 'reverse') {
            return function () use (&$base) {
                return array_reverse($base);
            };
        }
        if ($key === 'reduce') {
            return function ($callback, $initial = null) use (&$base) {
                return array_reduce($base, $callback, $initial);
            };
        }
        if ($key === 'join') {
            return function ($glue) use (&$base) {
                return implode($glue, $base);
            };
        }
        if ($key === 'sort') {
            return function ($callback = null) use (&$base) {
                return $callback ? usort($base, $callback) : sort($base);
            };
        }

        return null;
    };

    $getFromArray = function (&$base, $key) use ($arrayPrototype) {
        return isset($base[$key])
            ? $base[$key]
            : $arrayPrototype($base, $key);
    };

    $getCallable = function (&$base, $key) use ($getFromArray) {
        if (is_callable(array($base, $key))) {
            return new class(array($base, $key)) extends \ArrayObject
            {
                public function getValue()
                {
                    if ($this->isArrayAccessible()) {
                        return $this[0][$this[1]];
                    }

                    return $this[0]->{$this[1]} ?? null;
                }

                public function setValue($value)
                {
                    if ($this->isArrayAccessible()) {
                        $this[0][$this[1]] = $value;

                        return;
                    }

                    $this[0]->{$this[1]} = $value;
                }

                public function getCallable()
                {
                    return $this->getArrayCopy();
                }

                public function __isset($name)
                {
                    $value = $this->getValue();

                    if ((is_array($value) || $value instanceof ArrayAccess) && isset($value[$name])) {
                        return true;
                    }

                    return is_object($value) && isset($value->$name);
                }

                public function __get($name)
                {
                    return new self(array($this->getValue(), $name));
                }

                public function __set($name, $value)
                {
                    $value = $this->getValue();

                    if (is_array($value)) {
                        $value[$name] = $value;
                        $this->setValue($value);

                        return;
                    }

                    $value->$name = $value;
                }

                public function __toString()
                {
                    return (string) $this->getValue();
                }

                public function __toBoolean()
                {
                    $value = $this->getValue();

                    if (is_object($value) && method_exists($value, '__toBoolean')) {
                        return $value->__toBoolean();
                    }

                    return !!$value;
                }

                public function __invoke(...$arguments)
                {
                    return call_user_func_array($this->getCallable(), $arguments);
                }

                private function isArrayAccessible()
                {
                    return is_array($this[0]) || $this[0] instanceof ArrayAccess && !isset($this[0]->{$this[1]});
                }
            };
        }
        if ($base instanceof \ArrayAccess) {
            return $getFromArray($base, $key);
        }
    };

    $getRegExp = function ($value) {
        return is_object($value) && isset($value->isRegularExpression) && $value->isRegularExpression ? $value->regExp . $value->flags : null;
    };

    $fallbackDot = function (&$base, $key) use ($getCallable, $getRegExp) {
        if (is_string($base)) {
            if (preg_match('/^[-+]?\d+$/', strval($key))) {
                return substr($base, intval($key), 1);
            }
            if ($key === 'length') {
                return strlen($base);
            }
            if ($key === 'substr' || $key === 'slice') {
                return function ($start, $length = null) use ($base) {
                    return func_num_args() === 1 ? substr($base, $start) : substr($base, $start, $length);
                };
            }
            if ($key === 'charAt') {
                return function ($pos) use ($base) {
                    return substr($base, $pos, 1);
                };
            }
            if ($key === 'indexOf') {
                return function ($needle) use ($base) {
                    $pos = strpos($base, $needle);

                    return $pos === false ? -1 : $pos;
                };
            }
            if ($key === 'toUpperCase') {
                return function () use ($base) {
                    return strtoupper($base);
                };
            }
            if ($key === 'toLowerCase') {
                return function () use ($base) {
                    return strtolower($base);
                };
            }
            if ($key === 'match') {
                return function ($search) use ($base, $getRegExp) {
                    $regExp = $getRegExp($search);
                    $search = $regExp ? $regExp : (is_string($search) ? '/' . preg_quote($search, '/') . '/' : strval($search));

                    return preg_match($search, $base);
                };
            }
            if ($key === 'split') {
                return function ($delimiter) use ($base, $getRegExp) {
                    if ($regExp = $getRegExp($delimiter)) {
                        return preg_split($regExp, $base);
                    }

                    return explode($delimiter, $base);
                };
            }
            if ($key === 'replace') {
                return function ($from, $to) use ($base, $getRegExp) {
                    if ($regExp = $getRegExp($from)) {
                        return preg_replace($regExp, $to, $base);
                    }

                    return str_replace($from, $to, $base);
                };
            }
        }

        return $getCallable($base, $key);
    };

    $crawler = &$base;
    $result = $base;
    foreach (array_slice(func_get_args(), 1) as $key) {
        $result = is_array($crawler)
            ? $getFromArray($crawler, $key)
            : (is_object($crawler)
                ? (isset($crawler->$key)
                    ? $crawler->$key
                    : (method_exists($crawler, $method = "get" . ucfirst($key))
                        ? $crawler->$method()
                        : (method_exists($crawler, $key)
                            ? array($crawler, $key)
                            : $getCallable($crawler, $key)
                        )
                    )
                )
                : $fallbackDot($crawler, $key)
            );
        $crawler = &$result;
    }

    return $result;
};;
$GLOBALS['__jpv_and'] = function ($base) {
    foreach (array_slice(func_get_args(), 1) as $value) {
        if ($base) {
            $base = $value();
        }
    }

    return $base;
};
$GLOBALS['__jpv_and_with_ref'] = $GLOBALS['__jpv_and'];
$pugModule = [
  'Phug\\Formatter\\Format\\HtmlFormat::dependencies_storage' => 'pugModule',
  'Phug\\Formatter\\Format\\HtmlFormat::helper_prefix' => 'Phug\\Formatter\\Format\\HtmlFormat::',
  'Phug\\Formatter\\Format\\HtmlFormat::get_helper' => function ($name) use (&$pugModule) {
    $dependenciesStorage = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::dependencies_storage'];
    $prefix = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::helper_prefix'];
    $format = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::dependencies_storage'];

                            if (!isset($$dependenciesStorage)) {
                                return $format->getHelper($name);
                            }

                            $storage = $$dependenciesStorage;

                            if (!isset($storage[$prefix.$name]) &&
                                !(is_array($storage) && array_key_exists($prefix.$name, $storage))
                            ) {
                                throw new \Exception(
                                    var_export($name, true).
                                    ' dependency not found in the namespace: '.
                                    var_export($prefix, true)
                                );
                            }

                            return $storage[$prefix.$name];
                        },
  'Phug\\Formatter\\Format\\HtmlFormat::pattern' => function ($pattern) use (&$pugModule) {

                    $args = func_get_args();
                    $function = 'sprintf';
                    if (is_callable($pattern)) {
                        $function = $pattern;
                        $args = array_slice($args, 1);
                    }

                    return call_user_func_array($function, $args);
                },
  'Phug\\Formatter\\Format\\HtmlFormat::patterns.html_text_escape' => 'htmlspecialchars',
  'Phug\\Formatter\\Format\\HtmlFormat::pattern.html_text_escape' => function () use (&$pugModule) {
    $proceed = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::pattern'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::patterns.html_text_escape'];

                    $args = func_get_args();
                    array_unshift($args, $pattern);

                    return call_user_func_array($proceed, $args);
                },
  'Phug\\Formatter\\Format\\HtmlFormat::available_attribute_assignments' => array (
  0 => 'class',
  1 => 'style',
),
  'Phug\\Formatter\\Format\\HtmlFormat::patterns.attribute_pattern' => ' %s="%s"',
  'Phug\\Formatter\\Format\\HtmlFormat::pattern.attribute_pattern' => function () use (&$pugModule) {
    $proceed = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::pattern'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::patterns.attribute_pattern'];

                    $args = func_get_args();
                    array_unshift($args, $pattern);

                    return call_user_func_array($proceed, $args);
                },
  'Phug\\Formatter\\Format\\HtmlFormat::patterns.boolean_attribute_pattern' => ' %s',
  'Phug\\Formatter\\Format\\HtmlFormat::pattern.boolean_attribute_pattern' => function () use (&$pugModule) {
    $proceed = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::pattern'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::patterns.boolean_attribute_pattern'];

                    $args = func_get_args();
                    array_unshift($args, $pattern);

                    return call_user_func_array($proceed, $args);
                },
  'Phug\\Formatter\\Format\\HtmlFormat::attribute_assignments' => function (&$attributes, $name, $value) use (&$pugModule) {
    $availableAssignments = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::available_attribute_assignments'];
    $getHelper = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::get_helper'];

                    if (!in_array($name, $availableAssignments, true)) {
                        return $value;
                    }

                    $helper = $getHelper($name.'_attribute_assignment');

                    return $helper($attributes, $value);
                },
  'Phug\\Formatter\\Format\\HtmlFormat::attribute_assignment' => function (&$attributes, $name, $value) use (&$pugModule) {
    $attributeAssignments = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attribute_assignments'];

                    if (isset($name) && $name !== '') {
                        $result = $attributeAssignments($attributes, $name, $value);
                        if ($result !== null && $result !== false && ($result !== '' || $name !== 'class')) {
                            $attributes[$name] = $result;
                        }
                    }
                },
  'Phug\\Formatter\\Format\\HtmlFormat::merge_attributes' => function () use (&$pugModule) {
    $attributeAssignment = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attribute_assignment'];

                    $attributes = [];
                    foreach (array_filter(func_get_args(), 'is_array') as $input) {
                        foreach ($input as $name => $value) {
                            $attributeAssignment($attributes, $name, $value);
                        }
                    }

                    return $attributes;
                },
  'Phug\\Formatter\\Format\\HtmlFormat::attributes_mapping' => array (
),
  'Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment' => function () use (&$pugModule) {
    $attrMapping = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_mapping'];
    $mergeAttr = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::merge_attributes'];
    $pattern = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::pattern'];
    $attr = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::pattern.attribute_pattern'];
    $bool = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::pattern.boolean_attribute_pattern'];

                        $attributes = call_user_func_array($mergeAttr, func_get_args());
                        $code = '';
                        foreach ($attributes as $originalName => $value) {
                            if ($value !== null && $value !== false && ($value !== '' || $originalName !== 'class')) {
                                $name = isset($attrMapping[$originalName])
                                    ? $attrMapping[$originalName]
                                    : $originalName;
                                if ($value === true) {
                                    $code .= $pattern($bool, $name, $name);

                                    continue;
                                }
                                if (is_array($value) || is_object($value) &&
                                    !method_exists($value, '__toString')) {
                                    $value = json_encode($value);
                                }

                                $code .= $pattern($attr, $name, $value);
                            }
                        }

                        return $code;
                    },
  'Phug\\Formatter\\Format\\HtmlFormat::class_attribute_assignment' => function (&$attributes, $value) use (&$pugModule) {

            $split = function ($input) {
                return preg_split('/(?<![\[\{\<\=\%])\s+(?![\]\}\>\=\%])/', strval($input));
            };
            $classes = isset($attributes['class']) ? array_filter($split($attributes['class'])) : [];
            foreach ((array) $value as $key => $input) {
                if (!is_string($input) && is_string($key)) {
                    if (!$input) {
                        continue;
                    }

                    $input = $key;
                }
                foreach ($split($input) as $class) {
                    if (!in_array($class, $classes, true)) {
                        $classes[] = $class;
                    }
                }
            }

            return implode(' ', $classes);
        },
  'Phug\\Formatter\\Format\\HtmlFormat::style_attribute_assignment' => function (&$attributes, $value) use (&$pugModule) {

            if (is_string($value) && mb_substr($value, 0, 7) === '{&quot;') {
                $value = json_decode(htmlspecialchars_decode($value));
            }

            $styles = isset($attributes['style']) ? array_filter(explode(';', $attributes['style'])) : [];

            foreach ((array) $value as $propertyName => $propertyValue) {
                if (!is_int($propertyName)) {
                    $propertyValue = $propertyName.':'.$propertyValue;
                }

                $styles[] = $propertyValue;
            }

            return implode(';', $styles);
        },
]; ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(113);
// PUG_DEBUG:113
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(13);
// PUG_DEBUG:13
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(0);
// PUG_DEBUG:0
 ?><!DOCTYPE html>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(12);
// PUG_DEBUG:12
 ?><html<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['lang' => 'fr'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(10);
// PUG_DEBUG:10
 ?>  <head>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(1);
// PUG_DEBUG:1
 ?>    <meta<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['charset' => 'UTF-8'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(2);
// PUG_DEBUG:2
 ?>    <meta<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['name' => 'viewport'], ['content' => 'width=device-width, initial-scale=1.0'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(4);
// PUG_DEBUG:4
 ?>    <title><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(3);
// PUG_DEBUG:3
 ?>Glimps Gone</title>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(5);
// PUG_DEBUG:5
 ?>    <meta<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['name' => 'description'], ['content' => 'Découvrez l&#039;unique galerie d&#039;art d&#039;œuvres invisibles, un voyage sensoriel au-delà du visuel. Explorez une collection inédite où imagination, sensation et perception défient les frontières de l&#039;art traditionnel. Une expérience artistique inoubliable qui invite à la réflexion et à l&#039;éveil des sens.'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(6);
// PUG_DEBUG:6
 ?>    <link<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['rel' => 'icon'], ['href' => '/glimpsGoneV2/public/img/LogoBlanc.png'], ['type' => 'image/png'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(7);
// PUG_DEBUG:7
 ?>    <link<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['rel' => 'stylesheet'], ['href' => '/glimpsGoneV2/public/css/style.css'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(8);
// PUG_DEBUG:8
 ?>    <link<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css'], ['rel' => 'stylesheet'], ['integrity' => 'sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN'], ['crossorigin' => 'anonymous'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(9);
// PUG_DEBUG:9
 ?>    <link<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['rel' => 'stylesheet'], ['href' => 'https://unpkg.com/splitting/dist/splitting.css'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
  </head>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(11);
// PUG_DEBUG:11
 ?>  <body<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'body'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></body>
</html>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(70);
// PUG_DEBUG:70
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(14);
// PUG_DEBUG:14
 ?><?php $isUserLoggedIn = $GLOBALS['__jpv_and'](isset($GLOBALS['__jpv_dotWithArrayPrototype_with_ref']($_SESSION)['user_id']), function () { return $GLOBALS['__jpv_dotWithArrayPrototype_with_ref']($_SESSION, 'user_id') !== null; }) ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(15);
// PUG_DEBUG:15
 ?><?php $userId = isset($GLOBALS['__jpv_dotWithArrayPrototype_with_ref']($_SESSION)['user_id']) ? $GLOBALS['__jpv_dotWithArrayPrototype_with_ref']($_SESSION, 'user_id') : null ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(69);
// PUG_DEBUG:69
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'content'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(68);
// PUG_DEBUG:68
 ?>  <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'container-fluid'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(30);
// PUG_DEBUG:30
 ?>    <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'row'], ['class' => 'align-items-center'], ['class' => 'justify-content-between'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(19);
// PUG_DEBUG:19
 ?>      <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'col-auto'], ['class' => 'd-flex'], ['class' => 'align-items-center'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(16);
// PUG_DEBUG:16
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'logo'], ['src' => '/glimpsGoneV2/public/img/LogoBlanc.png'], ['alt' => 'Logo de Glimps Gone, l&#039;extravagante galerie d&#039;art d&#039;œuvre invisible'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(18);
// PUG_DEBUG:18
 ?>        <h1<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'titre'], ['class' => 'ml-3'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(17);
// PUG_DEBUG:17
 ?>GLIMPS GONE</h1>
</div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(29);
// PUG_DEBUG:29
 ?>      <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'col-auto'], ['class' => 'd-flex'], ['class' => 'align-items-center'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(24);
// PUG_DEBUG:24
 ?><?php if (
        is_object($_pug_temp = (isset($isUserLoggedIn) ? $isUserLoggedIn : null)) && method_exists($_pug_temp, "__toBoolean")
            ? $_pug_temp->__toBoolean()
            : $_pug_temp) { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(21);
// PUG_DEBUG:21
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'custom-bouton1'], ['class' => 'mr-2'], ['href' => '/glimpsGoneV2/profil'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(20);
// PUG_DEBUG:20
 ?>Mon profil</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(23);
// PUG_DEBUG:23
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'custom-bouton2'], ['class' => 'ml-2'], ['href' => '/glimpsGoneV2/deconnexion'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(22);
// PUG_DEBUG:22
 ?>Deconnexion</a><?php } else { ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(26);
// PUG_DEBUG:26
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'custom-bouton1'], ['class' => 'mr-2'], ['href' => '/glimpsGoneV2/connexion'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(25);
// PUG_DEBUG:25
 ?>Se connecter</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(28);
// PUG_DEBUG:28
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'custom-bouton2'], ['class' => 'ml-2'], ['href' => '/glimpsGoneV2/enregistrer'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(27);
// PUG_DEBUG:27
 ?>S'enregistrer</a><?php } ?>      </div>
    </div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(33);
// PUG_DEBUG:33
 ?>    <div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(32);
// PUG_DEBUG:32
 ?>      <h2<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'slogan'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(31);
// PUG_DEBUG:31
 ?>L’extravagante Galerie d'Art des Œuvres Invisibles !</h2>
    </div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(51);
// PUG_DEBUG:51
 ?>    <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'nav-container'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(50);
// PUG_DEBUG:50
 ?>      <nav>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(49);
// PUG_DEBUG:49
 ?>        <ul>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(36);
// PUG_DEBUG:36
 ?>          <li><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(35);
// PUG_DEBUG:35
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(34);
// PUG_DEBUG:34
 ?>Accueil</a></li>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(39);
// PUG_DEBUG:39
 ?>          <li><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(38);
// PUG_DEBUG:38
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/galerie'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(37);
// PUG_DEBUG:37
 ?>Galerie</a></li>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(42);
// PUG_DEBUG:42
 ?>          <li><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(41);
// PUG_DEBUG:41
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/ajouter'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(40);
// PUG_DEBUG:40
 ?>Ajouter</a></li>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(45);
// PUG_DEBUG:45
 ?>          <li><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(44);
// PUG_DEBUG:44
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/faq'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(43);
// PUG_DEBUG:43
 ?>FAQ</a></li>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(48);
// PUG_DEBUG:48
 ?>          <li><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(47);
// PUG_DEBUG:47
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/infos'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(46);
// PUG_DEBUG:46
 ?>Contact</a></li>
        </ul>
      </nav>
    </div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(54);
// PUG_DEBUG:54
 ?>    <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'menuBurger'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(53);
// PUG_DEBUG:53
 ?>      <button<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['onclick' => 'toggleMenu()'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(52);
// PUG_DEBUG:52
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'burger'], ['src' => '/glimpsGoneV2/public/img/menuBurger.png'], ['alt' => 'Menu'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></button>
    </div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(67);
// PUG_DEBUG:67
 ?>    <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'menuBurgerDesign'], ['id' => 'menuBurgerDesign'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(56);
// PUG_DEBUG:56
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'closebtn'], ['href' => 'javascript:void(0)'], ['onclick' => 'toggleMenu()'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(55);
// PUG_DEBUG:55
 ?>×</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(58);
// PUG_DEBUG:58
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(57);
// PUG_DEBUG:57
 ?>Accueil</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(60);
// PUG_DEBUG:60
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/galerie'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(59);
// PUG_DEBUG:59
 ?>Galerie</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(62);
// PUG_DEBUG:62
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/ajouter'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(61);
// PUG_DEBUG:61
 ?>Ajouter</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(64);
// PUG_DEBUG:64
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/faq'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(63);
// PUG_DEBUG:63
 ?>FAQ</a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(66);
// PUG_DEBUG:66
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['href' => '/glimpsGoneV2/infos'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(65);
// PUG_DEBUG:65
 ?>Contact</a></div>
  </div>
</div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(95);
// PUG_DEBUG:95
 ?><div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'container-fluid'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(94);
// PUG_DEBUG:94
 ?>  <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'row'], ['class' => 'flex-column-reverse'], ['class' => 'flex-md-row'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(93);
// PUG_DEBUG:93
 ?>    <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'col-12'], ['class' => 'col-md-12'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(84);
// PUG_DEBUG:84
 ?>      <p<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'textIndex'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(72);
// PUG_DEBUG:72
 ?>        <h1<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'texteFocus1'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(71);
// PUG_DEBUG:71
 ?>Oublie tout ce que tu pensais savoir sur l'art !</h1>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(73);
// PUG_DEBUG:73
 ?><br><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(82);
// PUG_DEBUG:82
 ?>        <div<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'd-flex'], ['class' => 'justify-content-start'], ['class' => 'mt-4'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(77);
// PUG_DEBUG:77
 ?>          <button<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'custom-bouton1'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(74);
// PUG_DEBUG:74
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(76);
// PUG_DEBUG:76
 ?><span><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(75);
// PUG_DEBUG:75
 ?>Ajouter une oeuvre !</span></button>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(81);
// PUG_DEBUG:81
 ?>          <button<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'custom-bouton2'], ['class' => 'ml-2'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(78);
// PUG_DEBUG:78
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(80);
// PUG_DEBUG:80
 ?><span><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(79);
// PUG_DEBUG:79
 ?>Contactez-nous</span></button>
        </div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(83);
// PUG_DEBUG:83
 ?><br>      </p>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(92);
// PUG_DEBUG:92
 ?>      <p<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'texteAnimation'], ['class' => 'box'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(85);
// PUG_DEBUG:85
 ?>Bienvenue chez Glimps Gone, la galerie où l'art est tellement avant-gardiste que votre petit esprit va enfin servir à quelque chose !<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(86);
// PUG_DEBUG:86
 ?><br><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(87);
// PUG_DEBUG:87
 ?>Nos œuvres invisibles ne se contentent pas de rester là, elles prennent vie dans votre tête, parce qu'ici c'est vous l'artiste !<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(88);
// PUG_DEBUG:88
 ?><br><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(89);
// PUG_DEBUG:89
 ?>Pas besoin de lunettes spéciales ni de charlatans pour apprécier notre exposition. Votre propre cerveau est la toile, votre imagination le pinceau.<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(90);
// PUG_DEBUG:90
 ?><br><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(91);
// PUG_DEBUG:91
 ?>Alors, prêts à plonger dans l'univers de l'art invisible ? À créer l'inimaginable et à rire de l'absurde ? Rejoignez notre fête de l'imagination et laissez votre esprit s'envoler (enfin, si vous en avez un) !</p>
    </div>
  </div>
</div>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(106);
// PUG_DEBUG:106
 ?><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(104);
// PUG_DEBUG:104
 ?><footer>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(103);
// PUG_DEBUG:103
 ?>  <p<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'footerText'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(97);
// PUG_DEBUG:97
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['target' => '_blank'], ['href' => 'https://github.com/baldaxx'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(96);
// PUG_DEBUG:96
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'iconesRS'], ['src' => '/glimpsGoneV2/public/img/github.png'], ['alt' => 'icone menant à Github'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(99);
// PUG_DEBUG:99
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['target' => '_blank'], ['href' => 'https://www.linkedin.com/in/baldax/'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(98);
// PUG_DEBUG:98
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'iconesRS'], ['src' => '/glimpsGoneV2/public/img/linkedin.png'], ['alt' => 'icone menant à Linkedin'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(101);
// PUG_DEBUG:101
 ?><a<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['target' => '_blank'], ['href' => 'https://www.instagram.com/baldax_avec_un_x/'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(100);
// PUG_DEBUG:100
 ?><img<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['class' => 'iconesRS'], ['src' => '/glimpsGoneV2/public/img/instagram.png'], ['alt' => 'icone menant à Instagram'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></a><?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(102);
// PUG_DEBUG:102
 ?> © 2024 GlimpsGone. Tous droits réservés.</p>
</footer>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(105);
// PUG_DEBUG:105
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => '/glimpsGoneV2/public/js/script.js'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(107);
// PUG_DEBUG:107
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.0/gsap.min.js'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(108);
// PUG_DEBUG:108
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.0/ScrollTrigger.min.js'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(109);
// PUG_DEBUG:109
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => 'https://unpkg.com/splitting/dist/splitting.min.js'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(110);
// PUG_DEBUG:110
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => 'https://unpkg.com/lenis@1.0.45/dist/lenis.min.js'], array(  ))
) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(111);
// PUG_DEBUG:111
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => '/glimpsGoneV2/public/js/script.js'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
<?php 
\Phug\Renderer\Profiler\ProfilerModule::recordProfilerDisplayEvent(112);
// PUG_DEBUG:112
 ?><script<?= (is_bool($_pug_temp = $pugModule['Phug\\Formatter\\Format\\HtmlFormat::attributes_assignment'](['src' => '/glimpsGoneV2/public/js/accueil.js'], array(  ))) ? var_export($_pug_temp, true) : $_pug_temp) ?>></script>
