<?php


namespace app\controllers\resources;


use app\models\entity\Contact;

trait ContactResourceTrait
{
    use PhoneResourceTrait;

    /**
     * @param Contact[] $contacts
     * @return array
     */
    protected function contactCollectionResource(array $contacts): array
    {
        $transformed = [];

        foreach ($contacts as $contact) {
            $transformed[] = $this->contactResource($contact);
        }

        return $transformed;
    }

    /**
     * @param Contact $contact
     * @return array
     */
    protected function contactResource(Contact $contact): array
    {
        $transformed = [
            'id'          => $contact->id(),
            'name'        => $contact->name(),
            'surname'     => $contact->surname(),
            'patronymic'  => $contact->patronymic(),
            'phones'      => $this->phoneCollectionResource($contact->phones())
        ];

        return $transformed;
    }
}