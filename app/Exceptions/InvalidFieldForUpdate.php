<?php

namespace App\Exceptions;

use Exception;

class InvalidFieldForUpdate extends Exception
{
    public function __construct(string $fieldName, string $entity, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            "{$fieldName} is not a valid updatable field for a {$entity}",
            $code,
            $previous
        );
    }
}
