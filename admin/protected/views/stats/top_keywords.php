<?php
echo "<h1>过去".$day."天热门关键字</h1>";

echo CHtml::form($this->createUrl("stats/topKeywords"), "GET");
echo "查看";
echo CHtml::textField("d");
echo "天内的热门关键字";
echo CHtml::submitButton("查看");
echo CHtml::endForm();

echo "<br/><br/>";

$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => array(
        '频道' => array(
            'id' => 'channel_1',
            'content' => $this->renderPartial('_list_top_keywords', array('data' => isset($results["channel"])?$results["channel"]:array()), true)
        ),
        '子频道' => array(
            'id' => 'subchannel_1',
            'content' => $this->renderPartial('_list_top_keywords', array('data' => isset($results["subchannel"])?$results["subchannel"]:array()), true)
        ),
        '其他关键字' => array(
            'id' => 'other_1',
            'content' => $this->renderPartial('_list_top_keywords', array('data' => isset($results["other"])?$results["other"]:array()), true)
        ),
    ),
    'options' => array(
        'collapsible' => false,
    ),
));
?>