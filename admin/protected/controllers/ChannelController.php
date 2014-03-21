<?php

class ChannelController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('admin', 'create', 'update'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionCreate() {
        $model = new Channel;

        if (isset($_POST['Channel'])) {
            $model->attributes = $_POST['Channel'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Channel'])) {
            $model->attributes = $_POST['Channel'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionAdmin() {
        $model = new Channel('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Channel']))
            $model->attributes = $_GET['Channel'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Channel::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'Channel-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
