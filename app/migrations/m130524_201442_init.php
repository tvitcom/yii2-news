<?php

use yii\db\Migration;

class m130524_201442_init extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%person}}', [
            'id' => $this->bigPrimaryKey(),
            'username' => $this->string(64)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'pass_hash' => $this->string(64)->notNull(),
            'password_reset_token' => $this->string(128)->unique(),
            'email' => $this->string(64)->notNull()->unique(),
            'status' => $this->smallInteger(8)->notNull()->defaultValue(10),
            'created_at' => $this->datetime(12)->notNull(),
            'updated_at' => $this->datetime(12)->notNull(),
            ], $tableOptions);
    }

    public function down() {
        $this->dropTable('{{%user}}');
    }

}
