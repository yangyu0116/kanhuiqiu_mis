<?php
$this->menu=array(
	array('label'=>'管理关键字关系', 'url'=>array('admin')),
);
?>

<h1>添加关键字关系</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>