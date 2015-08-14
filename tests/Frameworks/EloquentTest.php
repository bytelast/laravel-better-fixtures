<?php

namespace Yaodong\Fixtures\Test\Frameworks;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit_Framework_TestCase as TestCase;
use Yaodong\Fixtures\Fixtures;
use Yaodong\Fixtures\Frameworks\Eloquent\Schema;

class EloquentTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $db_file = __DIR__.'/testing.sqlite';
        touch($db_file);

        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => $db_file,
            'prefix'   => '',
        ]);
        $capsule->bootEloquent();
    }


    public function testParsing()
    {
        $folders = [
            __DIR__.'/Eloquent/fixtures/base',
            __DIR__.'/Eloquent/fixtures',
        ];

        Fixtures::setSchemaLoader(function ($table) {
            $class = __NAMESPACE__.'\Eloquent\\'.studly_case(str_singular($table));

            return new Schema(new $class());
        });

        $fixtures = new Fixtures($folders);

        $data = $fixtures->toArray();

        self::assertTrue(is_array($data));
        self::assertArrayHasKey('posts', $data);
        self::assertArrayHasKey('users', $data);
        self::assertEquals($data['posts']['holiday']['user_id'], $data['users']['jane']['id']);

        self::assertArrayHasKey('admin', $data['users']);
    }
}
