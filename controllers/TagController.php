<?php

namespace albertborsos\yii2tagger\controllers;

use Yii;
use Exception;
use albertborsos\yii2tagger\models\Tags;
use albertborsos\yii2tagger\models\TagSearch;
use albertborsos\yii2lib\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TagController implements the CRUD actions for Tags model.
 */
class TagController extends Controller
{
    public function init()
    {
        parent::init();
        $this->defaultAction = 'index';
        $this->name = 'Cimkék';
        $this->layout = '//center';
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'matchCallback' => function(){
                            return Yii::$app->user->can('reader');
                            }
                    ],
                    [
                        'actions' => ['updatebyeditable'],
                        'allow' => true,
                        'matchCallback' => function(){
                            return Yii::$app->user->can('editor');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'updatebyeditable' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tags models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Finds the Tags model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tags::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdatebyeditable(){
		$key       = Yii::$app->request->post('pk');
		$id        = unserialize(base64_decode($key));
        $attribute = Yii::$app->request->post('name');
        $value     = Yii::$app->request->post('value');

        $model = $this->findModel($id);
        try{
            if (!is_null($model)){
                $model->$attribute = $value;
                if (!$model->save()){
                    throw new Exception('Cimke módosítása nem sikerült');
                }
            }else{
                throw new Exception('Nem létezik ilyen rekord!');
            }
        }catch (Exception $e){
            throw new HttpException(400,$e->getMessage());
        }
    }
}
