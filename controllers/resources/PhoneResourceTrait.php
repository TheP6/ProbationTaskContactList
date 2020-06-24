<?php

namespace app\controllers\resources;

use app\models\entity\Phone;

trait PhoneResourceTrait
{
    /**
     * @param Phone[] $phones
     * @return array
     */
    protected function phoneCollectionResource(array $phones): array
    {
        $transformed = [];

        foreach ($phones as $phone) {
            $transformed[] = $this->phoneResource($phone);
        }

        return $transformed;
    }

    /**
     * @param Phone $phone
     * @return array
     */
    protected function phoneResource(Phone $phone): array
    {
        return [
            'id'          => $phone->id(),
            'number'      => $phone->phone(),
            'label'       => $phone->label(),
        ];
    }
}