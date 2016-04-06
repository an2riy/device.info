<?php

namespace app\controllers;

use app\models\ArrayDataProvider;
use app\models\Stats;
use Yii;
use yii\base\Exception;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Class StatsController
 * @package app\controllers
 */
class StatsController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'app\models\StatDetail';

    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @return array
     */
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

    /**
     * @return array
     */
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

    /**
     * @param \yii\base\Action $event
     * @return bool
     */
    public function beforeAction($event){

        try {

            $valid =  parent::beforeAction($event);

            if($valid && in_array($event->id, ['create']) && !preg_match('~'.Response::FORMAT_JSON.'~', Yii::$app->request->contentType)){

                $this->showError(406, "Content type must be '".Response::FORMAT_JSON."'");
                return false;

            }

            if($valid && in_array($event->id, ['update', 'delete'])){

                $this->showError(403, "Access denied to action '".$event->id."'");
                return false;

            }

            return $valid;

        } catch (Exception $e){

            $this->showError(405, $e->getMessage());
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
            "status"    =>  0,
        ];

        $error['message']   =   $message;
        $error['status']    =   $code;

        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->response->statusCode = $code;
        Yii::$app->response->data = $error;
        Yii::$app->response->send();

    }

    /**
     * @return array
     */
    public function actionGetIp(){
        return [
            "IP" => Yii::$app->request->userIP
        ];
    }

}