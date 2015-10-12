<?php

namespace app\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "stats_detail".
 *
 * @property integer $id
 * @property integer $timestamp
 * @property string $network_name
 * @property string $network_mac
 * @property string $network_InetAddresses
 * @property string $DeviseInfo_board
 * @property string $DeviseInfo_bootloader
 * @property string $DeviseInfo_codename
 * @property string $DeviseInfo_device
 * @property string $DeviseInfo_display
 * @property string $DeviseInfo_fingerprint
 * @property string $DeviseInfo_id
 * @property string $DeviseInfo_manufacturer
 * @property string $DeviseInfo_model
 * @property string $DeviseInfo_product
 * @property string $DeviseInfo_serial
 * @property string $DeviseInfo_incremental
 * @property string $IMSI_imsi_1
 * @property string $IMSI_ready_1
 * @property string $IMSI_imsi_2
 * @property string $IMSI_ready_2
 * @property string $location_latitude
 * @property string $location_longitude
 * @property string $location_provider
 */
class StatDetail extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stats_detail}}';
    }


}