<?php

namespace app\controllers;

use app\controllers\resources\ContactResourceTrait;
use app\models\entity\Contact;
use app\models\entity\Phone;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SearchController extends BaseController
{
    use ContactResourceTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'list' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionList()
    {
        $input = Yii::$app->request->get('input');
        $result = $this->makeGeneralSearch($input);

        return $this->asJson(
            $this->contactCollectionResource($result)
        );
    }

    /**
     * @param string $query
     * @return array
     */
    private function makeGeneralSearch(string $query): array
    {
        $userId = 1;
        $contactTable = Contact::tableName();
        $phoneTable = Phone::tableName();

        $result = Contact::find()
                    ->leftJoin("{$phoneTable}", "`{$phoneTable}`.`contact_id` = `{$contactTable}`.`id`")
                    ->where(["{$contactTable}.user_id" => $userId])
                    ->andFilterWhere([
                        'or',
                        [
                            'or',
                            ['LIKE', "{$contactTable}.name", $query],
                            ['LIKE', "{$contactTable}.surname", $query],
                            ['LIKE', "{$contactTable}.patronymic", $query],
                            ['LIKE', "{$phoneTable}.number", $query]
                        ]
                    ])
                    ->with('phones')
                    ->all();

        return $result;
    }
}
