<?php

/**
 * This is the model class for table "tbl_short_video_meta".
 *
 * The followings are the available columns in table 'tbl_short_video_meta':
 * @property integer $video_id
 * @property string $video_title
 * @property string $simg_url
 * @property integer $pub_date
 * @property integer $play_nums
 * @property integer $last_play_nums
 * @property integer $site_id
 * @property string $tags
 * @property string $source_detail_link
 * @property string $source_list_link
 * @property string $swf_link
 * @property integer $duration
 * @property integer $channel_id
 * @property integer $add_time
 * @property integer $flag
 * @property integer $final_score
 * @property string $limg_url
 * @property string $mimg_url
 * @property integer $comments_num
 * @property integer $update_time
 * @property integer $last_update_time
 * @property integer $source
 *
 * The followings are the available model relations:
 * @property Channel $channel
 * @property Site $site
 */
class Video extends CActiveRecord {

    public $play_nums_increment;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_video';
    }

    public function rules() {
        return array(
            //array('id, title, url, urlmd5, pic, year, date, addtime, site'),
            //array('source, pub_date, play_nums, site_id, duration, channel_id, add_time, flag, final_score, comments_num, update_time', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 300),
            array('createtime', 'default', 'value' => date('Y-m-d H:i:s')),
            //array('source_detail_link, source_list_link, swf_link', 'length', 'max' => 200),
            //array('video_id, video_title, simg_url, pub_date, play_nums, site_id, tags, source_detail_link, source_list_link, swf_link, duration, channel_id, add_time, flag, final_score, limg_url, mimg_url, comments_num, update_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            //'channel' => array(self::BELONGS_TO, 'Channel', 'channel_id'),
            //'site' => array(self::BELONGS_TO, 'Site', 'site_id')
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '标题',
			'type' => '类型',
            'url' => '视频url',
            'urlmd5' => 'urlmd5',
            'pic' => '图片url',
			'createtime' => '发布时间',
            'addtime' => '入库时间',
            'site' => '站点'
        );
    }

    public function afterDelete() {
        parent::afterDelete();
        // delete corresponding keywords
        VideoTokenize::model()->deleteAll("id=".$this->id);
    }

    public function afterFind() {
		/*
        if ($this->update_time>0 && $this->last_update_time>0 && $this->update_time>$this->last_update_time && $this->play_nums>$this->last_play_nums) {
            $this->play_nums_increment = intval(($this->play_nums - $this->last_play_nums) / ($this->update_time - $this->last_update_time) * 3600 );
        }
        else {
            $this->play_nums_increment = 0;
        }
		*/
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('url', $this->url);
        $criteria->compare('urlmd5', $this->urlmd5);
        $criteria->compare('pic', $this->pic);
        $criteria->compare('addtime', $this->addtime, true);
        $criteria->compare('site', $this->site, true);

        if (intval($this->addtime)) {
            $criteria->addBetweenCondition('addtime', strtotime($this->addtime), strtotime($this->addtime)+24*3600);
        }

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 20,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'id desc',
                    ),
                ));
    }

    public function searchByGenomeId($id) {
        $vid = array();
        if ($genome = Genome::model()->findByPk($id)) {
            foreach ($genome->keywords as $k) {
                $ret = $k->getVideoId();
                $vid = array_merge($vid, $ret);
            }
        }
        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $vid);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 25,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'id desc',
                    ),
                ));
    }

    public function searchByGenomeAndChannelId($gid, $cid) {
        $vid = array();
        if ($genome = Genome::model()->findByPk($gid)) {
            foreach ($genome->keywords as $k) {
                $ret = $k->getVideoId();
                $vid = array_merge($vid, $ret);
            }
        }

        $cvid = Yii::app()->db->createCommand("select id from tbl_video")->queryColumn();

        $vid = array_intersect($vid, $cvid);

        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $vid);
        //$criteria->addCondition('flag=1');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort' => array(
                'defaultOrder' => 'id desc',
            ),
        ));
    }


}