<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MbKegiatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mb Kegiatans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mb-kegiatan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Mb Kegiatan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'mb_kegiatan_id',
            //'mb_program_id',
            'mbProgram.mb_program_nama',
            'mb_kegiatan_kode',
            'mb_kegiatan_nama',
            //'mb_kegiatan_ket',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>