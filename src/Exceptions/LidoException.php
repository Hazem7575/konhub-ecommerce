<?php

namespace Konhub\Lido\Exceptions;

use Exception;

class LidoException extends Exception
{
    public static function invalidJson(): self
    {
        return new static('Invalid JSON structure provided');
    }

    public static function missingRequiredField(string $field): self
    {
        return new static("Missing required field: {$field}");
    }

    public static function invalidLayerType(string $type): self
    {
        return new static("Invalid layer type: {$type}");
    }
}
