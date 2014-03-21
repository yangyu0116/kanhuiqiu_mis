<?php
$this->layout = '//layouts/column2';

$this->menu=array(
	array('label'=>'添加短视频', 'url'=>array('create')),
	array('label'=>'更新该视频', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'查看站点API', 'url'=>array('siteAPI', 'url'=>$model->source_detail_link), 'linkOptions'=>array('target'=>'_blank')),
    array('label'=>'重新处理视频图片', 'url'=>array('updateFlag', 'id'=>$model->id)),
	array('label'=>'删除该视频', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>短视频 #<?php echo $model->id; ?> <?php echo $model->video_title; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		/*
		'simg_url',
		'pub_date',
		'site_id',
		'tags',
		'source_detail_link',
		'source_list_link',
		'swf_link',
		'duration',
		'channel_id',
		'flag',
		'limg_url',
		'mimg_url',
		'comments_num',
        array(
            'name' => 'add_time',
            'value' => date('Y-m-d H:i:s', $model->add_time),
        ),
        'play_nums',
        array(
            'name' => 'update_time',
            'value' => date('Y-m-d H:i:s', $model->update_time),
        ),
        'last_play_nums',
        array(
            'name' => 'last_update_time',
            'value' => $model->last_update_time ? date('Y-m-d H:i:s', $model->last_update_time) : '暂无',
        ),
        'final_score',
		*/
	),
)); ?>

<br/>
<br/>

<?php
    echo "<br/><h4>原始图片</h4>";
    echo CHtml::image($model->simg_url);
    if ($model->flag && $model->limg_url) {
        echo "<br/><br/><h4>处理后图片</h4>";
        echo CHtml::image($model->limg_url, 'image');
    }
?>

<br/>
<br/>
<br/>
<h3>分词结果</h3>

<?php
$tokenize = new VideoTokenize();
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'video-tokenize-grid',
    'dataProvider' => $tokenize->searchByVideoId($model->id),
    'ajaxUpdate' => false,
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),      
        array(
            'name' => 'keyword',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
        array(
            'name' => 'tokenize_type',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
    ),
));
?>
