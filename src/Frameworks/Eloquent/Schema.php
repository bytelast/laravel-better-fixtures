<?php

namespace Yaodong\Fixtures\Frameworks\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use ReflectionClass;
use Yaodong\Fixtures\Contracts\Schema as Base;
use Yaodong\Fixtures\Frameworks\Eloquent\Relation;

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
    protected $relations;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model      = $model;
        $this->reflection = new ReflectionClass(get_class($model));
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->model->getTable();
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
     * @param string $key
     *
     * @return Relation
     */
    public function getRelation($key)
    {
        if (!isset($this->relations[$key])) {

            $this->relations[$key] = false;

            $method_name = camel_case($key);
            if ($this->reflection->hasMethod($method_name)) {
                $method = $this->reflection->getMethod($method_name);
                if ($method->isPublic() && $method->getNumberOfParameters() === 0) {
                    $return = call_user_func([$this->model, $method_name]);
                    if ($return instanceof EloquentRelation) {
                        $this->relations[$key] = new Relation($return);
                    }
                }
            }
        }

        return $this->relations[$key];
    }
}
