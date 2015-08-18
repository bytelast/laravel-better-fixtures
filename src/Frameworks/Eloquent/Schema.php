<?php

namespace Yaodong\Fixtures\Frameworks\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use Yaodong\Fixtures\Attribute;
use Yaodong\Fixtures\Contracts\Schema as Base;

class Schema implements Base
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * @var array
     */
    protected $columns;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->reflection = new ReflectionClass(get_class($model));
    }

    public function getIncrementing()
    {
        return $this->model->getIncrementing();
    }

    public function getPrimaryKeyName()
    {
        return $this->model->getKeyName();
    }

    /**
     * @param string $name
     *
     * @return Attribute
     */
    public function getAttribute($name)
    {
        if (!isset($this->columns[$name])) {
            $this->columns[$name] = $this->parseAttribute($name);
        }

        return $this->columns[$name];
    }

    protected function parseAttribute($name)
    {
        $method_name = camel_case($name);
        if ($this->reflection->hasMethod($method_name)) {
            $method = $this->reflection->getMethod($method_name);
            if ($method->isPublic() && $method->getNumberOfParameters() === 0) {
                $return = call_user_func([$this->model, $method_name]);
                if ($return instanceof Relation) {
                    return $this->parseAssociation($return, $name);
                }
            }
        }

        return new Attribute($name);
    }

    protected function parseAssociation(Relation $relation, $name)
    {
        if ($relation instanceof BelongsTo) {
            $name = $relation->getForeignKey();
        }

        return new Attribute($name, true);
    }
}
