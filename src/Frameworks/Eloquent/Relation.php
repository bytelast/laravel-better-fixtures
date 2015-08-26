<?php

namespace Yaodong\Fixtures\Frameworks\Eloquent;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Yaodong\Fixtures\Contracts\Relation as RelationInterface;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;

class Relation implements RelationInterface
{
    /**
     * @var EloquentRelation
     */
    protected $relation;

    /**
     * @param EloquentRelation $relation
     */
    public function __construct(EloquentRelation $relation)
    {
        $this->relation = $relation;
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function getForeignKey()
    {
        if ($this->relation instanceof BelongsTo) {
            return $this->relation->getForeignKey();
        }

        throw new Exception('Relation type ' . get_class($this->relation) . 'is not supported.');
    }
}

