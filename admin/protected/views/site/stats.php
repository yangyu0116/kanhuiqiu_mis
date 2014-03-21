<h1>各站点、各频道视频数量</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'user',
    'dataProvider' => $dp,
));
?>
