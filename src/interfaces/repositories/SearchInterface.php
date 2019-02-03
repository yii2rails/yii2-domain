<?php

namespace yii2rails\domain\interfaces\repositories;

use yii2rails\domain\data\Query;

interface SearchInterface extends RepositoryInterface {
	
	public function searchByText($text, Query $query = null);
	public function searchByTextFields();
	
}