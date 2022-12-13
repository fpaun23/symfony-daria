<?php

namespace App\Validators;

/**
 * JobValidator
 */
class JobValidator
{
    /**
     * nameIsValid
     *
     * @param  mixed $data
     * @return bool
     */
    public function validToArray(array $data): bool
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('job name is not set');
        }

        if (strlen($data['name']) <= 2) {
            throw new \InvalidArgumentException('job name length is less than 2');
        }

        return true;
    }
    /**
     * idIsValid
     *
     * @param  mixed $id
     * @return bool
     */
    public function idIsValid(int $id): bool
    {
        if ($id < 0) {
            throw new \InvalidArgumentException('the id val must be greater than 0');
        }
        return true;
    }
    public function nameIsValid(string $name): bool
    {
        if (strlen($name <= 2)) {
            throw new \InvalidArgumentException('the name val must be greater than 2');
        }
        return true;
    }
}
