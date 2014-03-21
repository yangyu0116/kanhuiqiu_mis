<?php

class Channel extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_channel';
    }

    public function rules() {
        return array(
            array('add_time', 'required'),
            array('add_time', 'numerical', 'integerOnly' => true),
            array('title_chs', 'length', 'max' => 20),
            array('title_py', 'length', 'max' => 255),
            array('title_chs', 'unique'),
            array('title_py', 'unique'),
            array('channel_id, title_chs, title_py, add_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'video' => array(self::HAS_MANY, 'ShortVideoMeta', 'channel_id', 'order' => 'final_score desc'),
            'genome' => array(self::HAS_MANY, 'Genome', 'father_id', 'order' => 'hot_value desc'),
            'videoCount' => array(self::STAT, 'ShortVideoMeta', 'channel_id'),
        );
    }

    public function beforeValidate() {
        parent::beforeValidate();
        if ($this->isNewRecord) {
            $this->add_time = time();
        }
        return true;
    }

    public function attributeLabels() {
        return array(
            'channel_id' => '频道ID',
            'title_chs' => '中文名',
            'title_py' => '拼音',
            'add_time' => '创建时间',
        );
    }

    public function updateHotValue() {
        $this->hot_value = 1 + Yii::app()->db->createCommand("select avg(final_score) from tbl_short_video_meta where channel_id={$this->channel_id}")->queryScalar();
        $this->save();
    }

    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('channel_id', $this->channel_id);
        $criteria->compare('title_chs', $this->title_chs, true);
        $criteria->compare('title_py', $this->title_py, true);
        $criteria->compare('add_time', $this->add_time);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 25,
                    ),
                ));
    }

}