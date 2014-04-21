<?php
$this->menu=array(
	array('label'=>'管理基因关键字关系', 'url'=>array('admin')),
);
?>

<h1>添加基因关键字关系</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>