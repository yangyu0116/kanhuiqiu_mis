<?php

class VideoController extends Controller {

    public $layout = '//layouts/column1';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('view', 'create', 'update', 'admin', 'delete'),
                'users' => array('yangyu'),
            ),
			array('allow',
				'actions' => array('flag'),
				'users' => array('*'),
			),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

	public function actionFlag($video_id=0) {
		$success = 0;
		if (intval($video_id)>0) {
			$ret = Yii::app()->db->createCommand()->update('tbl_video', array('flag'=>2), 'id='.intval($id));
			$success = 1;
			$msg = "$ret row(s) affected";
		}
		else {
			$msg = "invalid video_id";
		}
		echo json_encode(array(
			'success' => $success,
			'message' => $msg,
		));
	}

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $model = new Video;

        $this->performAjaxValidation($model);

        if (isset($_POST['Video'])) {
            $model->attributes = $_POST['Video'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->video_id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if (isset($_POST['Video'])) {
            $model->attributes = $_POST['Video'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->video_id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionUpdateFlag($id) {
        $model = $this->loadModel($id);

        $model->flag = 0;
        $model->mimg_url = '';
        $model->limg_url = '';
        $model->save();

        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function actionSiteAPI($url='') {
        if ($url) {
            foreach (Yii::app()->params['siteAPI'] as $siteName => $apiUrl) {
                if (strpos($url, $siteName) !== false) {
                    $this->redirect($apiUrl . $url);
                }
            }
        }
    }

    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    public function actionAdmin() {
        $model = new Video('search');
        //$model->unsetAttributes();  // clear any default values
        if (isset($_GET['Video']))
            $model->attributes = $_GET['Video'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Video::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'video-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
