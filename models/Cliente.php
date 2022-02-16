<?php


namespace app\models;


use yii\base\Model;

class Cliente extends \yii\db\ActiveRecord
{

    public static function tableName(){
        return '{{clienti}}';
    }


    public function getPratiche(){
        return $this->hasMany(Pratica::class, ['cliente_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'codicefiscale' => 'Codice fiscale / P.iva',
        ];
    }
}