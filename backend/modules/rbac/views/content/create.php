<?php
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
$this->registerJs($this->render('js/create.js'));
?>
<div class="content-create">
    <?= $this->render('_form', [
        'model' => $model,
        'province' => $province,
    ]) ?>
</div>
