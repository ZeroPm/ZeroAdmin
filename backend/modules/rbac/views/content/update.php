<?php
use backend\assets\LayuiAsset;
LayuiAsset::register($this);
$this->registerJs($this->render('js/create.js'));
?>
<div class="content-update">
    <?= $this->render('_form', [
        'model' => $model,
        'province' => $model,
    ]) ?>

</div>
