<?php

class StopwordController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('admin', 'delete', 'create', 'update'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $model = new Stopword;

        if (isset($_POST['Stopword'])) {
            $model->attributes = $_POST['Stopword'];
            if ($model->save())
                $this->redirect(array('admin'));
            else {
                print_r($model->getErrors());
                exit;
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }


    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Stopword'])) {
            $model->attributes = $_POST['Stopword'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }


    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    public function actionAdmin() {
        $model = new Stopword('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Stopword']))
            $model->attributes = $_GET['Stopword'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }


    public function loadModel($id) {
        $model = Stopword::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stopword-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
