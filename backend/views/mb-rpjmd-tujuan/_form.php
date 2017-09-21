<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\MbRpjmdMisi;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\MbRpjmdTujuan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mb-rpjmd-tujuan-form">

    <?php $form = ActiveForm::begin(); ?>

      
    <?= $form->field($model, 'mb_rpjmd_misi_id')->widget(Select2::classname(), [
        
        
        'data' => ArrayHelper::map(MbRpjmdMisi::find()->all(),'mb_rpjmd_misi_id','mb_rpjmd_misi_isi'),
        'language' => 'en',
       // 'tabindex' => false,
        'options' => ['placeholder' => 'Pilih Misi'],
        'pluginOptions' => [
            'allowClear' => true
            ],
        ]);
  
    ?>

    <?= $form->field($model, 'mb_tujuan_isi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'mb_tujuan_ket')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
