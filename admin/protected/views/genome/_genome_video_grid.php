<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'short-video-meta-grid',
    'dataProvider' => $dataProvider,
    'filter' => $filter,
    'columns' => array(
        array(
            'name' => 'video_id',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'source',
            'header' => '来源',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'header' => '短视频标题',
            'class' => 'CLinkColumn',
            'labelExpression' => '$data->video_title',
            'urlExpression' => 'Yii::app()->createUrl("shortVideoMeta/view", array("id"=>$data->video_id))',
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
            'urlExpression' => 'Yii::app()->createUrl("shortVideoMeta/view", array("id"=>$data->video_id))',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
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
            'name' => 'pub_date',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'play_nums',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'final_score',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'update_time',
            'value' => 'date("Y-m-d H:i:s", $data->update_time)',
            'htmlOptions' => array(
                'width' => '15%',
            ),
        ),
    ),
));