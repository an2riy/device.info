<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Class Stats
 * @package app\models
 */
class Stats extends Model
{

    /**
     * @var
     */
     public $timestamp;

    /**
     * @var
     */
     public $network;

    /**
     * @var
     */
     public $DeviseInfo;

    /**
     * @var
     */
     public $IMSI;

    /**
     * @var
     */
     public $location;

    /**
     * @var
     */
     public $telephony;

    /**
     * @var null|StatDetail
     */
    private $stat_detail = null;

    /**
     * @var array
     */
    private static $multi_field = ['IMSI', 'telephony'];

    /**
     * @var array
     */
    private static $exclude_field = ['stat_detail', 'multi_field', 'exclude_field'];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp', 'network', 'DeviseInfo', 'IMSI'], 'required'],
            [['timestamp'], 'integer'],
            [['location', 'telephony'], 'safe']
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function save()
    {

        if(!$this->validate()){
            return 0;
        }

        $this->stat_detail = new StatDetail();

        $variables = get_class_vars(get_class($this));
        $columns = array_diff(array_keys($variables), self::$exclude_field);

        $columns_detail = $this->stat_detail->getTableSchema()->getColumnNames();

        foreach($columns as $column){
            $this->fillDetailStat($this->stat_detail, $column, $columns_detail);
        }

        return $this->stat_detail->save();
    }

    /**
     * @param bool|false $asArray
     * @return mixed
     */
    public function getPrimaryKey($asArray = false){
        return $this->stat_detail ? $this->stat_detail->getPrimaryKey($asArray) : 0;
    }

    /**
     * @return \string[]
     */
    public static function primaryKey(){
        return StatDetail::primaryKey();
    }

    /**
     * @param $condition
     * @return null|static
     */
    public static function findOne($condition)
    {
        /**
         * @var StatDetail
         */
        $model_detail = StatDetail::findOne($condition);

        $variables = get_class_vars(get_called_class());
        $columns = array_diff(array_keys($variables), self::$exclude_field);

        return self::prepareModelToShow($model_detail, $columns);
    }

    /**
     * @param StatDetail $model
     * @param $columns
     * @return \stdClass
     */
    private static function prepareModelToShow(StatDetail $model, $columns){

        $attributes = $model->getAttributes();

        $class = new \stdClass();
        $keys = StatDetail::primaryKey();

        $class->{array_shift($keys)} = $model->getPrimaryKey();

        foreach($columns as $column){

            $class->{$column} = null;
            $multi_field = [];

            foreach($attributes as $key => $val){
                $name = [];
                if (preg_match('~' . $column . '_(.*)~', $key, $name)) {

                    if(in_array($column, self::$multi_field)){

                        $name_m = [];
                        if (preg_match('~^(.*)_(.*)~', $name[1], $name_m)) {
                            if(!isset($multi_field[$name_m[2]])){
                                $multi_field[$name_m[2]] = new \stdClass();
                            }
                            $multi_field[$name_m[2]]->{$name_m[1]} = $val;
                        }

                    } else {

                        if (!$class->{$column}) {
                            $class->{$column} = new \stdClass();
                        }
                        $class->{$column}->{$name[1]} = preg_match('~^\{|\[~', $val) ? Json::decode($val) : $val;

                    }
                    unset($attributes[$key]);
                }

            }

            if(in_array($column, self::$multi_field) && !empty($multi_field)){
                $class->{$column} = array_values($multi_field);
            }

        }

        foreach($columns as $column){

            if(!$class->{$column}){
                foreach($attributes as $key => $val){
                    if($column == $key){
                        $class->{$column} = $val;
                    }
                }
            }

        }

        return $class;

    }

    /**
     * @param int $limit
     * @param int $offset
     * @param int $total
     * @return array
     */
    public static function findAll($limit = 0, $offset = 0, &$total = 0){

        $keys = StatDetail::primaryKey();

        $models = StatDetail::find()
            ->select    ('*', 'SQL_CALC_FOUND_ROWS')
            ->orderBy   ([array_shift($keys) => SORT_DESC])
            ->offset    ((int)$offset)
            ->limit     ((int)$limit)
            ->all();

        $total = StatDetail::getDb()->createCommand('SELECT FOUND_ROWS()')->queryScalar();

        $variables = get_class_vars(get_called_class());
        $columns =  array_diff(array_keys($variables), self::$exclude_field);

        $models_prepared = [];
        foreach($models as $model){
            $models_prepared[] = self::prepareModelToShow($model, $columns);
        }

        return $models_prepared;
    }

    /**
     * @param StatDetail $stat_detail
     * @param $name_column
     * @param $columns_detail
     */
    private function fillDetailStat(StatDetail $stat_detail, $name_column, $columns_detail){

        $data = $this->{$name_column};
        if($data) {

            if(in_array($name_column, self::$multi_field)){
                foreach ($data as $k => $v) {
                    foreach ($v as $key => $val) {

                        $name = $name_column.'_'.$key.'_'.($k + 1);
                        if(in_array($name, $columns_detail)){
                            $stat_detail->{$name} = is_array($val) ? Json::encode($val) : $val;
                        }

                    }
                }
                return;
            }

            if (count($data) != count($data, COUNT_RECURSIVE)) {
                $data = array_shift($data);
            }

            if (is_array($data)) {
                foreach ($data as $key => $val) {

                    $name = $name_column.'_'.$key;
                    if(in_array($name, $columns_detail)){
                        $stat_detail->{$name} = is_array($val) ? Json::encode($val) : $val;
                    }
                }

            } else {
                if(in_array($name_column, $columns_detail)){
                    $stat_detail->{$name_column} = $this->{$name_column};
                }
            }
        }

    }

}
