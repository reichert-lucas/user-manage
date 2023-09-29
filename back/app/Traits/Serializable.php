<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Jetimob\Http\Exceptions\RuntimeException;
use JsonException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;
use TypeError;

trait Serializable
{
    protected array $hydrationData = [];

    public static function reflectProperty(ReflectionClass $reflectionClass, string $propertyName): ?ReflectionProperty
    {
        try {
            return $reflectionClass->getProperty($propertyName);
        } catch (ReflectionException) {
            return null;
        }
    }

    /**
     * @param ReflectionNamedType|string $type
     *
     * @return bool
     */
    public static function isBuiltinType(ReflectionNamedType|string $type): bool
    {
        if (is_string($type)) {
            $typeStr = $type;
        } elseif ($type instanceof ReflectionNamedType || method_exists($type, 'getName')) {
            $typeStr = $type->getName();
        } else {
            return false;
        }

        return in_array($typeStr, ['bool', 'boolean', 'double', 'float', 'int', 'integer', 'string']);
    }

    public function hydrateArrayProperty(string $propertyName, ReflectionProperty $property, array $array): self
    {
        // if there is a method named {$propertyName}ArrayItemType in this class, it should return the type (class that
        // uses the Jetimob\Http\Traits\Serializable trait) of the items of the given array
        $propertyNameCamelCased = Str::camel($propertyName);
        if (method_exists($this, "{$propertyNameCamelCased}ItemType")
            && class_exists($class = $this->{"{$propertyNameCamelCased}ItemType"}())
            && method_exists($class, 'deserializeArray')
        ) {
            $this->{$propertyName} = $class::deserializeArray($array);
            return $this;
        }

        // We'll try to extract the typing from de docblock. For this to work, the @var typing MUST use the
        // complete namespace of the type, i.e.: \Typing\Full\Namespace\Type
        $docComment = $property->getDocComment();

        if (!$docComment) {
            return $this;
        }

        preg_match_all('/@var\s+([\w\\\]+)\[].*/', $docComment, $matches, PREG_PATTERN_ORDER);

        if (empty($matches) || empty($matches[1])) {
            return $this;
        }

        $type = $matches[1][0];

        // is a built-in type array
        if (static::isBuiltinType($type)) {
            $this->{$propertyName} = $array;
            return $this;
        }

        if (!class_exists($type) || !method_exists($type, 'deserializeArray')) {
            return $this;
        }

        $this->{$propertyName} = $type::deserializeArray($array);
        return $this;
    }

    public function hydrate(array $dataObject): static
    {
        $reflectionClass = new ReflectionClass($this);
        $this->hydrationData = $dataObject;
        if (array_is_list($dataObject)) {
            if (!property_exists($this, 'container')) {
                return $this;
            }

            $prop = self::reflectProperty($reflectionClass, 'container');

            if (is_null($prop)) {
                return $this;
            }

            $this->hydrateArrayProperty('container', $prop, $dataObject);

            return $this;
        }

        foreach ($dataObject as $key => $value) {
            if (method_exists($this, 'getPropertyName')) {
                $key = $this->getPropertyName($key);
            }

            // if the property doesn't exist or is already set, continue
            if (!property_exists($this, $key) || !empty($this->{$key})) {
                continue;
            }

            $prop = self::reflectProperty($reflectionClass, $key);

            if (is_null($prop)) {
                continue;
            }

            $type = $prop->getType();

            // its not a built-in php type
            if ($type && !$type->isBuiltin()) {
                $expectedClass = $type->getName();

                if (method_exists($expectedClass, 'deserialize')) {
                    // can throw \TypeError
                    $this->{$key} = $expectedClass::deserialize($value, $expectedClass);
                }
            } elseif (is_array($value)) {
                $this->hydrateArrayProperty($key, $prop, $value);
            } else {
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    /**
     * @throws JsonException
     * @throws TypeError
     */
    public static function deserialize(string|array|null $serialized, string $into = null): static
    {
        $className = $into ?? static::class;
        $instance = new $className();

        if (is_null($serialized)) {
            return $instance;
        }

        if (is_array($serialized)) {
            $data = $serialized;
        } elseif (is_string($serialized)) {
            $data = json_decode($serialized, true, 512, JSON_THROW_ON_ERROR);
        } else {
            $data = json_decode(json_encode($serialized, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        }

        if (is_string($data)) {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        }

        if (empty($data)) {
            return $instance;
        }

        if (method_exists($instance, 'hydrate')) {
            $instance->hydrate($data);
        }

        return $instance;
    }

    /**
     * @throws JsonException
     */
    public static function deserializeArray(array $serializedArray): array
    {
        $items = [];

        foreach ($serializedArray as $item) {
            $items[] = self::deserialize($item);
        }

        return $items;
    }

    public function getHydrationData(): array
    {
        return $this->hydrationData;
    }

    public function serializeProperty(ReflectionProperty $property)
    {
        if (!$property->isInitialized($this)) {
            return null;
        }

        $type = $property->getType();
        $value = $property->getValue($this);

        if (is_null($value) || is_null($type) || static::isBuiltinType($type)) {
            return $value;
        }

        if ($property->getName() === 'hydrationData') {
            return null;
        }

        if (is_array($value)) {
            $serializedData = [];

            foreach ($value as $arrItem) {
                if (
                    method_exists($arrItem, 'toArray')
                    && !is_null($serializedArrItem = $arrItem->toArray())
                ) {
                    $serializedData[] = $serializedArrItem;
                } else {
                    $serializedData[] = $arrItem;
                }
            }

            return $serializedData;
        }

        if (method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        throw new RuntimeException("Cannot serialize property of type '{$type->getName()}'");
    }

    public function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $props = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);

            if (!is_null($propertyValue = $this->serializeProperty($property))) {
                $props[$property->getName()] = $propertyValue;
            }

            $property->setAccessible(false);
        }

        return $props;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function serialize(): array
    {
        return $this->toArray();
    }
}
