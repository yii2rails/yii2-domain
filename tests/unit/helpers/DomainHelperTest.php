<?php

namespace tests\unit\helpers;

use yii2tool\test\Test\Unit;
use yii\base\Model;
use yii2rails\domain\helpers\DomainHelper;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\helpers\TestAuthHelper;

class DomainHelperTest extends Unit
{
	
	public function testHas()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isHas = DomainHelper::has('account');
		$this->tester->assertTrue($isHas);
		
		$isHas = DomainHelper::has('account1');
		$this->tester->assertFalse($isHas);
	}
	
	public function testIsEntity()
	{
		$entity = new LoginEntity();
		$isHas = DomainHelper::isEntity($entity);
		$this->tester->assertTrue($isHas);
		
		$entity = new Model();
		$isHas = DomainHelper::isEntity($entity);
		$this->tester->assertFalse($isHas);
		
		$isHas = DomainHelper::isEntity('string');
		$this->tester->assertFalse($isHas);
	}
	
	public function testIsCollection()
	{
		$isHas = DomainHelper::isCollection([]);
		$this->tester->assertTrue($isHas);
		
		$isHas = DomainHelper::isCollection('string');
		$this->tester->assertFalse($isHas);
	}
	
	public function testMessagesAlias()
	{
		TestAuthHelper::defineAccountDomain();
		
		$isHas = DomainHelper::messagesAlias('account');
		$this->tester->assertEquals($isHas, '@yii2bundle/account/domain/v3/messages');
	}
	
}
