<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;

class Stats extends Model
{

     public $timestamp;
     public $network;
     public $DeviseInfo;
     public $IMSI;
     public $location;

    /**
     * @var null|StatDetail
     */
    private $stat_detail = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timestamp', 'network', 'DeviseInfo', 'IMSI', 'location'], 'required'],
            [['timestamp'], 'integer']
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function save()
    {
        $this->stat_detail = new StatDetail();

        $columns = array_keys(get_class_vars(get_class($this)));
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

        $columns = array_keys(get_class_vars(get_called_class()));
        array_pop($columns);

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
        $class->{array_shift(StatDetail::primaryKey())} = $model->getPrimaryKey();

        foreach($columns as $column){

            $class->{$column} = null;
            $diff_only_id = [];

            foreach($attributes as $key => $val){
                $name = [];
                if (preg_match('~' . $column . '_(.*)~', $key, $name)) {

                    if(in_array($column, ["IMSI"])){

                        $name_m = [];
                        if (preg_match('~^(.*)_(.*)~', $name[1], $name_m)) {
                            if(!isset($diff_only_id[$name_m[2]])){
                                $diff_only_id[$name_m[2]] = new \stdClass();
                            }
                            $diff_only_id[$name_m[2]]->{$name_m[1]} = $val;
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

            if($column == "IMSI" && !empty($diff_only_id)){
                $class->{$column} = array_values($diff_only_id);
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
     * @param $limit
     * @return array
     */
    public static function findAll($limit){

        $models = StatDetail::find()
            ->orderBy([array_shift(StatDetail::primaryKey()) => SORT_DESC])
            ->limit($limit)
            ->all();

        $columns = array_keys(get_class_vars(get_called_class()));
        array_pop($columns);

        $models_prepared = [];
        foreach($models as $model){
            $models_prepared[] =self::prepareModelToShow($model, $columns);
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

            if(in_array($name_column, ['IMSI'])){
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
