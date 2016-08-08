<?php

use yii\db\Schema;
use yii\db\Migration;

class m160808_064740_telephony_update extends Migration
{
    /**
     *
     */
    public function up()
    {
        $this->renameColumn('stats_detail', 'telephony_countryIso', 'telephony_countryIso_1');
        $this->renameColumn('stats_detail', 'telephony_dataRoaming', 'telephony_dataRoaming_1');
        $this->renameColumn('stats_detail', 'telephony_iccId', 'telephony_iccId_1');
        $this->renameColumn('stats_detail', 'telephony_mcc', 'telephony_mcc_1');
        $this->renameColumn('stats_detail', 'telephony_mnc', 'telephony_mnc_1');
        $this->renameColumn('stats_detail', 'telephony_number', 'telephony_number_1');
        $this->renameColumn('stats_detail', 'telephony_simSlotIndex', 'telephony_simSlotIndex_1');
        $this->renameColumn('stats_detail', 'telephony_displayName', 'telephony_displayName_1');
        $this->renameColumn('stats_detail', 'telephony_carrierName', 'telephony_carrierName_1');
        $this->addColumn('stats_detail', 'telephony_countryIso_2', Schema::TYPE_STRING.'(15)');
        $this->addColumn('stats_detail', 'telephony_dataRoaming_2', Schema::TYPE_STRING.'(5)');
        $this->addColumn('stats_detail', 'telephony_iccId_2', Schema::TYPE_BIGINT.'(20)');
        $this->addColumn('stats_detail', 'telephony_mcc_2', Schema::TYPE_STRING.'(50)');
        $this->addColumn('stats_detail', 'telephony_mnc_2', Schema::TYPE_STRING.'(50)');
        $this->addColumn('stats_detail', 'telephony_number_2', Schema::TYPE_STRING);
        $this->addColumn('stats_detail', 'telephony_simSlotIndex_2', Schema::TYPE_STRING.'(50)');
        $this->addColumn('stats_detail', 'telephony_displayName_2', Schema::TYPE_STRING);
        $this->addColumn('stats_detail', 'telephony_carrierName_2', Schema::TYPE_STRING);
        $this->addColumn('stats_detail', 'telephony_countryIso_3', Schema::TYPE_STRING.'(15)');
        $this->addColumn('stats_detail', 'telephony_dataRoaming_3', Schema::TYPE_STRING.'(5)');
        $this->addColumn('stats_detail', 'telephony_iccId_3', Schema::TYPE_BIGINT.'(20)');
        $this->addColumn('stats_detail', 'telephony_mcc_3', Schema::TYPE_STRING.'(50)');
        $this->addColumn('stats_detail', 'telephony_mnc_3', Schema::TYPE_STRING.'(50)');
        $this->addColumn('stats_detail', 'telephony_number_3', Schema::TYPE_STRING);
        $this->addColumn('stats_detail', 'telephony_simSlotIndex_3', Schema::TYPE_STRING.'(50)');
        $this->addColumn('stats_detail', 'telephony_displayName_3', Schema::TYPE_STRING);
        $this->addColumn('stats_detail', 'telephony_carrierName_3', Schema::TYPE_STRING);
    }

    /**
     *
     */
    public function down()
    {
        $this->renameColumn('stats_detail', 'telephony_countryIso_1', 'telephony_countryIso');
        $this->renameColumn('stats_detail', 'telephony_dataRoaming_1', 'telephony_dataRoaming');
        $this->renameColumn('stats_detail', 'telephony_iccId_1', 'telephony_iccId');
        $this->renameColumn('stats_detail', 'telephony_mcc_1', 'telephony_mcc');
        $this->renameColumn('stats_detail', 'telephony_mnc_1', 'telephony_mnc');
        $this->renameColumn('stats_detail', 'telephony_number_1', 'telephony_number');
        $this->renameColumn('stats_detail', 'telephony_simSlotIndex_1', 'telephony_simSlotIndex');
        $this->renameColumn('stats_detail', 'telephony_displayName_1', 'telephony_displayName');
        $this->renameColumn('stats_detail', 'telephony_carrierName_1', 'telephony_carrierName');
        $this->dropColumn('stats_detail', 'telephony_countryIso_2');
        $this->dropColumn('stats_detail', 'telephony_dataRoaming_2');
        $this->dropColumn('stats_detail', 'telephony_iccId_2');
        $this->dropColumn('stats_detail', 'telephony_mcc_2');
        $this->dropColumn('stats_detail', 'telephony_mnc_2');
        $this->dropColumn('stats_detail', 'telephony_number_2');
        $this->dropColumn('stats_detail', 'telephony_simSlotIndex_2');
        $this->dropColumn('stats_detail', 'telephony_displayName_2');
        $this->dropColumn('stats_detail', 'telephony_carrierName_2');
        $this->dropColumn('stats_detail', 'telephony_countryIso_3');
        $this->dropColumn('stats_detail', 'telephony_dataRoaming_3');
        $this->dropColumn('stats_detail', 'telephony_iccId_3');
        $this->dropColumn('stats_detail', 'telephony_mcc_3');
        $this->dropColumn('stats_detail', 'telephony_mnc_3');
        $this->dropColumn('stats_detail', 'telephony_number_3');
        $this->dropColumn('stats_detail', 'telephony_simSlotIndex_3');
        $this->dropColumn('stats_detail', 'telephony_displayName_3');
        $this->dropColumn('stats_detail', 'telephony_carrierName_3');
    }
}
