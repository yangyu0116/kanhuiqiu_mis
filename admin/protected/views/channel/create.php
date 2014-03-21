<?php
$this->menu = array(
    array('label' => '管理主分类', 'url' => array('admin')),
);
?>

    <h1>添加主分类</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>