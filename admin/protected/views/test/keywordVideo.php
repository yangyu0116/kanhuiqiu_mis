<?php
echo "<h1>所有'$q'短视频</h1>";
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'short-video-meta-grid',
            'dataProvider' => $dp,
            'columns' => array(
                array(
                    'name' => 'video_id',
                    'htmlOptions' => array(
                        'width' => '5%',
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
                    'name' => 'channel.title_py',
                    'htmlOptions' => array(
                        'width' => '5%',
                    ),
                ),
                array(
                    'name' => 'site.site_py',
                    'htmlOptions' => array(
                        'width' => '5%',
                    ),
                ),
                array(
                    'name' => 'pub_date',
                    'htmlOptions' => array(
                        'width' => '8%',
                    ),
                ),
                array(
                    'name' => 'play_nums',
                    'htmlOptions' => array(
                        'width' => '8%',
                    ),
                ),
                array(
                    'name' => 'final_score',
                    'htmlOptions' => array(
                        'width' => '8%',
                    ),
                ),
                array(
                    'name' => 'update_time',
                    'value' => 'date("Ymd",$data->update_time)',
                    'htmlOptions' => array(
                        'width' => '8%',
                    ),
                ),
            ),
        ));
?>
