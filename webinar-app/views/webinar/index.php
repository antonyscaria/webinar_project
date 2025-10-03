<?php
use yii\helpers\Html;

$this->title = 'Webinars';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('Create Webinar', ['create'], ['class' => 'btn btn-success']) ?></p>

<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($webinars as $webinar): ?>
    <tr>
        <td><?= Html::encode($webinar->name) ?></td>
        <td><?= Html::encode($webinar->description) ?></td>
        <td>
            <?= Html::a('View', ['view', 'id' => $webinar->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Update', ['update', 'id' => $webinar->id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Register', ['register', 'id' => $webinar->id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Registrants', ['registrants', 'id' => $webinar->id], ['class' => 'btn btn-success']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
