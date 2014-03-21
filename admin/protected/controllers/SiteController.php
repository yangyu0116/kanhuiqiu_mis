<?php

class SiteController extends Controller {

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('admin', 'create', 'update', 'logout', 'stats', 'error'),
                'users' => array('yangyu'),
            ),
            array('allow',
                'actions' => array('index', 'login'),
                'users' => array('*'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * 统计各站点、各频道视频数量
     */
    public function actionStats() {
        $channels = Channel::model()->findAll();
        $sites = Site::model()->findAll();
        $stats = array();
        // 分频道统计
        foreach ($channels as $c) {
            $row = array(
                'id' => $c->channel_id,
                'channel' => $c->title_chs
            );
            $channel_total = 0;
            foreach ($sites as $s) {
                $count = Yii::app()->db->createCommand("select count(*) from tbl_short_video_meta where site_id={$s->site_id} and channel_id={$c->channel_id}")->queryScalar();
                $row['site_' . $s->site_py] = $count;
                $channel_total += $count;
            }
            $row['channel_total'] = $channel_total;
            $stats[] = $row;
        }
        
        // 分站点统计
        $row = array(
            'id' => 0,
            'channel' => '各站点视频总数'
        );
        $channel_total = 0;
        foreach ($sites as $s) {
            $count = Yii::app()->db->createCommand("select count(*) from tbl_short_video_meta where site_id={$s->site_id}")->queryScalar();
            $row['site_' . $s->site_py] = $count;
            $channel_total += $count;
        }
        $row['channel_total'] = $channel_total;
        $stats[] = $row;
        
        // 输出结果
        $dp = new CArrayDataProvider($stats, array(
                    'id' => 'stats',
                    'pagination' => array(
                        'pageSize' => 100,
                    ),
                ));
        $this->render('stats', array(
            'dp' => $dp,
        ));
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionCreate() {
        $model = new Site;

        if (isset($_POST['Site'])) {
            $model->attributes = $_POST['Site'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Site'])) {
            $model->attributes = $_POST['Site'];
            if ($model->save())
                $this->redirect(array('admin'));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionAdmin() {
        $model = new Site('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Site']))
            $model->attributes = $_GET['Site'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Site::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'Site-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}