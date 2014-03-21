
<?php


$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'video_title',
            'header' => '标题',
            'htmlOptions' => array(
                'width' => '36%',
            )
        ),
        array(
            'name' => 'site',
            'header' => '发布站点',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'pub_date',
            'header' => '发布时间',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'play_nums',
            'header' => '播放次数',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'normalized_play_nums',
            'header' => '归一化播放次数',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'incremental_play_nums',
            'header' => '新增播放次数',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'normalized_incremental_play_nums',
            'header' => '归一化新增播放次数',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'score_1',
            'header' => '热度1',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'score_2',
            'header' => '热度2',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
        array(
            'name' => 'final_score',
            'header' => '综合热度',
            'htmlOptions' => array(
                'width' => '8%',
            )
        ),
    ),
));
?>