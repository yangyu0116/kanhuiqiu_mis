<?php
$this->menu = array(
    array('label' => '管理站点', 'url' => array('admin')),
);
?>

    <h1>添加站点</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>