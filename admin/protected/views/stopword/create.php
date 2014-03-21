<?php
$this->menu=array(
	array('label'=>'管理停用词表', 'url'=>array('admin')),
);
?>

<h1>添加停用词</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>