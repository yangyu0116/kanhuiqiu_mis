<?php

$dp = new CArrayDataProvider($data, array(
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'top-keywords-grid',
    'dataProvider' => $dp,
    'columns' => array(
        array(
            'name' => 'keyword',
            'header' => '关键字',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'count',
            'header' => '出现次数',
            'htmlOptions' => array(
                'width' => '15%',
            ),
        ),
        array(
            'name' => 'is_genome',
            'header' => '是否基因',
            'value' => '$data["is_genome"] ? "<font style=color:green>是</font>" : "<font style=color:red>否</font>"',
            'type' => 'raw',
            'htmlOptions' => array(
                'width' => '15%',
            ),
        ),        
        array(
            'header' => '添加为基因',
            'class' => 'CLinkColumn',
            'label' => '添加为基因',
            'urlExpression' => 'Yii::app()->createUrl("genome/create",array("title_chs"=>$data["keyword"]))',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '15%',
            ),
        ),
        array(
            'header' => '添加为停用词',
            'class' => 'CLinkColumn',
            'label' => '添加为停用词',
            'urlExpression' => 'Yii::app()->createUrl("stopword/create",array("stopword"=>$data["keyword"]))',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '15%',
            ),
        ),
        array(
            'header' => '查看对应视频',
            'class' => 'CLinkColumn',
            'label' => '查看对应视频',
            'urlExpression' => 'Yii::app()->createUrl("videoTokenize/admin",array("VideoTokenize[keyword]"=>$data["keyword"]))',
            'linkHtmlOptions' => array(
                'target' => '_blank'
            ),
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
    ),
));
?>
