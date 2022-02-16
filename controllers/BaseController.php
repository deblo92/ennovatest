<?php


namespace app\controllers;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest && Yii::$app->request->get('r') != 'site/login'){
            $this->redirect(['site/login']);
        }

        return parent::beforeAction($action);
    }



}