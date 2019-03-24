<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="content-create">
    <?= $this->render('_form', [
        'model' => $model,
        'province' => $province,
    ]) ?>
</div>
