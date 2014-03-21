<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'short-video-meta-grid',
    'dataProvider' => $dp,
    'columns' => array(
        array(
            'name' => 'video_id',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'header' => '短视频标题',
            'class' => 'CLinkColumn',
            'labelExpression' => '$data->video_title',
            'urlExpression' => '$data->source_detail_link',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '40%',
            ),            
        ),
        array(
            'name' => 'channel_id',
            'value' => '$data->channel->title_chs',
            'filter' => CHtml::listData(Channel::model()->findAll(), 'channel_id', 'title_chs'),
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'site_id',
            'value' => '$data->site->site_name',
            'filter' => CHtml::listData(Site::model()->findAll(), 'site_id', 'site_name'),
            'htmlOptions' => array(
                'width' => '10%',
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
    ),
));
?>
