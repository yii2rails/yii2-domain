<?php

namespace yii2rails\domain\values;

use DateTime;
use DateTimeZone;
use yii\base\InvalidArgumentException;
use yii2rails\extension\common\helpers\time\TimeHelper;
use yii2rails\extension\web\enums\HttpHeaderEnum;

class TimeValue extends BaseValue {
	
	const FORMAT_WEB = 'Y-m-d H:i:s';
	const FORMAT_WEB_TIME = 'H:i:s';
	const FORMAT_WEB_DATE = 'Y-m-d';
	const FORMAT_API = 'Y-m-d\TH:i:s\Z';
	const TIMESTAMP = '_TIMESTAMP_';
	
	public function setDateTime($year = 0, $month = 0, $day = 0, $hour = 0, $minute = 0, $second = 0) {
		$this->setDate($year, $month, $day);
		$this->setTime($hour, $minute, $second);
	}
	
	public function setTime($hour = 0, $minute = 0, $second = 0) {
		/** @var DateTime $dateTime */
		$dateTime = $this->get();
		$dateTime->setTime($hour, $minute, $second);
		$this->set($dateTime);
	}
	
	public function setDate($year = 0, $month = 0, $day = 0) {
		/** @var DateTime $dateTime */
		$dateTime = $this->get();
		if($dateTime == null) {
            $dateTime = new DateTime;
        }
		$dateTime->setDate($year, $month, $day);
		$this->set($dateTime);
	}
	
	public function setFromFormat($value, $format) {
		/** @var DateTime $dateTime */
		$dateTime = DateTime::createFromFormat($format, $value);
		$this->set($dateTime);
	}
	
	public function setNow() {
		$this->set(time());
	}
	
	public function set($value) {
		$dateTimeZoneUtc = new DateTimeZone('UTC');
		if(!empty($value)) {
			try {
                if(is_string($value)) {
                    $dateTime = new DateTime($value, $dateTimeZoneUtc);
                } elseif(is_array($value)) {
                    $dateTime = new DateTime();
                    $dateTime->setDate($value[0], $value[1], $value[2]);
                    $dateTime->setTime($value[3], $value[4], $value[5]);
                } elseif($value instanceof DateTime) {
                    $dateTime = clone $value;
                    $dateTime->setTimezone($dateTimeZoneUtc);
                } elseif(is_numeric($value)) {
                    $dateTime = new DateTime;
                    $dateTime->setTimezone($dateTimeZoneUtc);
                    $dateTime->setTimestamp($value);
                }
            } catch (\Exception $e) {
                throw new InvalidArgumentException('Unknown time format');
            }
			if(!isset($dateTime)) {
                throw new InvalidArgumentException('Unknown time format');
            }
			parent::set($dateTime);
		} else {
			parent::set(null);
		}
	}
	
	public function getInFormat($mask = self::TIMESTAMP) {
        /** @var DateTime $dateTime */
        $dateTime = $this->get();
        if($dateTime == null) {
            return null;
        }
		$timeZone = TimeHelper::getTimeZone();
        if($timeZone) {
            $dateTime->setTimezone(new \DateTimeZone($timeZone));
        }
		if($mask == self::TIMESTAMP) {
			$value = $dateTime->getTimestamp();
		} else {
			$value = $dateTime->format($mask);
		}
		return $value;
	}
	
	protected function _encode($value) {
	    if(empty($value)) {
            return null;
        }
	    //prr($value);
		/** @var DateTime $dateTime */
		if($value instanceof DateTime) {
			$dateTime = $value;
        } elseif(is_integer($value)) {
            $dateTime = new DateTime();
			$dateTime->setTimestamp($value);
		} elseif(is_array($value)) {
            $dateTime = new DateTime();
			$dateTime->setDate($value[0], $value[1], $value[2]);
			$dateTime->setTime($value[3], $value[4], $value[5]);
		} elseif(is_string($value)) {
			$dateTime = new DateTime($value);
		}
		return $dateTime;
	}
	
	public function getDefault() {
	    return null;
		return $this->_encode(TIMESTAMP);
	}
	
	public function isValid($value) {
		try {
			$dateTime = $this->_encode($value);
		} catch(\Exception $e) {
			return false;
		}
		if($dateTime == null) {
            return true;
        }
		return !empty($dateTime->getTimestamp());
	}
}
