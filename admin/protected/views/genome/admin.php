<?php
$this->menu = array(
    array('label' => '添加次分类', 'url' => array('create')),
);
?>

<h1>管理次分类</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'genome-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'level',
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'father_id',
            'value' => 'isset($data->channel)?$data->channel->title_chs:"其他"',
            'filter' => CHtml::listData(Channel::model()->findAll(), 'channel_id', 'title_chs'),
            'htmlOptions' => array(
                'width' => '5%',
            ),
        ),
        array(
            'name' => 'title_chs',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'count',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'hot_value',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'header' => '关键字',
            'value' => 'count($data->keywords)',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'add_time',
            'value' => 'date("Y-m-d H:i:s", $data->add_time)',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'update_time',
            'value' => 'date("Y-m-d H:i:s", $data->update_time)',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
    ),
));
?>
