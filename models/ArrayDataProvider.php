<?php
/**
 * Created by PhpStorm.
 * User: kastiel
 * Date: 12.10.2015
 * Time: 12:43
 */

namespace app\models;

use yii\data\ArrayDataProvider as ArrayDataProviderBase;

class ArrayDataProvider extends ArrayDataProviderBase
{
    /**
     * @var int total count models
     */
    public $totalCount = 0;

    /**
     * @inheritdoc
     */
    protected function prepareModels()
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
            $models = $this->sortModels($models, $sort);
        }

        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->totalCount;
        }

        return $models;
    }

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount()
    {
        return $this->totalCount;
    }
}