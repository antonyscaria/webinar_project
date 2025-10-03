<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Webinar';
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>

<p><?= Html::a('Back to list', ['index'], ['class' => 'btn btn-primary']) ?></p>
