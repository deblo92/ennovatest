<?php

use yii\db\Migration;
use app\models\Cliente;
/**
 * Handles the creation of table `{{%clienti}}`.
 */
class m220213_183920_create_clienti_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clienti}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'cognome' => $this->string()->notNull(),
            'codicefiscale' => $this->string()->notNull(),
            'note' => $this->text()->null()->defaultValue(null),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%clienti}}');
    }
}
