<?php
$this->menu = array(
    array('label' => '新增停用词', 'url' => array('create')),
);
?>

<h1>管理停用词</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'stopword-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'stopword',
            'htmlOptions' => array(
                'width' => '40%',
            ),
        ),
        array(
            'name' => 'add_time',
            'value' => 'date("Y-m-d H:i:s",$data->add_time)',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'class' => 'CButtonColumn',
            'header' => '操作',
            'htmlOptions' => array(
                'width' => '20%',
            ),            
        ),
    ),
));
?>
