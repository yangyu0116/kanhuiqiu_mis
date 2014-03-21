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
class ShortVideoMeta extends CActiveRecord {

    public $play_nums_increment;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_video';
    }

    public function rules() {
        return array(
            array('id, title, keywords, url, urlmd5, pic, year, date, addtime, site'),
            //array('source, pub_date, play_nums, site_id, duration, channel_id, add_time, flag, final_score, comments_num, update_time', 'numerical', 'integerOnly' => true),
            //array('video_title', 'length', 'max' => 300),
            //array('simg_url, tags, limg_url, mimg_url', 'length', 'max' => 500),
            //array('source_detail_link, source_list_link, swf_link', 'length', 'max' => 200),
            //array('video_id, video_title, simg_url, pub_date, play_nums, site_id, tags, source_detail_link, source_list_link, swf_link, duration, channel_id, add_time, flag, final_score, limg_url, mimg_url, comments_num, update_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'channel' => array(self::BELONGS_TO, 'Channel', 'channel_id'),
            'site' => array(self::BELONGS_TO, 'Site', 'site_id'),
            'keywords' => array(self::HAS_MANY, 'VideoTokenize', 'video_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'keywords' => '分词',
            'url' => '视频url',
            'urlmd5' => 'urlmd5',
            'pic' => '图片url',
            'year' => '年份',
            'date' => '日期',
            'addtime' => '入库时间',
            'site' => '站点'
        );
    }

    public function afterDelete() {
        parent::afterDelete();
        // delete corresponding keywords
        VideoTokenize::model()->deleteAll("video_id=".$this->video_id);
    }

    public function afterFind() {
        if ($this->update_time>0 && $this->last_update_time>0 && $this->update_time>$this->last_update_time && $this->play_nums>$this->last_play_nums) {
            $this->play_nums_increment = intval(($this->play_nums - $this->last_play_nums) / ($this->update_time - $this->last_update_time) * 3600 );
        }
        else {
            $this->play_nums_increment = 0;
        }

    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->video_id);
        $criteria->compare('title', $this->video_title, true);
        $criteria->compare('keywords', $this->simg_url, true);
        $criteria->compare('url', $this->pub_date);
        $criteria->compare('urlmd5', $this->play_nums);
        $criteria->compare('pic', $this->site_id);
        $criteria->compare('year', $this->source);
        $criteria->compare('date', $this->tags, true);
        $criteria->compare('addtime', $this->source_detail_link, true);
        $criteria->compare('site', $this->source_list_link, true);

        if (intval($this->add_time)) {
            $criteria->addBetweenCondition('add_time', strtotime($this->add_time), strtotime($this->add_time)+24*3600);
        }

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'final_score desc',
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
        $criteria->addInCondition('video_id', $vid);
        $criteria->addCondition('flag=1');

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 25,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'final_score desc,add_time desc',
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

        $cvid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where channel_id=$cid")->queryColumn();

        $vid = array_intersect($vid, $cvid);

        $criteria = new CDbCriteria;
        $criteria->addInCondition('video_id', $vid);
        $criteria->addCondition('flag=1');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
            'sort' => array(
                'defaultOrder' => 'final_score desc,add_time desc',
            ),
        ));
    }


}