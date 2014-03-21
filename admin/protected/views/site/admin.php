<?php
$this->menu = array(
    array('label' => '添加站点', 'url' => array('create')),
);
?>

<h1>管理站点</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'channel-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'site_id',
            'htmlOptions' => array(
                'width' => '10%',
            ),
        ),
        array(
            'name' => 'site_name',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'site_py',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'site_weight',
            'htmlOptions' => array(
                'width' => '20%',
            ),
        ),
        array(
            'name' => 'add_time',
            'value' => 'date("Y-m-d H:i:s", $data->add_time)',
            'htmlOptions' => array(
                'width' => '20%',
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
