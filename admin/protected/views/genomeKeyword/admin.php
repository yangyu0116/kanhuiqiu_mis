<?php
$this->menu = array(
    array('label' => '添加基因关键字关系', 'url' => array('create')),
);
?>

<h1>管理基因关键字关系</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'genome-keyword-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        'genome_id',
        array(
            'value' => 'isset($data->genome) ? $data->genome->title_chs : "N/A"',
            'htmlOptions' => array(
                'width' => '25%',
            ),
        ), 
        'keyword',
        'tokenize_type',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
