<?php
$this->menu=array(
	array('label'=>'添加基因关键字关系', 'url'=>array('create')),
	array('label'=>'管理基因关键字关系', 'url'=>array('admin')),
);
?>

<h1>更新基因关键字关系 <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>