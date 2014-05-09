<?php

namespace InFog\Routine;

/**
 * Convert data to JSON
 */
class Json
{
    /**
     * @param mixed $data
     * @return string
     */
    public function __invoke($data)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        unset($data['_view']);
        return json_encode($data, \JSON_FORCE_OBJECT);
    }
}