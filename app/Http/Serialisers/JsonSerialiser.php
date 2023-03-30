<?php

declare(strict_types=1);

namespace App\Http\Serialisers;

use League\Fractal\Serializer\ArraySerializer;

class JsonSerialiser extends ArraySerializer
{
    /**
     * @param $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data): array
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }

        return $data;
    }

    /**
     * @param $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item($resourceKey, array $data): array
    {
        if ($resourceKey) {
            return [$resourceKey => $data];
        }
        return $data;
    }
}
