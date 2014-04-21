<?php

/**
 * This is the model class for table "tbl_words".
 *
 * The followings are the available columns in table 'tbl_words':
 * @property integer $id
 * @property integer $id
 * @property string $word
 * @property string $samewords
 */
class Words extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_words';
    }

    public function rules() {
        return array(
            array('id', 'numerical', 'integerOnly' => true),
            array('word', 'length', 'max' => 10),
            array('samewords', 'length', 'max' => 100),
          //  array('id, word, samewords', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'genome' => array(self::BELONGS_TO, 'Genome', 'id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'word' => '关键字',
            'samewords' => '关键字类型',
        );
    }

    public function getVideoId() {
        $words = array();
        $arrLogicAnd = explode('&&', $this->word);
        foreach ($arrLogicAnd as $logicAnd) {
            $words[] = explode('||', $logicAnd);
        }

        // logic OR operation
        $vidIntersect = array();
        foreach ($words as $val) {
            $vid = array();
            foreach ($val as $v) {
                $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where word='" . $v . "'")->queryColumn();
                $vid = array_merge($vid, $ret);
            }
            $vidIntersect[] = $vid;
        }

        // logic AND operation
        $vidFinal = $vidIntersect[0];
        for ($i=1; $i<count($vidIntersect); $i++) {
            $vidFinal = array_intersect($vidFinal, $vidIntersect[$i]);
        }
        return $vidFinal;
    }

    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('word', $this->word);
        $criteria->compare('samewords', $this->samewords);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'id desc',
                    ),
                ));
    }
    
    public function searchByGenomeId($id) {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $id);
        $criteria->compare('word', $this->word);
        $criteria->compare('samewords', $this->samewords);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'id desc',
                    ),
                ));
    }    

}