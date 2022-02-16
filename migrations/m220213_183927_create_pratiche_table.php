<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pratiche}}`.
 */
class m220213_183927_create_pratiche_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pratiche}}', [
            'id' => $this->primaryKey(),
            'cliente_id' => $this->integer()->notNull(),
            'id_pratica' => $this->string()->notNull(),
            'stato_pratica' => "ENUM('open', 'clonse')",
            'note' => $this->text()->null()->defaultValue(null),
            'created_at' => $this->timestamp(),
        ]);

        $this->createIndex(
            'idx-pratiche-cliente_id',
            'pratiche',
            'cliente_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-pratiche-cliente_id',
            'pratiche',
            'cliente_id',
            'clienti',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pratiche}}');
    }
}
