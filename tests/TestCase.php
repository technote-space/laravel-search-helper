<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Tests;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Technote\SearchHelper\Providers\SearchHelperServiceProvider;

/**
 * Class TestCase
 * @package Technote\SearchHelper\Tests
 */
class TestCase extends BaseTestCase
{
    /**
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'test');
        $app['config']->set('database.connections.test', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    /**
     * @param  Application  $app
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getPackageProviders($app)
    {
        return [
            SearchHelperServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable(false)->unique()->comment('test1');
            $table->string('name', 128)->nullable(false)->comment('test2');
            $table->string('name_kana', 128)->nullable(false)->comment('test3');
            $table->string('zip_code', 16)->nullable(false)->comment('test4');
            $table->string('address', 128)->nullable(false)->comment('test5');
            $table->string('phone', 16)->nullable(false)->comment('test6');
            $table->string('mobile_phone', 16)->nullable(true)->comment('test7');
            $table->string('home_url', 100)->nullable(true)->comment('test8');
            $table->string('email', 100)->nullable(false)->comment('test9');
            $table->unsignedSmallInteger('age')->nullable(false)->comment('test10');
            $table->boolean('bool_test')->nullable(true)->comment('test11');
            $table->float('float_test')->nullable(true)->comment('test12');
            $table->date('date_test')->nullable(true)->comment('test13');
            $table->time('time_test')->nullable(true)->comment('test14');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 128)->nullable(false)->comment('test15');
            $table->timestamps();
        });

        $faker = Factory::create('ja_JP');
        $this->userFactory('test1-name', 'test1-address', $faker);
        $this->userFactory('test2-name', 'test2-address', $faker);
        $this->userFactory('test3-name', 'test3-address', $faker);
        $this->userFactory('test4-name', 'test4-address', $faker);
        $this->userFactory('test5-name', 'test5-address', $faker);

        $this->itemFactory('test1-name');
        $this->itemFactory('test2-name');
        $this->itemFactory('test3-name');
        $this->itemFactory('test4-name');
        $this->itemFactory('test5-name');
    }

    private function userFactory(string $name, string $address, Generator $faker)
    {
        $this->userDetailFactory(User::create(), $name, $address, $faker);
    }

    private function userDetailFactory(User $user, string $name, string $address, Generator $faker)
    {
        UserDetail::create([
            'user_id'   => $user->id,
            'name'      => $name,
            'name_kana' => $faker->kanaName,
            'zip_code'  => substr_replace($faker->postcode, '-', 3, 0),
            'address'   => $address,
            'phone'     => '0'.$faker->numberBetween(10, 99).'-'.$faker->numberBetween(10, 9999).'-'.$faker->numberBetween(100, 9999),
            'email'     => $faker->email,
            'age'       => $faker->numberBetween(0, 100),
        ]);
    }

    private function itemFactory(string $name)
    {
        Item::create(['name' => $name]);
    }
}
