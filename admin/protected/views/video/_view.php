<?php
/* @var $this ShortVideoMetaController */
/* @var $data ShortVideoMeta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->video_id), array('view', 'id'=>$data->video_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('video_title')); ?>:</b>
	<?php echo CHtml::encode($data->video_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('simg_url')); ?>:</b>
	<?php echo CHtml::encode($data->simg_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pub_date')); ?>:</b>
	<?php echo CHtml::encode($data->pub_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('play_nums')); ?>:</b>
	<?php echo CHtml::encode($data->play_nums); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('site_id')); ?>:</b>
	<?php echo CHtml::encode($data->site_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('source_detail_link')); ?>:</b>
	<?php echo CHtml::encode($data->source_detail_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('source_list_link')); ?>:</b>
	<?php echo CHtml::encode($data->source_list_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('swf_link')); ?>:</b>
	<?php echo CHtml::encode($data->swf_link); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration')); ?>:</b>
	<?php echo CHtml::encode($data->duration); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('channel_id')); ?>:</b>
	<?php echo CHtml::encode($data->channel_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('add_time')); ?>:</b>
	<?php echo CHtml::encode($data->add_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('flag')); ?>:</b>
	<?php echo CHtml::encode($data->flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('final_score')); ?>:</b>
	<?php echo CHtml::encode($data->final_score); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('limg_url')); ?>:</b>
	<?php echo CHtml::encode($data->limg_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mimg_url')); ?>:</b>
	<?php echo CHtml::encode($data->mimg_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comments_num')); ?>:</b>
	<?php echo CHtml::encode($data->comments_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	*/ ?>

</div>