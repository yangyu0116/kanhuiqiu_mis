<?php
$this->menu = array(
    array('label' => '添加次分类', 'url' => array('create')),
    array('label' => '修改该次分类', 'url' => array('update', 'id' => $model->id)),
    array('label' => '删除该次分类', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => '确定要删除该基因吗？')),
    array('label' => '管理次分类', 'url' => array('admin')),
);
?>

<h1>查看次分类 "<?php echo $model->title_chs; ?>"</h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'level',
        array(
            'name' => 'father_id',
            'value' => isset($model->channel) ? $model->channel->title_chs : 'N/A',
        ),
        'title_chs',
        'title_eng',
        'hot_value',
        'count',
        array(
            'name' => 'add_time',
            'value' => date("Y-m-d H:i:s", $model->add_time),
        ),
        array(
            'name' => 'update_time',
            'value' => date("Y-m-d H:i:s", $model->update_time),
        ),
    ),
));
?>

<br/><br/>
<h3>次分类对应关键字</h3>

<?php
echo CHtml::link('添加关键字', $this->createUrl('genomeKeyword/create'), array('target'=>'_blank'));
echo "<br/>";
$genomeKeyword = new GenomeKeyword();
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'genome-keyword-grid',
    'dataProvider' => $genomeKeyword->searchByGenomeId($model->id),
    'filter' => $genomeKeyword,
    'columns' => array(
        'id',
        'genome_id',
        'keyword',
        'tokenize_type',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>

<br/><br/>
<h3>对应视频（主分类下）</h3>

<?php
$shortVideoMeta = new ShortVideoMeta();
$this->renderPartial('_genome_video_grid', array(
    'dataProvider' => $shortVideoMeta->searchByGenomeAndChannelId($model->id, $model->father_id),
    'filter' => $shortVideoMeta,
));
?>

<br/><br/>
<h3>对应视频（全部）</h3>

<?php
$shortVideoMeta = new ShortVideoMeta();
$this->renderPartial('_genome_video_grid', array(
    'dataProvider' => $shortVideoMeta->searchByGenomeId($model->id),
    'filter' => $shortVideoMeta,
));
?>
