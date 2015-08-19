<?php

namespace Yaodong\Fixtures\Test\Frameworks\Eloquent;

use Yaodong\Fixtures\Contracts\Filter;
use Yaodong\Fixtures\Filters\Identifier;
use Yaodong\Fixtures\Filters\Relations;
use Yaodong\Fixtures\Fixtures as FixturesAbstract;
use Yaodong\Fixtures\Frameworks\Eloquent\Schema;

class Fixtures extends FixturesAbstract
{
    /**
     * @param string $table
     *
     * @return Schema
     */
    public function getSchema($table)
    {
        $class = __NAMESPACE__.'\\Models\\'.studly_case(str_singular($table));

        return new Schema(new $class());
    }

    /**
     * @return Filter[] $filters
     */
    protected function getFilters()
    {
        return [
            new Identifier(),
            new Relations(),
        ];
    }

}
