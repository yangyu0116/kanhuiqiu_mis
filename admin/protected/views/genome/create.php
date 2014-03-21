<?php
$this->menu = array(
    array('label' => '管理次分类', 'url' => array('admin')),
);
?>

<h1>添加次分类</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>