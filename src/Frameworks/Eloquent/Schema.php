<?php
namespace Yaodong\Fixtures\Framework\Eloquent;

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
    private $model;

    /**
     * @var ReflectionClass
     */
    private $reflection;

    /**
     * @var array
     */
    private $columns;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model      = $model;
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
     * @param string $key
     *
     * @return Attribute
     */
    public function getAttribute($key)
    {
        if (!isset($this->columns[$key])) {
            $this->columns[$key] = $this->parseAttribute($key);
        }

        return $this->columns[$key];
    }

    private function parseAttribute($key)
    {
        if ($this->reflection->hasMethod($key)) {
            $method = $this->reflection->getMethod($key);
            if ($method->isPublic() && $method->getNumberOfParameters() === 0) {
                $return = call_user_func([$this->model, $key]);
                if ($return instanceof Relation) {
                    return $this->parseAssociation($return, $key);
                }
            }
        }

        return new Attribute($key);
    }

    private function parseAssociation(Relation $relation, $key)
    {
        if ($relation instanceof BelongsTo) {
            $key = $relation->getForeignKey();
        }

        return new Association($key);
    }
}
