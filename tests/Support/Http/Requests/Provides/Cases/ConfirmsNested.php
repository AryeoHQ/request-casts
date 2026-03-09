<?php

declare(strict_types=1);

namespace Tests\Support\Http\Requests\Provides\Cases;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Support\Http\Casts\Nested;
use Tests\Fixtures\Support\Enum;
use Tests\Support\Http\Requests\Concerns\Cases\ConfirmsInputCasting;

/**
 * @mixin ConfirmsInputCasting
 */
trait ConfirmsNested
{
    #[Test]
    public function it_can_cast_nested_object_with_primitives(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'name' => 'string',
                'age' => 'integer',
                'is_active' => 'boolean',
            ]),
        ])->merge([
            'user' => [
                'name' => 123,
                'age' => '25',
                'is_active' => '1',
            ],
        ]);

        $user = $this->request->user;

        $this->assertIsArray($user);
        $this->assertSame('123', $user['name']);
        $this->assertSame(25, $user['age']);
        $this->assertTrue($user['is_active']);
    }

    #[Test]
    public function it_can_cast_nested_collection(): void
    {
        $this->request->mergeCasts([
            'items' => Nested::make([
                '*.price' => 'float',
                '*.quantity' => 'integer',
            ]),
        ])->merge([
            'items' => [
                ['price' => '9.99', 'quantity' => '2'],
                ['price' => '4.50', 'quantity' => '1'],
            ],
        ]);

        $items = $this->request->items;

        $this->assertIsArray($items);
        $this->assertCount(2, $items);
        $this->assertSame(9.99, $items[0]['price']);
        $this->assertSame(2, $items[0]['quantity']);
        $this->assertSame(4.50, $items[1]['price']);
        $this->assertSame(1, $items[1]['quantity']);
    }

    #[Test]
    public function it_can_cast_deeply_nested_with_composition(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'address' => Nested::make([
                    'city' => 'string',
                    'zip' => 'integer',
                ]),
            ]),
        ])->merge([
            'user' => [
                'address' => [
                    'city' => 123,
                    'zip' => '90210',
                ],
            ],
        ]);

        $user = $this->request->user;

        $this->assertSame('123', $user['address']['city']);
        $this->assertSame(90210, $user['address']['zip']);
    }

    #[Test]
    public function it_can_cast_leaf_with_castable_class(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'tags' => AsCollection::class,
            ]),
        ])->merge([
            'user' => [
                'tags' => ['php', 'laravel'],
            ],
        ]);

        $user = $this->request->user;

        $this->assertInstanceOf(Collection::class, $user['tags']);
        $this->assertSame(['php', 'laravel'], $user['tags']->toArray());
    }

    #[Test]
    public function it_can_cast_leaf_with_enum(): void
    {
        $this->request->mergeCasts([
            'post' => Nested::make([
                'status' => Enum::class,
            ]),
        ])->merge([
            'post' => [
                'status' => 'draft',
            ],
        ]);

        $post = $this->request->post;

        $this->assertSame(Enum::Draft, $post['status']);
    }

    #[Test]
    public function it_can_cast_collection_with_nested_paths(): void
    {
        $this->request->mergeCasts([
            'orders' => Nested::make([
                '*.total' => 'float',
                '*.customer.name' => 'string',
            ]),
        ])->merge([
            'orders' => [
                ['total' => '99.99', 'customer' => ['name' => 123]],
                ['total' => '50.00', 'customer' => ['name' => 456]],
            ],
        ]);

        $orders = $this->request->orders;

        $this->assertSame(99.99, $orders[0]['total']);
        $this->assertSame('123', $orders[0]['customer']['name']);
        $this->assertSame(50.00, $orders[1]['total']);
        $this->assertSame('456', $orders[1]['customer']['name']);
    }

    #[Test]
    public function it_handles_null_nested_value(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'name' => 'string',
            ]),
        ])->merge([
            'user' => null,
        ]);

        $this->assertNull($this->request->user);
    }

    #[Test]
    public function it_handles_missing_nested_keys(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'name' => 'string',
                'age' => 'integer',
            ]),
        ])->merge([
            'user' => [
                'name' => 123,
            ],
        ]);

        $user = $this->request->user;

        $this->assertSame('123', $user['name']);
        $this->assertArrayNotHasKey('age', $user);
    }

    #[Test]
    public function it_handles_empty_array_for_collection(): void
    {
        $this->request->mergeCasts([
            'items' => Nested::make([
                '*.price' => 'float',
            ]),
        ])->merge([
            'items' => [],
        ]);

        $this->assertSame([], $this->request->items);
    }

    #[Test]
    public function it_handles_empty_cast_definitions(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([]),
        ])->merge([
            'user' => ['name' => 'John'],
        ]);

        $this->assertSame(['name' => 'John'], $this->request->user);
    }

    #[Test]
    public function it_handles_missing_nested_parent(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'address' => Nested::make([
                    'city' => 'string',
                ]),
            ]),
        ])->merge([
            'user' => [
                'name' => 'John',
            ],
        ]);

        $user = $this->request->user;

        $this->assertSame('John', $user['name']);
        $this->assertArrayNotHasKey('address', $user);
    }

    #[Test]
    public function it_can_cast_from_json_string(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'age' => 'integer',
            ]),
        ])->merge([
            'user' => json_encode(['age' => '25', 'name' => 'John']),
        ]);

        $user = $this->request->user;

        $this->assertSame(25, $user['age']);
        $this->assertSame('John', $user['name']);
    }

    #[Test]
    public function it_can_bidirectionally_cast_nested_values(): void
    {
        $cast = Nested::make([
            'tags' => AsCollection::class,
        ]);

        $this->request->mergeCasts([
            'user' => $cast,
        ])->merge([
            'user' => ['tags' => ['a', 'b']],
        ]);

        $user = $this->request->user;
        $this->assertInstanceOf(Collection::class, $user['tags']);
    }

    #[Test]
    public function it_can_cast_nested_with_as_collection_alongside_wildcards(): void
    {
        $this->request->mergeCasts([
            'user' => Nested::make([
                'tags' => AsCollection::class,
                'addresses.*.city' => 'string',
                'addresses.*.zip' => 'integer',
            ]),
        ])->merge([
            'user' => [
                'tags' => ['php', 'laravel'],
                'addresses' => [
                    ['city' => 123, 'zip' => '90210'],
                    ['city' => 456, 'zip' => '10001'],
                ],
            ],
        ]);

        $user = $this->request->user;

        $this->assertInstanceOf(Collection::class, $user['tags']);
        $this->assertSame(['php', 'laravel'], $user['tags']->toArray());

        $this->assertSame('123', $user['addresses'][0]['city']);
        $this->assertSame(90210, $user['addresses'][0]['zip']);
        $this->assertSame('456', $user['addresses'][1]['city']);
        $this->assertSame(10001, $user['addresses'][1]['zip']);
    }

    #[Test]
    public function it_can_cast_complex_nested_structure(): void
    {
        $this->request->mergeCasts([
            'per_page' => 'integer',
            'q' => 'string',
            'filters' => Nested::make([
                'price.min' => 'integer',
                'price.max' => 'integer',
                'sqft.min' => 'integer',
                'sqft.max' => 'integer',
                'bedrooms' => 'integer',
                'bathrooms' => 'float',
                'is_active' => 'boolean',
                'tags.*.label' => 'string',
                'geo.radius' => 'float',
                'geo.center.lat' => 'float',
                'geo.center.lng' => 'float',
            ]),
        ])->merge([
            'per_page' => '25',
            'q' => 123,
            'filters' => [
                'price' => ['min' => '100000', 'max' => '500000'],
                'sqft' => ['min' => '1000', 'max' => '3000'],
                'bedrooms' => '3',
                'bathrooms' => '2.5',
                'is_active' => '1',
                'tags' => [
                    ['label' => 123],
                    ['label' => 456],
                ],
                'geo' => [
                    'radius' => '10.5',
                    'center' => ['lat' => '40.7128', 'lng' => '-74.0060'],
                ],
            ],
        ]);

        $this->assertSame(25, $this->request->per_page);
        $this->assertSame('123', $this->request->q);

        $filters = $this->request->filters;

        $this->assertSame(100000, $filters['price']['min']);
        $this->assertSame(500000, $filters['price']['max']);
        $this->assertSame(1000, $filters['sqft']['min']);
        $this->assertSame(3000, $filters['sqft']['max']);
        $this->assertSame(3, $filters['bedrooms']);
        $this->assertSame(2.5, $filters['bathrooms']);
        $this->assertTrue($filters['is_active']);
        $this->assertSame('123', $filters['tags'][0]['label']);
        $this->assertSame('456', $filters['tags'][1]['label']);
        $this->assertSame(10.5, $filters['geo']['radius']);
        $this->assertSame(40.7128, $filters['geo']['center']['lat']);
        $this->assertSame(-74.0060, $filters['geo']['center']['lng']);
    }

    #[Test]
    public function it_can_cast_wildcard_alongside_sibling_keys(): void
    {
        $this->request->mergeCasts([
            'data' => Nested::make([
                '*.price' => 'float',
                'total' => 'integer',
            ]),
        ])->merge([
            'data' => [
                ['price' => '9.99'],
                ['price' => '4.50'],
                'total' => '100',
            ],
        ]);

        $data = $this->request->data;

        $this->assertSame(9.99, $data[0]['price']);
        $this->assertSame(4.50, $data[1]['price']);
        $this->assertSame(100, $data['total']);
    }
}
