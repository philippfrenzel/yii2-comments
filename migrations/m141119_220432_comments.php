<?php
use yii\db\Schema;
use yii\db\Migration;

class m141119_220432_comments extends Migration
{

    public function up()
    {
        switch (Yii::$app->db->driverName) {
            case 'mysql':
              $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
              break;
            case 'pgsql':
              $tableOptions = null;
              break;
            default:
              throw new RuntimeException('Your database is not supported!');
        }
        
        $this->createTable('{{%comment}}', [
            'id' => 'pk',
            'comment' => Schema::TYPE_TEXT,
            
            'model_type' => Schema::TYPE_STRING,
            'model_id'   => Schema::TYPE_INTEGER,
            'state_id'   => Schema::TYPE_INTEGER,
            
            'type_id'    => Schema::TYPE_INTEGER,
            
            //timestamps
            'created_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'        => Schema::TYPE_INTEGER . ' NOT NULL',
            'deleted_at'        => Schema::TYPE_INTEGER . ' DEFAULT NULL'
        ],$tableOptions);
    }

    public function down()
    {        
        $this->dropTable('{{%comment}}');        
    }
}
