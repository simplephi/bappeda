<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\MbRpjmdVisi;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\MbRpjmdMisi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mb-rpjmd-misi-form">

    <?php $form = ActiveForm::begin(); ?>

  
    
    <?= $form->field($model, 'mb_rpjmd_visi_id')->widget(Select2::classname(), [
        
        
        'data' => ArrayHelper::map(MbRpjmdVisi::find()->all(),'mb_rpjmd_visi_id','mb_rpjmd_visi_isi'),
        'language' => 'en',
       // 'tabindex' => false,
        'options' => ['placeholder' => 'Pilih Visi'],
        'pluginOptions' => [
            'allowClear' => true
            ],
        ]);
  
    ?>
    

    <?= $form->field($model, 'mb_rpjmd_misi_isi')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'mb_rpjmd_misi_ket')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
