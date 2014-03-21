<?php

/**
 * This is the model class for table "tbl_genome_keyword".
 *
 * The followings are the available columns in table 'tbl_genome_keyword':
 * @property integer $id
 * @property integer $genome_id
 * @property string $keyword
 * @property string $tokenize_type
 */
class GenomeKeyword extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_genome_keyword';
    }

    public function rules() {
        return array(
            array('keyword', 'required'),
            array('genome_id', 'numerical', 'integerOnly' => true),
            array('keyword', 'length', 'max' => 20),
            array('tokenize_type', 'length', 'max' => 10),
            array('id, genome_id, keyword, tokenize_type', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'genome' => array(self::BELONGS_TO, 'Genome', 'genome_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'genome_id' => '基因ID',
            'keyword' => '关键字',
            'tokenize_type' => '关键字类型',
        );
    }

    public function getVideoId() {
        $keywords = array();
        $arrLogicAnd = explode('&&', $this->keyword);
        foreach ($arrLogicAnd as $logicAnd) {
            $keywords[] = explode('||', $logicAnd);
        }

        // logic OR operation
        $vidIntersect = array();
        foreach ($keywords as $val) {
            $vid = array();
            foreach ($val as $v) {
                $ret = Yii::app()->db->createCommand("select video_id from tbl_video_tokenize where keyword='" . $v . "'")->queryColumn();
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
        $criteria->compare('genome_id', $this->genome_id);
        $criteria->compare('keyword', $this->keyword);
        $criteria->compare('tokenize_type', $this->tokenize_type);

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

        $criteria->compare('id', $this->id);
        $criteria->compare('genome_id', $id);
        $criteria->compare('keyword', $this->keyword);
        $criteria->compare('tokenize_type', $this->tokenize_type);

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