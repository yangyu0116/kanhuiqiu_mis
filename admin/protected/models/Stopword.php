<?php

class Stopword extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'tbl_stopword';
    }

    public function rules() {
        return array(
            array('add_time', 'numerical', 'integerOnly' => true),
            array('stopword', 'required'),
            array('stopword', 'unique'),
            array('stopword', 'length', 'max' => 100),
            array('id, stopword, add_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'stopword' => '停用词',
            'add_time' => '添加时间',
        );
    }
    
    public function beforeValidate() {
        parent::beforeValidate();
        if ($this->isNewRecord) {
            $this->add_time = time();
        }
        return true;
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('stopword', $this->stopword);
        $criteria->compare('add_time', $this->add_time);

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