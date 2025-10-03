<?php
namespace app\controllers;

use Yii;
use app\models\Webinar;
use app\models\WebinarRegistrant;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class WebinarController extends Controller
{
    public function actionIndex()
    {
        $webinars = Webinar::find()->all();
        return $this->render('index', ['webinars' => $webinars]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Webinar();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $eventId = Yii::$app->gotoWebinar->createEvent($model);
            if ($eventId) {
                $model->event_id = $eventId;
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->gotoWebinar->updateEvent($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model]);
    }

   public function actionRegister($id)
{
    $model = $this->findModel($id);

    $user = new \yii\base\DynamicModel(['first_name', 'last_name', 'email']);
    $user->addRule(['first_name', 'last_name', 'email'], 'required');
    $user->addRule('email', 'email');

    $result = null;

    if (Yii::$app->request->isPost) {
        $user->load(Yii::$app->request->post());

        if ($user->validate()) {
            // Register in GoToWebinar
            $result = Yii::$app->gotoWebinar->registerUser($model->event_id, [
                'firstName' => $user->first_name,
                'lastName'  => $user->last_name,
                'email'     => $user->email,
            ]);

            // Save locally
            $registrant = new \app\models\WebinarRegistrant();
            $registrant->webinar_id = $model->id;
            $registrant->first_name = $user->first_name;
            $registrant->last_name = $user->last_name;
            $registrant->email = $user->email;
            $registrant->save();
        }
    }

    return $this->render('register', [
        'model'  => $model,
        'user'   => $user,
        'result' => $result,
    ]);
}


  public function actionRegistrants($id)
{
    $webinar = Webinar::findOne($id);
    if (!$webinar) {
        throw new \yii\web\NotFoundHttpException("Webinar not found.");
    }

    $registrants = WebinarRegistrant::find()->where(['webinar_id' => $id])->all();

    return $this->render('registrants', [
        'webinar'     => $webinar,
        'registrants' => $registrants,
    ]);
}


    protected function findModel($id)
    {
        if (($model = Webinar::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Webinar not found.');
    }
    public function behaviors()
{
    return [
        'access' => [
            'class' => \yii\filters\AccessControl::class,
            'only' => ['index', 'view', 'create', 'update', 'register', 'registrants'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'], // logged-in users
                ],
            ],
        ],
    ];
}
}
