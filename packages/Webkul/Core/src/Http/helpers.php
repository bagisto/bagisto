<?php
    use Webkul\Core\Core;
    
    if (! function_exists('core')) {
        function core()
        {
            return new Core;
        }
    }

    if (! function_exists('array_permutation')) {
        function array_permutation($input)
        {
            $results = [];

            while (list($key, $values) = each($input)) {
                if (empty($values)) {
                    continue;
                }

                if (empty($results)) {
                    foreach($values as $value) {
                        $results[] = [$key => $value];
                    }
                } else {
                    $append = [];

                    foreach($results as &$result) {
                        $result[$key] = array_shift($values);

                        $copy = $result;

                        foreach($values as $item) {
                            $copy[$key] = $item;
                            $append[] = $copy;
                        }

                        array_unshift($values, $result[$key]);
                    }

                    $results = array_merge($results, $append);
                }
            }

            return $results;
        }
    }
?>