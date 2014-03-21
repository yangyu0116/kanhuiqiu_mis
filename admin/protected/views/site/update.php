<?php
$this->menu=array(
    array('label'=>'添加站点', 'url'=>array('create')),
    array('label'=>'管理站点', 'url'=>array('admin')),
);
?>

    <h1>修改站点 <?php echo $model->site_name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>