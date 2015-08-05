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
