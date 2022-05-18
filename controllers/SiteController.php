<?php

namespace app\controllers;

use app\models\Supplier;
use app\models\SupplierSearchForm;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\db\Query;

/**
 * SiteController implements the CRUD actions for Supplier model.
 */
class SiteController extends Controller
{
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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierSearchForm();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Export selected supplier models
     *
     * @return void
     */
    public function actionExport()
    {
        $handle = fopen('php://temp', 'wr+');
        fputcsv($handle, [
            'id', 'name', 'code', 'status'
        ]);

        $ids = $this->request->post('ids');
        if (!empty($ids)) {
            $query = Supplier::find()->where(['in', 'id', explode(',',  $ids)]);
        } else {
            $searchModel = new SupplierSearchForm();
            $dataProvider = $searchModel->search($this->request->queryParams);
            $query = $dataProvider->query;
        }

        /**
         * @var Query $query
         */
        foreach ($query->each() as $supplier) {
            /**
             * @var Supplier $supplier
             */
            fputcsv($handle, [
                $supplier->id,
                $supplier->name,
                $supplier->code,
                $supplier->status,
            ]);
        }

        $this->response->sendStreamAsFile($handle, date('YmdHisu') . '.csv', ['mimeType' => 'text/csv']);
    }
}
