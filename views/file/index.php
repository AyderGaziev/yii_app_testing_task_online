<?php

use app\models\File;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Upload Files', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fileName',
            'dateTime:datetime',
            'path' =>
            array(
                'attribute' =>'path',
                'format' => 'html',
                'value'=>function($model) {
                    $link = Html::a('ZIP', ['download', 'path' => $model->path], ['class' => 'btn btn-success']);
                    return
                    "
                        <p>$model->path</p>
                        $link
"
                        ;},

            ),
            'preview' =>
                array(
                    'attribute' =>'preview',
                    'format' => 'html',
                    'value'=>function($model) { return
                        "<img src='$model->path' alt='$model->fileName' width='200px' height='200px'>";},

                ),

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, File $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
            ],
        ],
    ]); ?>


</div>
