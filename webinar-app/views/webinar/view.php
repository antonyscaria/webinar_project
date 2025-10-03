<?php
use yii\helpers\Html;

$this->title = 'View Webinar';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><strong>Name:</strong> <?= Html::encode($model->name) ?></p>
<p><strong>Description:</strong> <?= Html::encode($model->description) ?></p>
<p><strong>GotoWebinar Event ID:</strong> <?= Html::encode($model->event_id) ?></p>

<p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
    <?= Html::a('Back to list', ['index'], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Register User', ['register', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
</p>
