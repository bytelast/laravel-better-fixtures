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
     * @param string $table_name
     *
     * @return Schema
     */
    public function getSchema($table_name)
    {
        $class = __NAMESPACE__.'\\Models\\'.studly_case(str_singular($table_name));

        return new Schema(new $class());
    }

    /**
     * @return Filter[] $filters
     */
    public function getFilters()
    {
        return [
            new Identifier(),
            new Relations(),
        ];
    }

}
