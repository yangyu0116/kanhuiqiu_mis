<?php

/**
 * This is the model class for table "tbl_site".
 *
 * The followings are the available columns in table 'tbl_site':
 * @property integer $site_id
 * @property string $site_name
 * @property integer $site_weight
 * @property string $site_py
 * @property integer $add_time
 *
 * The followings are the available model relations:
 * @property ShortVideoMeta[] $shortVideoMetas
 */
class Site extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_site';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('add_time', 'required'),
			array('site_weight, add_time', 'numerical', 'integerOnly'=>true),
            array('site_name', 'unique'),
			array('site_name', 'length', 'max'=>30),
			array('site_py', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('site_id, site_name, site_weight, site_py, add_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'shortVideoMetas' => array(self::HAS_MANY, 'ShortVideoMeta', 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'site_id' => '站点ID',
			'site_name' => '站点中文名',
			'site_weight' => '站点权重',
			'site_py' => '站点英文名',
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

    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('site_name',$this->site_name,true);
		$criteria->compare('site_weight',$this->site_weight);
		$criteria->compare('site_py',$this->site_py,true);
		$criteria->compare('add_time',$this->add_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 25,
            ),
		));
	}
}