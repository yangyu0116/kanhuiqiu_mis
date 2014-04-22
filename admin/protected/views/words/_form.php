<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'word-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
        <?php echo $form->textField($model,'id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>
-->
	<div class="row">
		<?php echo $form->labelEx($model,'word'); ?>
		<?php echo $form->textField($model,'word',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'word'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'samewords'); ?>
		<?php echo $form->textField($model,'samewords',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'samewords'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '添加' : '修改'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->