<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use common\models\UserAkses;
use backend\models\customs\User;
use backend\models\customs\search\UserSearch;

use backend\models\AuthAssignment;
use backend\models\customs\SignUp;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                    'toggle-status' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new SignUp();
        if ($model->load(Yii::$app->request->post())) {
            /*if ($user = $model->signup()) {
                $_akses = new UserAkses();

                $_akses->user_id = $user->id;
                $_akses->skpd_id = $model->skpd;
                $_akses->save(false);

                Yii::$app->session->setFlash('success', 'User baru berhasil ditambahkan.');
                return $this->redirect(['/user']);
            }*/
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user = $model->signup()) {
                    $_akses = new UserAkses();

                    $_akses->user_id = $user->id;
                    $_akses->skpd_id = $model->skpd;
                    if ($_akses->save(false)) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'User baru berhasil ditambahkan.');
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error','Terjadi kesalahan, User baru tidak bisa disimpan');
                    }
                    //return $this->redirect(['/user']);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error','Rollback transaction. User baru tidak bisa disimpan');
                //return $this->redirect(['index']);
            }
            return $this->redirect(['/user']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $auth = Yii::$app->authManager;
        //echo "<pre>";
        //print_r(array_shift($auth->getRolesByUser($id))->name);
        //echo "</pre>";
        //exit();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->delete()) {
                $authorRole = array_shift($auth->getRolesByUser($id))->name;
                //$auth->revoke($authorRole, $authorRole);
                $modelAssignment = $this->findModelAssignment($id, $authorRole);
                if ($modelAssignment->delete()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success','User berhasil dihapus');
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error','Terjadi kesalahan, Role User tidak berhasil dihapus');
                }
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error','Terjadi kesalahan, User tidak berhasil dihapus');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error','Rollback transaction, User tidak berhasil dihapus');
        }
        return $this->redirect(['index']);
    }

    public function actionToggleStatus()
    {
        $field = Yii::$app->request->post('attribute');
        $id = Yii::$app->request->post('id');
        $value = Yii::$app->request->post('value');

        $query = Yii::$app->db
            ->createCommand("UPDATE user
                SET ".$field."=:fl
                WHERE id=:id")
            ->bindValue(':fl', $value)
            ->bindValue(':id', $id)
            ->execute();

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'status' => true,
            'message' => '',
        ];
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelAssignment($id, $role)
    {
        if (($model = AuthAssignment::findOne(['user_id' => $id, 'item_name' => $role])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
