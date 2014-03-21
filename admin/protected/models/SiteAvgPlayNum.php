<?php

/**
 * This is the model class for table "tbl_site_avg_play_num".
 *
 * The followings are the available columns in table 'tbl_site_avg_play_num':
 * @property integer $site_id
 * @property integer $channel_id
 * @property integer $avg_play_num
 * @property integer $update_time
 */
class SiteAvgPlayNum extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_site_avg_play_num';
    }

    public function rules() {
        return array(
            array('site_id, channel_id, update_time', 'required'),
            array('site_id, channel_id, avg_play_num, update_time', 'numerical', 'integerOnly' => true),
            array('site_id, channel_id, avg_play_num, update_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'site' => array(self::BELONGS_TO, 'Site', 'site_id'),
            'channel' => array(self::BELONGS_TO, 'Channel', 'channel_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'site_id' => '站点ID',
            'channel_id' => '频道ID',
            'avg_play_num' => '平均播放次数',
            'update_time' => '更新时间',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('site_id', $this->site_id);
        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('avg_play_num', $this->avg_play_num);
        $criteria->compare('update_time', $this->update_time);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 200,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'site_id asc',
                    ),
                ));
    }

}