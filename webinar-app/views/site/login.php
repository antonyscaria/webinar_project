<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>

<div class="site-login" style="max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please enter your login credentials:</p>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

        <div style="margin-top: 15px;">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
