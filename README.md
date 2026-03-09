# Request Casts
This package extends Laravel’s request handling by bringing the power of Eloquent casting directly to `\Illuminate\Http\Request`. With it, you can transparently transform raw request input into strongly typed, ready-to-use data — without mutating the original user input or interfering with validation.

This means you can:
- Use any of Laravel’s built-in or custom cast types (boolean, array, encrypted, datetime, etc.) directly on request data.
- Keep casting logic centralized and consistent across your application, reducing repetitive boilerplate for type conversions.

Making request data behave more like Eloquent attributes helps you write cleaner, more predictable code.

## Installation
You can install the package via Composer:

```bash
composer require aryeo/request-casts
```
That's it — no configuration required.

## Usage
Enabling request casting is straightforward. Simply implement the provided `interface` and use the `trait` on your `Request`:

```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Support\Http\Requests\Contracts\CastableData;
use Support\Http\Requests\Provides\CastsData;

class Validator extends FormRequest implements CastableData
{
    use CastsData;
}
```

Once enabled, you can define casts just like you would on an Eloquent `Model`:
```php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Support\Http\Requests\Contracts\CastableData;
use Support\Http\Requests\Provides\CastsData;

class Validator extends FormRequest implements CastableData
{
    use CastsData;

    public function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'tags' => 'array',
        ];
    }
}
```

Now, when accessing request data, the values are automatically cast:
```php
namespace App\Http\Controllers\UpdateUser;

use App\Http\Requests\Validator;
use App\Models\User;

class Controller
{
    public function __invoke(Validator $request, User $user)
    {
        $user->update([
            'is_admin' => $request->is_admin,
            'tags' => $request->tags
        ]);

        return response()->json($user);
    }
}
```

## Accessing Data
Casted values are available through the accessors you already use:

| Accessor | Returns |
|---|---|
| `$request->field` | Casted |
| `$request->all()` | Casted |
| `$request->validated()` | Casted |
| `$request->safe()` | Casted |
| `$request->input()` | Raw |
| `$request->query()` | Raw |
| `$request->collect()` | Raw |

Validation always runs against the **raw** input, so your rules see the original values. After validation passes, `validated()` and `safe()` return the casted versions of only the validated fields — giving you type-safe data without any extra work.

Cast values are rich PHP types — `Carbon` instances, `Collection`s, backed enums, etc. — so you can call methods on them directly. When including cast values in a JSON response, they serialize automatically as long as the underlying type implements `JsonSerializable`. Laravel's built-in casts all satisfy this. If you write a custom cast whose `get()` returns an object, ensure it implements `JsonSerializable` so it serializes correctly at the response layer.

## Nested Casting
For complex request structures — nested objects, arrays of items, deeply nested paths — use `Nested::make()` to apply casts at any depth:

```php
use Support\Http\Casts\Nested;

public function casts(): array
{
    return [
        'user' => Nested::make([
            'name' => 'string',
            'age' => 'integer',
            'is_active' => 'boolean',
        ]),
    ];
}
```

Now `$request->user` returns an array with each key cast to its declared type.

### Collections
Use `*` to cast items in a collection:

```php
'items' => Nested::make([
    '*.price' => 'float',
    '*.quantity' => 'integer',
]),
```

### Dot Notation
Use dot notation to reach deeply nested paths:

```php
'filters' => Nested::make([
    'price.min' => 'integer',
    'price.max' => 'integer',
    'geo.center.lat' => 'float',
    'geo.center.lng' => 'float',
]),
```

### Any Cast Type
Any cast that works on a flat request attribute works as a leaf value inside `Nested::make()` — primitives, `Castable` classes, `CastsAttributes` implementations, and enums:

```php
use Illuminate\Database\Eloquent\Casts\AsCollection;

'user' => Nested::make([
    'tags' => AsCollection::class,
    'role' => MyEnum::class,
    'age' => 'integer',
]),
```

### Combining Everything
All of these compose naturally. Here's a real-world search endpoint:

```php
public function casts(): array
{
    return [
        'per_page' => 'integer',
        'query' => 'string',
        'filters' => Nested::make([
            'price.min' => 'integer',
            'price.max' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'float',
            'is_active' => 'boolean',
            'tags.*.label' => 'string',
            'geo.radius' => 'float',
            'geo.center.lat' => 'float',
            'geo.center.lng' => 'float',
        ]),
    ];
}
```
