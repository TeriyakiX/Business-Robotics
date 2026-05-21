<?php

declare(strict_types=1);

namespace App\Traits\DTOs;

trait UseAsArrayTrait
{
    /**
     * @param array|null $only - только указанные поля
     * @param array $except - исключить указанные поля
     * @param bool $filterNull - исключать ли null значения (по умолчанию true)
     */
    public function toArray(?array $only = null, array $except = [], bool $filterNull = true): array
    {
        $data = (array) $this;

        if ($filterNull) {
            $data = array_filter($data, fn($value) => $value !== null);
        }

        if ($only !== null) {
            $data = array_intersect_key($data, array_flip($only));
        }

        if (!empty($except)) {
            $data = array_diff_key($data, array_flip($except));
        }

        return $data;
    }

    public function toDatabaseArray(): array
    {
        return $this->toArray(filterNull: true);
    }
}
