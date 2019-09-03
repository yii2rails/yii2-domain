<?php

namespace yii2rails\domain;

/**
 * Базовая сущность с набором свойств в jsonb
 *
 * @author kamaelkz <kamaelkz@yandex.kz>
 */
class BaseEntityWithProperties extends BaseEntity
{
    /**
     * @var string
     */
    private $propertyField = 'data';

    /**
     * @return string
     */
    public function getPropertyField()
    {
        return $this->propertyField;
    }

    public static function properties()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if(
            property_exists($this, $this->getPropertyField())
            && is_array($this->{$this->getPropertyField()})
            && isset($this->{$this->getPropertyField()}[$name])
        ) {
            return $this->{$this->getPropertyField()}[$name];
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (
            property_exists($this, $this->getPropertyField())
            && in_array($name, static::properties())
        ) {
            $this->{$this->getPropertyField()}[$name] = $value;

            return true;
        }

        return parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        if(
            isset($fields{$this->getPropertyField()})
            && is_array($this->{$this->getPropertyField()})
        ) {
            foreach ($this->{$this->getPropertyField()} as $key => $value) {
                $fields[$key] = function () use ($value) {
                    return $value;
                };
            }
        }
//        unset($fields[$this->getPropertyField()]);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true, $isRaw = false): array
    {
        $result = parent::toArray($fields, $expand, $recursive, $isRaw);

        return $result;
    }
}