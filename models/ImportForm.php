<?php


namespace app\models;


use yii\base\Model;

class ImportForm extends  Model
{
    public $file;
    public $types;
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv'],
            [['types'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'file' => 'Select file',
            'types' => 'Tipologia file da importare',
        );
    }
}