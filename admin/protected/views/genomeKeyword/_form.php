<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'genome-keyword-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'genome_id'); ?>
        <?php echo $form->textField($model,'genome_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'genome_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'keyword'); ?>
		<?php echo $form->textField($model,'keyword',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'keyword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tokenize_type'); ?>
		<?php echo $form->textField($model,'tokenize_type',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'tokenize_type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '添加' : '修改'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->