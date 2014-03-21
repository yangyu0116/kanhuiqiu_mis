<?php
$this->layout = '//layouts/column2';

$this->menu=array(
	array('label'=>'添加短视频', 'url'=>array('create')),
	array('label'=>'更新该视频', 'url'=>array('update', 'id'=>$model->video_id)),
	array('label'=>'删除该视频', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->video_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>更新短视频 #<?php echo $model->video_id; ?> <?php echo $model->video_title; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>