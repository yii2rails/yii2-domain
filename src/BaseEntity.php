<?php

namespace yii2rails\domain;

use yii\base\InvalidCallException;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii2rails\domain\helpers\EntityType;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\interfaces\ValueObjectInterface;
use yii2rails\domain\traits\entity\ValidatorTrait;
use yii2rails\extension\common\helpers\ReflectionHelper;
use ReflectionClass;
use ReflectionProperty;
use yii\base\Arrayable;
use DateTime;
use Yii;
use yii\base\Behavior;
use yii\base\UnknownPropertyException;
use yii2rails\domain\values\TimeValue;

class BaseEntity extends Component implements Arrayable {

    use ValidatorTrait;

    const SCENARIO_DEFAULT = 'default';
	const EVENT_INIT = 'init';
    const EVENT_BEFORE_SET_ATTRIBUTE = 'beforeSetAttribute';
    const EVENT_BEFORE_GET_ATTRIBUTE = 'beforeGetAttribute';
    const EVENT_BEFORE_VALIDATE = 'beforeValidate';
    const EVENT_AFTER_VALIDATE = 'afterValidate';
	
	private $old_attributes = [];
	private $hidden_attributes = [];

	public function fieldType() {
		return [];
	}

    /**
     * Возвращает поле по которому можно идентифицировать уникальность сущности
     * @return array
     */
	public function keyFields()
    {
        return [
            'id'
        ];
    }

    /**
     * Возвращает поля запрещенные к редактированию
     * @return array
     */
    public function protectedFields() {
        return [];
    }

    public function readOnlyFields() {
        return [];
    }

	public function extraFields() {
		return [];
	}

	public static function labels() {
		return [];
	}
	
	public function init()
	{
		parent::init();
		$this->trigger(self::EVENT_INIT);
	}
	
	public function fields() {
		$fields = $this->attributes();
		$fields = array_diff($fields, $this->extraFields());
		return array_combine($fields, $fields);
	}
	
	public function __construct($config = []) {
		if(!empty($config)) {
			if($config instanceof BaseEntity) {
				$config = $config->toArray();
			} elseif(!is_array($config)) {
                $config = (array) $config;
            }
			$this->setAttributes($config);
		}
		$this->init();
	}
	
	public function getConstantEnum($prefix = null) {
		$enums = ReflectionHelper::getConstantsValuesByPrefix($this, $prefix);
		return $enums;
	}

	public function toArrayRaw(array $fields = [], array $expand = [], $recursive = true) {
		return $this->toArray($fields, $expand, $recursive, true);
	}
	
	public function toArray(array $fields = [], array $expand = [], $recursive = true, $isRaw = false) : array {
		if(empty($fields)) {
			$fields = $this->fields();
		}
		$fields = $this->addExtraFields($fields, $expand);
		$result = [];
		foreach($fields as  $key => $name) {
            $value = $this->getAttribute($name, $isRaw);
            if($recursive) {
                if($name instanceof \Closure) {
                    $result[ $key ] = Helper::toArray($value, $recursive);
                } else {
                    $result[ $name ] = Helper::toArray($value, $recursive);
                }
            } else {
                if ($name instanceof \Closure) {
                    $result[$key] = $value;
                } else {
                    $result[$name] = $value;
                }
            }
		}
		return $result;
	}

	public function load($attributes, $only = null) {
		$this->setAttributes($attributes, $only);
	}

    public function modifiedFields() {
        if(empty($attributeNames)) {
            $attributeNames = $this->attributes();
        }
        $result = [];
        foreach($attributeNames as $name) {
            if(array_key_exists($name, $this->old_attributes)) {
                if($this->{$name} !== $this->old_attributes[$name]) { //isset($values[$name]) &&
                    $result[] = $name;
                }
            }
        }
        return $result;
    }
	
    // todo: make test
	public function showAttributesOnly($attributes) {
		$allAttributes = $this->attributes();
		foreach ($allAttributes as $key => $val) {
			if (in_array($val, $attributes)) {
				unset($allAttributes[$key]);
			}
		}
		$allAttributes = array_values($allAttributes);
		$this->hideAttributes($allAttributes);
	}
 
	public function hideAttributes($attributes) {
		$attributes = ArrayHelper::toArray($attributes);
		$this->hidden_attributes = ArrayHelper::merge($attributes, $this->hidden_attributes);
	}
    
    public function attributes() {
        $class = new ReflectionClass(static::class);
        $propertyTypes = ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED;
        $properties = $class->getProperties($propertyTypes);
        $names = [];
        foreach($properties as $property) {
            $names[] = $property->getName();
        }
	    $names = array_diff($names, $this->hidden_attributes);
	    $names = array_values($names);
        return $names;
    }

    public function __get($name) {
        return $this->getAttribute($name);
    }

    public function __set($name, $value) {
        $this->isReadOnly($name);
        $this->isProtected($name, $value);
        $this->trigger(self::EVENT_BEFORE_SET_ATTRIBUTE);
        $setter = $this->magicMethodName($name, 'set');
        if(method_exists($this, $setter)) {
            // set property
            $this->$setter($value);
            return null;
        } elseif(strncmp($name, 'on ', 3) === 0) {
            // on event: attach event handler
            $this->on(trim(substr($name, 3)), $value);

            return null;
        } elseif(strncmp($name, 'as ', 3) === 0) {
            // as behavior: attach behavior
            $name = trim(substr($name, 3));
            $this->attachBehavior($name, $value instanceof Behavior ? $value : Yii::createObject($value));

            return null;
        }

        if(property_exists($this, $name)) {

            return $this->$name = $this->evaluteFieldValue($name, $value);

            // read property, e.g. getName()
            //$value;
        }

        // behavior property
        $this->ensureBehaviors();
        foreach($this->behaviors as $behavior) {
            if($behavior->canSetProperty($name)) {
                $behavior->$name = $value;
                return null;
            }
        }

        if(method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
    }

    public function __isset($name) {
	    $getter = $this->magicMethodName($name, 'get');
        if(method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        if(property_exists($this, $name)) {
            // read property, e.g. getName()
            return $this->$name !== null;
        }

        // behavior property
        $this->ensureBehaviors();
        foreach($this->behaviors as $behavior) {
            if($behavior->canGetProperty($name)) {
                return $behavior->$name !== null;
            }
        }

        return false;
    }

    public function __unset($name) {
        $this->isProtected($name);
        $setter = $this->magicMethodName($name, 'set');
        if(method_exists($this, $setter)) {
            $this->$setter(null);
            return null;
        }

        if(property_exists($this, $name)) {
            // read property, e.g. getName()
            return $this->$name = null;
        }

        // behavior property
        $this->ensureBehaviors();
        foreach($this->behaviors as $behavior) {
            if($behavior->canSetProperty($name)) {
                $behavior->$name = null;
                return null;
            }
        }

        throw new InvalidCallException('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
    }

    public function getAttribute($name, $inRaw = false) {
        $this->trigger(self::EVENT_BEFORE_GET_ATTRIBUTE);
        if($name instanceof \Closure) {
            return $this->extractValue($name(), $inRaw);
        }

        $getter = $this->magicMethodName($name, 'get');
        if(method_exists($this, $getter)) {
            // read property, e.g. getName()
            return $this->extractValue($this->$getter(), $inRaw);
        }

        if(property_exists($this, $name)) {
            // read property, e.g. getName()
            return $this->extractValue($this->$name, $inRaw);
        }

        // behavior property
        $this->ensureBehaviors();
        foreach($this->behaviors as $behavior) {
            if($behavior->canGetProperty($name)) {
                return $this->extractValue($behavior->$name, $inRaw);
            }
        }

        if(method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    private function magicMethodName($name, $direction) {
        return $direction . str_replace('_', '', $name);
    }

    private function extractValue($value, $inRaw = false) {
        if($inRaw) {
            return $value;
        }
        if($value instanceof ValueObjectInterface) {
            $value = $value->get();
        }
        if($value instanceof DateTime) {
            $value = $value->format(TimeValue::FORMAT_WEB);
        }
        return $value;
    }

    private function addExtraFields($fields, $expand) {
        $extra = $this->extraFields();
        if(empty($extra)) {
            return $fields;
        }
        foreach($expand as $field) {
            if(in_array($field, $extra)) {
                $fields[ $field ] = $field;
            }
        }
        return $fields;
    }

    private function setAttributes($values, $attributeNames = null) {
		if(empty($values) || !is_array($values)) {
			return null;
		}
		if(empty($attributeNames)) {
			$attributeNames = $this->attributes();
		}
        $this->old_attributes = [];
		foreach($values as $name => $value) {
			if(in_array($name, $attributeNames)) {
                $this->__set($name, $value);
			}
		}
        foreach($attributeNames as $name) {
            if(isset($this->$name)) {
                $this->old_attributes[ $name ] = $this->$name;
            }
        }
		return $this->old_attributes;
	}

    private function isReadOnly($name) {
        $readOnly = $this->readOnlyFields();
	    if(empty($readOnly)) {
	        return false;
        }
        if(!in_array($name, $readOnly)) {
            return false;
        }
        if(!empty($this->$name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
            // ReadOnlyException
        }
	    return true;
    }

    /**
     * Проверка на модификацию защищенного поля
     * первичные ключи являются по умолчанию защищенными
     * @param $name
     * @return bool
     */
    private function isProtected($name, $value = null) {
        $keyFields = $this->keyFields();
        $protected = $this->protectedFields();
        $keyIsSet = false;
        foreach ($keyFields as $keyField){
            if (!isset($this->{$keyField})){
                continue;
            }
            if ($this->{$keyField}){
                $protected[] = $keyField;
                $keyIsSet = true;
            }
        }
        if (!$keyIsSet) {
            return true;
        }
        if (empty($protected)) {
            return true;
        }
        if (!in_array($name, $protected)) {
            return true;
        }
        if (!empty($this->$name) && $this->$name !== $value) {
            throw new InvalidCallException('Setting protected property: ' . get_class($this) . '::' . $name);
        }

        return true;
    }

	private function evaluteFieldValue($name, $value) {
        $fieldType = $this->fieldType();
        if (empty($fieldType[$name])) {
            return $value;
        }
        return EntityType::encode($value, $fieldType[$name]);
	}

}