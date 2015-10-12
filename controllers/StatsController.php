<?php

namespace app\controllers;

use app\models\ArrayDataProvider;
use app\models\Stats;
use Yii;
use yii\base\Exception;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\web\Response;

class StatsController extends ActiveController
{
    public $modelClass = 'app\models\StatDetail';
    public $enableCsrfValidation = false;

    public function actions(){
        $actions = parent::actions();
        $actions['index']['modelClass']             = 'app\models\Stats';
        $actions['index']['prepareDataProvider']    = function(){

            $page       = Yii::$app->request->get('page', 1) - 1;
            $pageSize   = Yii::$app->request->get('per-page', 10);

            return new ArrayDataProvider([
                'allModels'  => Stats::findAll($pageSize, $page * $pageSize, $total),
                'totalCount' => $total,
                'pagination' => [
                    'pageSize' => $pageSize,
                ],
            ]);

        };

        $actions['create']['modelClass']    = 'app\models\Stats';
        $actions['view']['modelClass']      = 'app\models\Stats';
        return $actions;
    }

    public function behaviors()
    {
        return [
            'verb' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'  => ['get'],
                    'view'   => ['get'],
                    'create' => ['post'],
                    'update' => ['put'],
                    'delete' => ['delete'],
                ],
            ],
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function beforeAction($event){

        try {

            $valid =  parent::beforeAction($event);

            if($valid && in_array($event->id, ['create']) && !preg_match('~'.Response::FORMAT_JSON.'~', Yii::$app->request->contentType)){

                Yii::$app->response->statusCode = 406;
                $this->showError(Yii::$app->response->statusCode, "Content type must be '".Response::FORMAT_JSON."'");

                return false;
            }

            if($valid && in_array($event->id, ['update', 'delete'])){

                Yii::$app->response->statusCode = 403;
                $this->showError(Yii::$app->response->statusCode, "Access denied to action '".$event->id."'");

                return false;
            }

            return $valid;

        } catch (Exception $e){

            Yii::$app->response->statusCode = 405;
            Yii::$app->response->format = Response::FORMAT_JSON;
            $this->showError(Yii::$app->response->statusCode, $e->getMessage());

            return false;

        }
    }

    /**
     * @param $code
     * @param $message
     */
    private function showError($code, $message){

        $error = [
            "name"      => "Bad Request",
            "message"   =>  "",
            "code"      =>  0,
            "status"    =>  0,
        ];

        $error['message']   =   $message;
        $error['status']    =   $code;

        echo Json::encode($error);

    }

    public function actionGetIp(){
        return [
            "IP" => Yii::$app->request->userIP
        ];
    }

}