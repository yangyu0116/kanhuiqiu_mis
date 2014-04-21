<?php
$this->menu = array(
    array('label' => '添加基因关键字关系', 'url' => array('create')),
);
?>

<h1>管理基因关键字关系</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'word-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'word',
        array(
            'value' => 'isset($data->samewords) ? $data->samewords : "N/A"',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ), 
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
