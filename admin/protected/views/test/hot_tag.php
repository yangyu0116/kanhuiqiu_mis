<?php

$channels = Channel::model()->findAll('hot_value>1');
foreach ($channels as $c) {
    $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where channel_id={$c->channel_id} order by final_score desc limit 1000")->queryColumn();
    if ($vid) {
        $cr = new CDbCriteria;
        $cr->addInCondition('video_id', $vid);
        $dp = new CActiveDataProvider('ShortVideoMeta', array(
                    'criteria' => $cr,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'final_score desc,add_time desc',
                    ),
                ));
        echo "<h1>$c->title_chs&nbsp;/&nbsp;热度$c->hot_value&nbsp;/&nbsp;视频数$dp->totalItemCount</h1>";
        $this->renderPartial('_hot_video_dp', array('dp'=>$dp));
    }
}

$genomes = SvideoGenome::model()->findAll('hot_value>1');
foreach ($genomes as $g) {
    $videoId = array();
    foreach ($g->keywords as $k) {
        $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where words_tokenize='" . $k->keyword . "'")->queryColumn();
        $videoId = array_merge($videoId, $ret);
    }
    $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where video_id in (" . implode(',', $videoId) . ") order by final_score desc limit 1000")->queryColumn();
    if ($vid) {
        $cr = new CDbCriteria;
        $cr->addInCondition('video_id', $vid);
        $dp = new CActiveDataProvider('ShortVideoMeta', array(
                    'criteria' => $cr,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'final_score desc,add_time desc',
                    ),
                ));
        echo "<h1>$g->title_chs&nbsp;/&nbsp;热度$g->hot_value&nbsp;/&nbsp;视频数$dp->totalItemCount</h1>";
        $this->renderPartial('_hot_video_dp', array('dp'=>$dp));
    }
}
?>
