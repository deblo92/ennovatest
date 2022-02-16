<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

$this->title = 'Import csv';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'import-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'file_to_upload')->fileInput() ?>

                <?= $form->field($model, 'types')->dropdownList([
                    "" => '--',
                    "C" => 'Importazione clienti',
                    "P" => 'Importazione pratiche'
                ]) ?>


                <div class="form-group">
                    <?= Html::submitButton('Import csv', ['class' => 'btn btn-primary btn-sm', 'name' => 'import-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>
