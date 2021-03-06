<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#video-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>所有短视频</h1>

<?php echo CHtml::link('高级搜索', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php

$img_not_processed = Yii::app()->db->createCommand("select count(*) from tbl_video")->queryScalar();

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'video-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'ajaxUpdate' => false,
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array(
                'width' => '1%',
            ),
        ),
		array(
            'name' => 'title',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
		/*
		array(
            'name' => 'url',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
		*/
		array(
                'header' => '图片',
            'class' => 'CLinkColumn',
            'labelExpression' => 'CHtml::image($data->pic)',
            'urlExpression' => 'Yii::app()->createUrl("video/view", array("id"=>$data->id))',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '1%',
            ),
        ),
				array(
            'name' => 'addtime',
            'htmlOptions' => array(
                'width' => '6%',
            ),
        ),
		/*
        array(
            'name' => 'channel_id',
            'value' => 'isset($data->channel)?$data->channel->title_chs:"无"',
            'filter' => CHtml::listData(Channel::model()->findAll(), 'channel_id', 'title_chs'),
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'site_id',
            'value' => '$data->site->site_py',
            'filter' => CHtml::listData(Site::model()->findAll(), 'site_id', 'site_py'),
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'source',
            'htmlOptions' => array(
                'width' => '3%',
            ),
        ),
        array(
            'header' => '短视频标题',
            'class' => 'CLinkColumn',
            'labelExpression' => '$data->title',
            'urlExpression' => '$data->source_detail_link',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '20%',
            ),            
        ),
        array(
            'header' => '图片',
            'class' => 'CLinkColumn',
            'labelExpression' => 'CHtml::image($data->limg_url)',
            'urlExpression' => 'Yii::app()->createUrl("shortVideoMeta/view", array("id"=>$data->id))',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'duration',
            'htmlOptions' => array(
                'width' => '4%',
            ),
        ),
        array(
            'name' => 'pub_date',
            'htmlOptions' => array(
                'width' => '7%',
            ),
        ),
        array(
            'name' => 'play_nums',
            'htmlOptions' => array(
                'width' => '7%',
            ),
        ),
        array(
            'name' => 'play_nums_increment',
            'header' => '新增播放次数',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'final_score',
            'htmlOptions' => array(
                'width' => '6%',
            ),
        ),
        array(
            'name' => 'flag',
            'header' => '图片',
            'htmlOptions' => array(
                'width' => '2%',
            ),
        ),
        array(
            'name' => 'update_time',
            'value' => 'date("Y-m-d H:i:s", $data->update_time)',
            'htmlOptions' => array(
                'width' => '8%',
            ),
        ),
        array(
            'name' => 'add_time',
            'value' => 'date("Y-m-d H:i:s", $data->add_time)',
            'htmlOptions' => array(
                'width' => '8%',
            ),
        ),
		*/
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array(
                'width' => '6%',
            ),
        ),
    ),
));
?>
