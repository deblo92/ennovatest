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
            [['file_to_upload'], 'file_to_upload', 'skipOnEmpty' => false, 'extensions' => 'csv'],
            [['types'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'file_to_upload' => 'Select file_to_upload',
            'types' => 'Tipologia file_to_upload da importare',
        );
    }
}