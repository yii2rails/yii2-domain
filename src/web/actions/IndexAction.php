<?php

namespace yii2rails\domain\web\actions;

use Yii;
use yii\base\Model;
use yii2rails\domain\base\Action;
use yii2rails\domain\data\ActiveDataProvider;

class IndexAction extends Action {
	
	public $serviceMethod = 'getDataProvider';
	public $searchClass = null;
	
	public function run() {
		$this->view->title = Yii::t('main', 'list_title');
		$searchModel = $this->loadSearchModel();
		$method = $this->serviceMethod;
		/** @var ActiveDataProvider $dataProvider */
		$dataProvider = $this->service->$method($this->query);
		//$dataProvider->query = Query::forge($this->query);
		return $this->render($this->render, compact('dataProvider', 'searchModel'));
	}
	
	private function loadSearchModel() {
		$searchModel = null;
		if ($this->searchClass) {
			/** @var Model $searchModel */
			$searchModel = new $this->searchClass;
			$formName = $searchModel->formName();
			$params = Yii::$app->getRequest()->getQueryParams();
			if(!empty($params[$formName])) {
				$this->query->removeWhere($formName);
				$searchModel->load($params);
				if(method_exists($searchModel, 'prepareQuery')) {
					$searchModel->prepareQuery($this->query);
				} else {
					foreach($params[$formName] as $name => $value) {
						if($value !== '') {
							$this->query->andWhere([$name => $value]);
						}
					}
				}
			}
		}
		return $searchModel;
	}
	
}
