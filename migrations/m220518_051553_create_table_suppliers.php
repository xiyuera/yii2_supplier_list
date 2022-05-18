<?php

use yii\db\Migration;
use Faker\Factory;

/**
 * Class m220518_051553_create_table_suppliers
 */
class m220518_051553_create_table_suppliers extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('suppliers', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->defaultValue(''),
            'code' => $this->char(3)->defaultValue(null)->unique(),
            't_status' => $this->tinyInteger(1),
        ]);
        $factory = Factory::create();
        for ($i = 0; $i < 100000; $i++) {
            $this->insert('suppliers', [
                'name' => $factory->company,
                'code' => $factory->unique()->regexify('[a-zA-Z0-9]{3}'),
                't_status' => $factory->randomElement([1, 2]),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('suppliers');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220518_051553_create_table_suppliers cannot be reverted.\n";

        return false;
    }
    */
}
