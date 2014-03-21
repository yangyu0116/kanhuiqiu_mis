<?php
/* @var $this ShortVideoMetaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Short Video Metas',
);

$this->menu=array(
	array('label'=>'Create ShortVideoMeta', 'url'=>array('create')),
	array('label'=>'Manage ShortVideoMeta', 'url'=>array('admin')),
);
?>

<h1>Short Video Metas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
