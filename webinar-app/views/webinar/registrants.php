<?php
use yii\helpers\Html;

/** @var \app\models\Webinar $webinar */
/** @var \app\models\WebinarRegistrant[] $registrants */
?>

<div class="webinar-registrants">
    <h2>Registrants for Webinar: <?= Html::encode($webinar->name ?? "Unknown Webinar") ?></h2>

    <?php if (!empty($registrants) && is_array($registrants)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrants as $index => $r): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= Html::encode($r->first_name) ?></td>
                        <td><?= Html::encode($r->last_name) ?></td>
                        <td>
                            <?= Html::encode($r->email) ?>
                            <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('<?= Html::encode($r->email) ?>')">Copy</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No registrants found for this webinar.</p>
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
