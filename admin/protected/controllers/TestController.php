<?php

class TestController extends Controller {
    public $layout = '//layouts/column1';
    
    public function actionSiteSubmission($v='list', $site_id=0, $subchannel='', $tag='') {
        switch ($v) {
            case 'list':
                $this->render('siteSubmission', array('view'=>$v));
                break;
            case 'view':
                $vid = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='subchannel' and keyword='$subchannel'")->queryColumn();
                if ($tag) {
                    $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='tag' and keyword='$tag'")->queryColumn();
                    $vid = array_intersect($vid, $ret);
                }
                if ($vid && $site_id) {
                    $vid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where site_id=$site_id and video_id in (".implode(",",$vid).")")->queryColumn();
                }
                $criteria = new CDbCriteria;
                $criteria->addInCondition('video_id', $vid);
//                $criteria->addCondition('flag=1');
                $dp = new CActiveDataProvider('ShortVideoMeta', array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 25,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'final_score desc,add_time desc',
                    ),
                ));
                $this->render('siteSubmission', array(
                    'view'=>$v,
                    'dp'=>$dp,
                ));
                break;
            default:
                break;
        }
    }

    public function countSiteSubmission($site_id=0, $subchannel='', $tag='') {
        $vid = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='subchannel' and keyword='$subchannel'")->queryColumn();
        if ($vid && $tag) {
            $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='tag' and keyword='$tag'")->queryColumn();
            $vid = array_intersect($vid, $ret);
        }
        if ($vid && $site_id) {
            $ret = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where site_id=$site_id and video_id in (".implode(",",$vid).")")->queryColumn();
            return count($ret);
        }
        else {
            return 0;
        }
    }

    public function actionTag($subchannel='短视频节目') {
        $vid = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where tokenize_type='subchannel' and keyword='$subchannel'")->queryColumn();
        foreach ($vid as $v) {
            $row = Yii::app()->db->createCommand("select video_id,channel_id,tags from tbl_short_video_meta where video_id=$v")->queryRow();
            $count = Yii::app()->db->createCommand("select count(*) from tbl_video_tokenize where video_id=$v and keyword='".$row['tags']."'")->queryScalar();
            if ($count==0) {
                Yii::app()->db->createCommand()->insert('tbl_video_tokenize', array(
                    'video_id' => $row['video_id'],
                    'keyword' => $row['tags'],
                    'channel_id' => $row['channel_id'],
                    'tokenize_type' => 'tag',
                ));
            }
        }

    }

}

?>