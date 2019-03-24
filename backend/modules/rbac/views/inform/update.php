<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
?>
<div class="inform-update">
    <?= $this->render('_form', [
        'model' => $model,
        'content' => $content,
    ]) ?>

</div>
