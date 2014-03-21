<?php
echo "<h1>视频平均播放次数</h1>";

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'site-avg-play-num-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'site_id',
            'header' => '站点',
            'value' => 'isset($data->site)?$data->site->site_py:"N/A"',
            'filter' => CHtml::listData(Site::model()->findAll(), 'site_id', 'site_py'),
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
        array(
            'name' => 'channel_id',
            'header' => '频道',
            'value' => 'isset($data->channel)?$data->channel->title_chs:"全站"',
            'filter' => CHtml::listData(Channel::model()->findAll(), 'channel_id', 'title_chs'),
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),        
        array(
            'name' => 'avg_play_num',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
        array(
            'name' => 'update_time',
            'value' => 'date("Y-m-d H:i:s", $data->update_time)',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
    ),
));
?>
