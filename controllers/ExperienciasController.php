<?php

namespace app\controllers;

use Yii;
use app\models\Experiencia;
use app\models\ExperienciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ExperienciasController implements the CRUD actions for Experiencia model.
 */
class ExperienciasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['cv'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'update', 'view', 'index', 'cv'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Experiencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExperienciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $tipos = [
            'Formación académica' => 'Formación académica',
            'Formación complementaria' => 'Formación complementaria',
            'Experiencia profesional' => 'Experiencia profesional'
        ];
        $entidades = Experiencia::find()
                        ->select('entidad')
                        ->orderBy('entidad')
                        ->asArray()->all();
        $entidades = ArrayHelper::map($entidades, 'entidad', 'entidad');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipos' => $tipos,
            'entidades' => $entidades,
        ]);
    }

    /**
     * Lists all Experiencia models.
     * @return mixed
     */
    public function actionCv()
    {
        $formaciones = Experiencia::find()
                        ->where(['tipo' => 'Formación académica'])
                        ->orderBy('fecha_inicio desc')
                        ->asArray()->all();
        $profesionales = Experiencia::find()
                          ->where(['tipo' => 'Experiencia profesional'])
                          ->orderBy('fecha_inicio desc')
                          ->asArray()->all();
        $complementarias = Experiencia::find()
                            ->where(['tipo' => 'Formación complementaria'])
                            ->orderBy('fecha_inicio desc')
                            ->asArray()->all();

        return $this->render('cv', [
            'formaciones' => $formaciones,
            'profesionales' => $profesionales,
            'complementarias' => $complementarias,
        ]);
    }

    /**
     * Displays a single Experiencia model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Experiencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Experiencia();
        $tipos = [
            'Formación académica' => 'Formación académica',
            'Formación complementaria' => 'Formación complementaria',
            'Experiencia profesional' => 'Experiencia profesional'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'tipos' => $tipos,
        ]);
    }

    /**
     * Updates an existing Experiencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $tipos = [
            'Formación académica' => 'Formación académica',
            'Formación complementaria' => 'Formación complementaria',
            'Experiencia profesional' => 'Experiencia profesional'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'tipos' => $tipos,
        ]);
    }

    /**
     * Deletes an existing Experiencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Experiencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Experiencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Experiencia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
