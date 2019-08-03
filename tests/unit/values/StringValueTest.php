<?php
namespace tests\unit\values;

use yii2tool\test\Test\Unit;
use yii\base\InvalidArgumentException;
use yii2rails\domain\values\StringValue;

class StringValueTest extends Unit
{
	
	public function testSet()
	{
		$value = $this->buildInstance();
		$value->set('qwerty');
	}
	
	public function testSetNotValid()
	{
		$value = $this->buildInstance();
		try {
			$value->set(999);
			$this->tester->assertBad();
		} catch(InvalidArgumentException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testIsValid()
	{
		$value = $this->buildInstance();
		$isValid = $value->isValid('qwerty');
		$this->tester->assertTrue($isValid);
		
		$isValid = $value->isValid(150);
		$this->tester->assertFalse($isValid);
		
		$isValid = $value->isValid([4545]);
		$this->tester->assertFalse($isValid);
		
		$isValid = $value->isValid(null);
		$this->tester->assertFalse($isValid);
	}
	
	private function buildInstance() {
		$value = new StringValue();
		return $value;
	}
	
}
