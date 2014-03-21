<?php
$this->menu=array(
    array('label'=>'添加主分类', 'url'=>array('create')),
    array('label'=>'管理主分类', 'url'=>array('admin')),
);
?>

    <h1>修改主分类 <?php echo $model->title_chs; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>