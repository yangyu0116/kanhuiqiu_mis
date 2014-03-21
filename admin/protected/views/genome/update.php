<?php
$this->menu=array(
	array('label'=>'添加次分类', 'url'=>array('create')),
	array('label'=>'查看次分类', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'管理次分类', 'url'=>array('admin')),
);
?>

<h1>修改次分类 <?php echo $model->title_chs; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>