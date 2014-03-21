<?php
/* @var $this ShortVideoMetaController */
/* @var $model ShortVideoMeta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'short-video-meta-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'video_title'); ?>
		<?php echo $form->textField($model,'video_title',array('size'=>60,'maxlength'=>300)); ?>
		<?php echo $form->error($model,'video_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'simg_url'); ?>
		<?php echo $form->textField($model,'simg_url',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'simg_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pub_date'); ?>
		<?php echo $form->textField($model,'pub_date'); ?>
		<?php echo $form->error($model,'pub_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'play_nums'); ?>
		<?php echo $form->textField($model,'play_nums'); ?>
		<?php echo $form->error($model,'play_nums'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'site_id'); ?>
		<?php echo $form->textField($model,'site_id'); ?>
		<?php echo $form->error($model,'site_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_detail_link'); ?>
		<?php echo $form->textField($model,'source_detail_link',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'source_detail_link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_list_link'); ?>
		<?php echo $form->textField($model,'source_list_link',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'source_list_link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'swf_link'); ?>
		<?php echo $form->textField($model,'swf_link',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'swf_link'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duration'); ?>
		<?php echo $form->textField($model,'duration'); ?>
		<?php echo $form->error($model,'duration'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'channel_id'); ?>
		<?php echo $form->textField($model,'channel_id'); ?>
		<?php echo $form->error($model,'channel_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'add_time'); ?>
		<?php echo $form->textField($model,'add_time'); ?>
		<?php echo $form->error($model,'add_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'flag'); ?>
		<?php echo $form->textField($model,'flag'); ?>
		<?php echo $form->error($model,'flag'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'final_score'); ?>
		<?php echo $form->textField($model,'final_score'); ?>
		<?php echo $form->error($model,'final_score'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'limg_url'); ?>
		<?php echo $form->textField($model,'limg_url',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'limg_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mimg_url'); ?>
		<?php echo $form->textField($model,'mimg_url',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'mimg_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comments_num'); ?>
		<?php echo $form->textField($model,'comments_num'); ?>
		<?php echo $form->error($model,'comments_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
		<?php echo $form->error($model,'update_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->