<?php

/**
 * This is the model class for table "tbl_video_tokenize".
 *
 * The followings are the available columns in table 'tbl_video_tokenize':
 * @property integer $video_id
 * @property string $keyword
 * @property integer $channel_id
 * @property string $words_attr
 * @property integer $tokenize_id
 */
class VideoTokenize extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_video_tokenize';
    }

    public function rules() {
        return array(
            array('video_id, words_tokenize, channel_id, words_attr', 'required'),
            array('video_id, channel_id', 'numerical', 'integerOnly' => true),
            array('keyword', 'length', 'max' => 20),
            array('words_attr', 'length', 'max' => 5),
            array('video_id, words_tokenize, channel_id, words_attr, tokenize_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'channel' => array(self::BELONGS_TO, 'Channel', 'channel_id'),
            'video' => array(self::BELONGS_TO, 'ShortVideoMeta', 'video_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'video_id' => '短视频ID',
            'keyword' => '关键字',
            'channel_id' => '频道ID',
            'words_attr' => '词性',
            'tokenize_id' => '主键',
            'video.video_title' => '短视频标题',
            'video.pub_date' => '发布日期',
            'video.play_num' => '播放次数',
            'video.final_score' => '热度值',
            'channel.title_chs' => '频道',
        );
    }

    public function search() {

        $criteria = new CDbCriteria;
        $criteria->compare('keyword', $this->keyword);
        $criteria->compare('video_id', $this->video_id);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'tokenize_id desc',
                    ),
                ));
    }

    public function searchByVideoId($id) {

        $criteria = new CDbCriteria;
        $criteria->compare('video_id', $id);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'tokenize_id desc',
                    ),
                ));
    }
    
}