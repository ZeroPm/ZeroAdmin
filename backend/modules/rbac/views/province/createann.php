<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="province-create">
    <?= $this->render('_annform', [
        'model' => $model,
        'province' => $province,
    ]) ?>
</div>
