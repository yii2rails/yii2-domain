<?php

namespace yii2rails\domain\web;

use Yii;
use yii2rails\extension\web\helpers\Behavior;

class ActiveController extends Controller {

	const RENDER_UPDATE = '@yii2rails/domain/views/active/update';
	const RENDER_CREATE = '@yii2rails/domain/views/active/create';
	const RENDER_INDEX = '@yii2rails/domain/views/active/index';
	const RENDER_VIEW = '@yii2rails/domain/views/active/view';

	const ACTION_UPDATE = 'yii2rails\domain\web\actions\UpdateAction';
	const ACTION_CREATE = 'yii2rails\domain\web\actions\CreateAction';
	const ACTION_INDEX = 'yii2rails\domain\web\actions\IndexAction';
	const ACTION_VIEW = 'yii2rails\domain\web\actions\ViewAction';
	const ACTION_DELETE = 'yii2rails\domain\web\actions\DeleteAction';

	public $formClass;
	public $titleName = 'title';

	public function actions() {
		return [
			'update' => [
				'class' => self::ACTION_UPDATE,
				'render' => self::RENDER_UPDATE,
			],
			'create' => [
				'class' => self::ACTION_CREATE,
				'render' => self::RENDER_CREATE,
			],
			'index' => [
				'class' => self::ACTION_INDEX,
				'render' => self::RENDER_INDEX,
			],
			'view' => [
				'class' => self::ACTION_VIEW,
				'render' => self::RENDER_VIEW,
			],
			'delete' => [
				'class' => self::ACTION_DELETE,
			],
		];
	}
	
}
