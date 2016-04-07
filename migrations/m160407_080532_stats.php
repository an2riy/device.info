<?php

use yii\db\Migration;

/**
 * Class m160407_080532_stats
 */
class m160407_080532_stats extends Migration
{

    /**
     * @var string
     */
    private $table = '{{%stats_detail}}';


    /**
     *
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->table, [
            'id'                     => $this->primaryKey(),
            'timestamp'              => $this->bigInteger()->notNull(),
            'network_name'           => $this->string()->notNull(),
            'network_mac'            => $this->string(50)->notNull(),
            'network_InetAddresses'  => $this->string()->notNull(),
            'DeviseInfo_board'       => $this->string()->notNull(),
            'DeviseInfo_bootloader'  => $this->string()->notNull(),
            'DeviseInfo_brand'       => $this->string()->notNull(),
            'DeviseInfo_codename'    => $this->string()->notNull(),
            'DeviseInfo_device'      => $this->string()->notNull(),
            'DeviseInfo_display'     => $this->string()->notNull(),
            'DeviseInfo_fingerprint' => $this->text()->notNull(),
            'DeviseInfo_id'          => $this->string()->notNull(),
            'DeviseInfo_manufacturer'=> $this->string()->notNull(),
            'DeviseInfo_model'       => $this->string()->notNull(),
            'DeviseInfo_product'     => $this->string()->notNull(),
            'DeviseInfo_serial'      => $this->string()->notNull(),
            'DeviseInfo_incremental' => $this->string()->notNull(),
            'DeviseInfo_sdk'         => $this->string(20)->notNull(),
            'DeviseInfo_root'        => $this->string(50)->notNull(),
            'IMSI_imsi_1'            => $this->string()->notNull(),
            'IMSI_ready_1'           => $this->string(50)->notNull(),
            'IMSI_imsi_2'            => $this->string()->defaultValue(''),
            'IMSI_ready_2'           => $this->string(50)->defaultValue(''),
            'IMSI_imsi_3'            => $this->string()->defaultValue(''),
            'IMSI_ready_3'           => $this->string()->defaultValue(''),
            'location_latitude'      => $this->string(50)->defaultValue(''),
            'location_longitude'     => $this->string(50)->defaultValue(''),
            'location_accuracy'      => $this->string(50)->defaultValue(''),
            'location_provider'      => $this->string()->defaultValue(''),
            'telephony_countryIso'   => $this->string(15)->defaultValue(''),
            'telephony_dataRoaming'  => $this->string(5)->defaultValue(''),
            'telephony_iccId'        => $this->bigInteger()->defaultValue(0),
            'telephony_mcc'          => $this->string(50)->defaultValue(''),
            'telephony_mnc'          => $this->string(50)->defaultValue(''),
            'telephony_number'       => $this->string()->defaultValue(''),
            'telephony_simSlotIndex' => $this->string(50)->defaultValue(''),
            'telephony_displayName'  => $this->string()->defaultValue(''),
            'telephony_carrierName'  => $this->string()->defaultValue(''),

        ], $tableOptions);
    }

    /**
     *
     */
    public function down()
    {
        $this->dropTable($this->table);
    }

}
