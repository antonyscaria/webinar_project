<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \app\models\Webinar $model */
/** @var \yii\base\DynamicModel $user */
/** @var array|null $result */
?>

<div class="webinar-register">

    <h2>Register for Webinar: <?= Html::encode($model->name ?? "Unknown Webinar") ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'first_name') ?>
    <?= $form->field($user, 'last_name') ?>
    <?= $form->field($user, 'email') ?>

    <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

    <?php if (!empty($result) && is_array($result)): ?>
        <div class="card" style="max-width: 500px; margin-top: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h3 style="margin-bottom: 15px;">Registrant Details</h3>
            <p><strong>RegistrantKey:</strong> <?= Html::encode($result['registrantKey'] ?? '') ?></p>
            <p><strong>JoinUrl:</strong>
                <a href="<?= Html::encode($result['joinUrl'] ?? '#') ?>" target="_blank">Link</a>
                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('<?= Html::encode($result['joinUrl'] ?? '') ?>')">Copy</button>
            </p>
            <p><strong>Status:</strong> <?= Html::encode($result['status'] ?? '') ?></p>
        </div>
    <?php endif; ?>
<p><?= Html::a('Back to list', ['index'], ['class' => 'btn btn-primary']) ?></p>
</div>

<script>
function copyToClipboard(text) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied: ' + text);
    }).catch(err => {
        alert('Failed to copy text.');
    });
}
</script>
