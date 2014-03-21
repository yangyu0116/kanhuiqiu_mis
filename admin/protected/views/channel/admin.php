<?php
$this->menu = array(
    array('label' => '添加主分类', 'url' => array('create')),
);
?>

<h1>管理主分类</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'channel-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'channel_id',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'title_chs',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'title_py',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'header' => '视频数',
            'value' => '$data->videoCount',
            'htmlOptions' => array(
                'width' => '15%',
            ),
        ),
        array(
            'name' => 'add_time',
            'value' => 'date("Y-m-d H:i:s", $data->add_time)',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
    ),
));
?>
