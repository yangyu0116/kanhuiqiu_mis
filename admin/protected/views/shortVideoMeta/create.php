<?php
$this->layout = '//layouts/column2';

$this->menu=array(
	array('label'=>'管理短视频', 'url'=>array('admin')),
);
?>

<h1>添加短视频</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>