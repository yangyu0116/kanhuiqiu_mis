<div class="form wide">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'channel-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'site_name'); ?>
        <?php echo $form->textField($model,'site_name'); ?>
        <?php echo $form->error($model,'site_name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'site_py'); ?>
        <?php echo $form->textField($model,'site_py'); ?>
        <?php echo $form->error($model,'site_py'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? '添加' : '保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->