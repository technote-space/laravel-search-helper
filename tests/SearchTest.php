<?php
declare(strict_types=1);

namespace Technote\SearchHelper\Tests;

/**
 * Class SearchTest
 * @package Technote\SearchHelper\Tests
 */
class SearchTest extends TestCase
{
    /**
     * @dataProvider dataProviderForTestSearch
     *
     * @param array $conditions
     * @param int $count
     */
    public function testUserSearch(array $conditions, int $count): void
    {
        self::assertCount($count, User::search($conditions)->get());
    }

    /**
     * @dataProvider dataProviderForTestSearch
     *
     * @param array $conditions
     * @param int $count
     */
    public function testItemSearch(array $conditions, int $count): void
    {
        self::assertCount($count, Item::search($conditions)->get());
    }

    /**
     * @return array
     */
    public function dataProviderForTestSearch(): array
    {
        return [
            [[], 5],
            [
                [
                    'id' => 1,
                ],
                1,
            ],
            [
                [
                    'id' => [1, 2],
                ],
                2,
            ],
            [
                [
                    'not_id' => 1,
                ],
                4,
            ],
            [
                [
                    'not_id' => [1, 2],
                ],
                3,
            ],
            [
                [
                    'count' => 3,
                ],
                3,
            ],
            [
                [
                    'count' => 10,
                    'offset' => 3,
                ],
                2,
            ],
            [
                [
                    's' => 'name',
                ],
                5,
            ],
            [
                [
                    's' => 'test1-',
                ],
                1,
            ],
            [
                [
                    's' => ' ',
                ],
                5,
            ],
        ];
    }
}
