<?php

declare(strict_types = 1);

namespace CodeFusion\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as TestCaseAlias;

abstract class TestCase extends TestCaseAlias
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function setUpDatabase($app): void
    {
        $table = $app->get('db')->connection()->getSchemaBuilder();

        $table->create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $table->create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $table->create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('customer_id')->constrained();
            $table->string('name');
            $table->timestamps();
        });

        $table->create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('customer_id')->constrained();
            $table->string('type')->default('default');
            $table->timestamps();
        });

        $table->create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('customer_id')->constrained();
            $table->string('value');
            $table->timestamps();
        });
    }
}
