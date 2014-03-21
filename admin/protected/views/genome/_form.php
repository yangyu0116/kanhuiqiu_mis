<?php
/* @var $this GenomeController */
/* @var $model Genome */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'genome-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'father_id'); ?>
		<?php echo CHtml::activeDropDownList($model, 'father_id', CHtml::listData(Channel::model()->findAll(), 'channel_id', 'title_chs')); ?>
		<?php echo $form->error($model,'father_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title_chs'); ?>
		<?php
                if ($this->action->getId()=="create" && isset($_GET["title_chs"])):
                    echo CHtml::textField("Genome[title_chs]", $_GET["title_chs"],array('size'=>60,'maxlength'=>100));
                else:
                    echo $form->textField($model,'title_chs',array('size'=>60,'maxlength'=>100));
                endif;                
                ?>
		<?php echo $form->error($model,'title_chs'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title_eng'); ?>
		<?php echo $form->textField($model,'title_eng',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title_eng'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hot_value'); ?>
		<?php echo $form->textField($model,'hot_value'); ?>
		<?php echo $form->error($model,'hot_value'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '添加' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->