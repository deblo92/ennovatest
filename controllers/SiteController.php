<?php

namespace app\controllers;

use app\models\Cliente;
use app\models\ImportForm;
use app\models\Pratica;
use mysqli;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

class SiteController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionImport(){
        $model= new ImportForm();
        if ($model->load(Yii::$app->request->post())) {
            $arrayToCollectData = [];
            $model->file = UploadedFile::getInstance($model, 'file');
            $model->file->saveAs($model->file);
            $handle = fopen($model->file, 'r');
            if($handle){
                if($model->types == 'C'){
                    $tableName = 'clienti';
                    $columns = ['nome', 'cognome', 'codicefiscale'];
                    while (($line = fgetcsv($handle, 1000, ",")) != FALSE){
                        $arrayToCollectData[] = array_combine($columns, [$line[1], $line[2], $line[3]]);
                    }
                }elseif($model->types == 'P'){
                    $tableName = 'pratiche';
                    $columns = ['cliente_id', 'id_pratica', 'stato_pratica'];
                    while (($line = fgetcsv($handle, 1000, ",")) != FALSE){
                        $arrayToCollectData[] = array_combine($columns, [$line[1], $line[2], $line[3]]);
                    }
                }
                fclose($handle);

                Yii::$app->db->createCommand()->batchInsert($tableName, $columns, $arrayToCollectData)->execute();
            }
        }
        return $this->render("import", array('model'=>$model));

    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pratiche  = Pratica::find()->all();
        $model = new Pratica();

        //qui invece che $_POST potevo chimare $model->codicefiscale etc. Cosi per Pratica che per Cliente;
        // Soluzione brutta ma capire come migliorarla
        if ($model->load(Yii::$app->request->post())) {

            if($_POST['Pratica']['id_pratica'] != '' && $_POST['Cliente']['codicefiscale'] == ''){
                $pratiche  = Pratica::find()->where( ['id_pratica' => $_POST['Pratica']['id_pratica']])->all();
            }else if($_POST['Pratica']['id_pratica'] == '' && $_POST['Cliente']['codicefiscale'] != ''){
                $pratiche  = Pratica::find()->joinWith('cliente')
                    ->where( ['clienti.codicefiscale' => $_POST['Cliente']['codicefiscale']])->all();
            }else if($_POST['Pratica']['id_pratica'] != '' && $_POST['Cliente']['codicefiscale'] != ''){
                $pratiche  = Pratica::find()->joinWith('cliente')
                    ->where( ['clienti.codicefiscale' => $_POST['Cliente']['codicefiscale']])
                    ->where(['id_pratica' => $_POST['Pratica']['id_pratica']])->all();
            }else{
                $pratiche  = Pratica::find()->all();
            }
        }else{
            if($_POST && $_POST['export-database']){
                $database = self::backupDatabaseAllTables('localhost', 'root', '', 'ennovatest');
                header('Content-type:'. pathinfo($database, PATHINFO_EXTENSION));
                header('Content-disposition: attachment; filename='.$database);
                return readfile($database);
            }
        }

        return $this->render('index', [
            'pratiche' => $pratiche,
            'request' => $model->load(Yii::$app->request->post()) ? $_POST: null,
        ]);
    }


    private static function backupDatabaseAllTables($dbhost,$dbusername,$dbpassword,$dbname,$tables = '*'){
        $db = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

        if($tables == '*') {
            $tables = array();
            $result = $db->query("SHOW TABLES");
            while($row = $result->fetch_row()) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables)?$tables:explode(',',$tables);
        }

        $return = '';

        foreach($tables as $table){
            $result = $db->query("SELECT * FROM $table");
            $numColumns = $result->field_count;

//             $return .= "DROP TABLE $table;";
            $result2 = $db->query("SHOW CREATE TABLE $table");
            $row2 = $result2->fetch_row();

            $return .= "\n\n".$row2[1].";\n\n";

            for($i = 0; $i < $numColumns; $i++) {
                while($row = $result->fetch_row()) {
                    $return .= "INSERT INTO $table VALUES(";
                    for($j=0; $j < $numColumns; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"'.$row[$j].'"' ;
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($numColumns-1)) {
                            $return.= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }

            $return .= "\n\n\n";
        }
        $handle = fopen("$dbname.sql",'w+');
        fwrite($handle,$return);
        fclose($handle);
        return "$dbname.sql";
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) ) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
;