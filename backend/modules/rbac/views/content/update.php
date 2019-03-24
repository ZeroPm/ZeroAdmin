<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="content-update">
    <?= $this->render('_form', [
        'model' => $model,
        'province' => $model,
    ]) ?>

</div>
