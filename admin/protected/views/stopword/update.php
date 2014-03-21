<?php
$this->menu=array(
	array('label'=>'添加停用词', 'url'=>array('create')),
	array('label'=>'管理停用词', 'url'=>array('admin')),
);
?>

<h1>更新停用词 <?php echo $model->keyword; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>