<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="cuser-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
