<?php

use yii\db\Migration;

/**
 * Class m160407_103918_stats_extra_fieldss
 */
class m160407_103918_stats_extra_fieldss extends Migration
{

    /**
     * @var string
     */
    private $table = '{{%stats_detail}}';

    /**
     * @return bool
     */
    public function safeUp()
    {
        $this->renameColumn($this->table, 'DeviseInfo_sdk', 'DeviseInfo_sdk_version');
        $this->addColumn   ($this->table, 'DeviseInfo_app_version', $this->string(20)->notNull() .' AFTER DeviseInfo_sdk_version');

        return true;
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        $this->renameColumn($this->table, 'DeviseInfo_sdk_version', 'DeviseInfo_sdk');
        $this->dropColumn  ($this->table, 'DeviseInfo_app_version');

        return true;
    }

}
