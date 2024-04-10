<?php

namespace app\controllers;

use app\models\File;
use app\models\UploadForm;
use Yii;
use yii\base\Exception;
use yii\console\Response;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{

    public $modelClass = 'app\models\File';
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }



    /**
     * Lists all File models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => File::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single File model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                $dataProvider = new ActiveDataProvider([
                    'query' => File::find(),
                    /*
                    'pagination' => [
                        'pageSize' => 50
                    ],
                    'sort' => [
                        'defaultOrder' => [
                            'id' => SORT_DESC,
                        ]
                    ],
                    */
                ]);
                return $this->render('index', ['dataProvider' =>$dataProvider]);
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Downloads an existing file as zip.
     * @param string $path
     * @return Response|\yii\web\Response
     * @throws Exception
     */
    public function actionDownload(string $path)
    {
        Yii::error('TEST'.$path, '');
        if(extension_loaded('zip')){

            $file = \Yii::getAlias('@webroot/archive'.microtime().'.zip');

            $zip = new \ZipArchive();

            if($zip->open($file, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create a zip file');
            }

            $zip->addFile($path);

            $zip->close();

            if (file_exists($file)) {
                \Yii::$app->response->sendFile($file, 'archive.zip');
                ignore_user_abort(true);//удаление временного файла
                if (connection_aborted()) unlink($file);
                register_shutdown_function('unlink', $file);
            }

            return Yii::$app->response->sendFile($file, 'archive.zip');
        }
        else {
            throw new Exception('zip ext not enabled');
        }
    }
}
