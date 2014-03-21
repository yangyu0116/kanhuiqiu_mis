<?php
    if ($view=='list'):

        $site = array(
            '9' => array('胥渡吧', '郑云'),
            '119' => array('十万个冷笑话', '飞碟说', '老湿', '暴走大事件'),
            '2' => array('刘咚咚', '淮秀帮', '胡狼', '暴走漫画'),
        );
        $subchannel = '短视频节目';
        echo "<h2>$subchannel</h2>";
        foreach ($site as $site_id=>$tags):
            echo '<h3>'.Site::model()->findByPk($site_id)->site_py.'</h3>';
            echo '<ul>';
            foreach ($tags as $tag):
                echo '<li>';
                echo CHtml::link($tag, $this->createUrl('test/siteSubmission', array('v'=>'view','site_id'=>$site_id,'subchannel'=>$subchannel,'tag'=>$tag)), array('target'=>'_blank'));
                echo '（'.$this->countSiteSubmission($site_id, $subchannel, $tag).'）';
                echo '</li>';
            endforeach;
            echo '</ul>';
        endforeach;

        $site = array(
            '6' => array('娱乐Everyday', '乐视星宾乐', '星月私房话', '乐视大牌党', '乐视风向标', '体育早班车', '中超故事会', '中超大爆炸'),
            '1' => array('娱乐猛回头', '电视剧有戏', '环球影讯', '头号人物', '青春那些事儿', '时尚爆米花', '音乐不要停', '街拍瞬间', '帕帕帮', '以德服人', '综艺大嘴巴', '笑霸来了', '圈里圈外'),
            '2' => array('酷6星客厅', '读人'),
        );
        $subchannel = '自制剧';
        echo "<h2>$subchannel</h2>";
        foreach ($site as $site_id=>$tags):
            echo '<h3>'.Site::model()->findByPk($site_id)->site_py.'</h3>';
            echo '<ul>';
            foreach ($tags as $tag):
                echo '<li>';
                echo CHtml::link($tag, $this->createUrl('test/siteSubmission', array('v'=>'view','site_id'=>$site_id,'subchannel'=>$subchannel,'tag'=>$tag)), array('target'=>'_blank'));
                echo '（'.$this->countSiteSubmission($site_id, $subchannel, $tag).'）';
                echo '</li>';
            endforeach;
            echo '</ul>';
        endforeach;

    endif;
?>

<?php
    if ($view=='view'):
        $model = new ShortVideoMeta();
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'short-video-meta-grid',
            'dataProvider' => $dp,
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
                    'name' => 'tags',
                    'htmlOptions' => array(
                        'width' => '6%',
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
            ),
        ));
    endif;
?>