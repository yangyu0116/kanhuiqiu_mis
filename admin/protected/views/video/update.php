<?php
$this->layout = '//layouts/column2';

$this->menu=array(
	array('label'=>'添加短视频', 'url'=>array('create')),
	array('label'=>'更新该视频', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'删除该视频', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>更新短视频 #<?php echo $model->id; ?> <?php echo $model->title; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>