<?php

/**
 * This is the model class for table "tbl_genome".
 *
 * The followings are the available columns in table 'tbl_genome':
 * @property integer $id
 * @property integer $level
 * @property integer $father_id
 * @property string $title_chs
 * @property string $title_eng
 * @property integer $add_time
 * @property integer $update_time
 * @property integer $hot_value
 */
class Genome extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_genome';
    }

    public function rules() {
        return array(
            array('title_chs', 'required'),
//            array('title_chs', 'unique'),
            array('level, father_id, add_time, update_time, hot_value, count', 'numerical', 'integerOnly' => true),
            array('title_chs', 'length', 'max' => 20),
            array('title_eng', 'length', 'max' => 255),
            array('id, level, father_id, title_chs, title_eng, add_time, update_time, hot_value', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'keywords' => array(self::HAS_MANY, 'GenomeKeyword', 'genome_id'),
            'channel' => array(self::BELONGS_TO, 'Channel', 'father_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'level' => '层级',
            'father_id' => '主分类',
            'title_chs' => '次分类',
            'title_eng' => '英文名',
            'add_time' => '添加时间',
            'update_time' => '更新时间',
            'hot_value' => '热度值',
            'count' => '视频数量',
        );
    }
    
    public function beforeValidate() {
        parent::beforeValidate();
        if ($this->isNewRecord) {
            $this->add_time = time();
            $this->update_time = $this->add_time;
        }
        else {
            $this->update_time = time();
        }
        return true;
    }
    
    public function afterDelete() {
        parent::afterDelete();
        // delete corresponding keywords
        GenomeKeyword::model()->deleteAll("genome_id=".$this->id);
        // add as a stopword
        $model = new Stopword();
        $model->stopword = $this->title_chs;
        $model->save();
    }
    
    public function afterSave() {
        parent::afterSave();
        // 如果该基因没有映射到任何关键字，则生成一个关键字对应关系
        if ($this->isNewRecord) {
            $genomeKeyword = GenomeKeyword::model()->find("keyword=':k'", array(''=>$this->title_chs));
            if (!$genomeKeyword) {
                $genomeKeyword = new GenomeKeyword();
                $genomeKeyword->keyword = $this->title_chs;
                $genomeKeyword->genome_id = $this->id;
                $genomeKeyword->save();
            }
        }
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('level', $this->level);
        $criteria->compare('father_id', $this->father_id);
        $criteria->compare('title_chs', $this->title_chs);
        $criteria->compare('title_eng', $this->title_eng);
        $criteria->compare('add_time', $this->add_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('hot_value', $this->hot_value);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'hot_value desc',
                    ),            
                ));
    }

    public function getVideoId($underChannel=1, $limit=500, $site=array()) {
        $vid = array();

        foreach ($this->keywords as $k) {
            $ret = $k->getVideoId();
            $vid = array_merge($vid, $ret);
        }

        if ($underChannel) {
            $cvid = Yii::app()->db->createCommand("select video_id from tbl_short_video_meta where channel_id={$this->father_id}")->queryColumn();
            $vid = array_intersect($vid, $cvid);
        }

        if (count($vid)>0) {
            if ($site) {
                $sql = "select video_id from tbl_short_video_meta where video_id in (" . implode(',',$vid) . ") and flag=1 and site_id in (".implode(",", $site).") order by final_score desc limit $limit";
            }
            else {
                $sql = "select video_id from tbl_short_video_meta where video_id in (" . implode(',',$vid) . ") and flag=1 order by final_score desc limit $limit";
            }
            return Yii::app()->db->createCommand($sql)->queryColumn();
        }

        return array();
    }

}