<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class Pratica extends ActiveRecord
{


    public static function tableName(){
        return '{{pratiche}}';
    }

    public function getCliente(){
        return $this->hasOne(Cliente::class,  ['id' => 'cliente_id']);
    }

    public function attributeLabels()
    {
        return [
            'id_pratica' => 'Id pratica',
        ];
    }
}