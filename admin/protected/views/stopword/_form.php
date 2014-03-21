<div class="form wide">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stopword-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'stopword'); ?>
		<?php
                if ($this->action->getId()=="create" && isset($_GET["stopword"])):
                    echo CHtml::textField("Stopword[stopword]", $_GET["stopword"]);
                else:
                    echo $form->textField($model,'stopword',array('size'=>60,'maxlength'=>100));
                endif;
                ?>
		<?php echo $form->error($model,'stopword'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '添加' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->