<h1>所有分词结果</h1>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'video-tokenize-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'ajaxUpdate' => false,
    'columns' => array(
        array(
            'name' => 'video_id',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'header' => '站点',
            'value' => 'isset($data->video->site->site_py) ? $data->video->site->site_py : "N/A"',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'channel.title_chs',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'header' => '短视频标题',
            'class' => 'CLinkColumn',
            'labelExpression' => 'isset($data->video) ? $data->video->video_title : "N/A"',
            'urlExpression' => 'isset($data->video) ? Yii::app()->createUrl("shortVideoMeta/view", array("id"=>$data->video->video_id)) : "N/A"',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '35%',
            ),            
        ),
        array(
            'name' => 'video.pub_date',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'video.play_nums',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'video.final_score',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),        
        array(
            'name' => 'keyword',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'tokenize_type',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
    ),
));
?>
