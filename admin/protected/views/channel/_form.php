<div class="form wide">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'channel-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'title_chs'); ?>
        <?php echo $form->textField($model,'title_chs'); ?>
        <?php echo $form->error($model,'title_chs'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'title_py'); ?>
        <?php echo $form->textField($model,'title_py'); ?>
        <?php echo $form->error($model,'title_py'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '添加' : '保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->