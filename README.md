# Request Casts
This package extends Laravel’s request handling by bringing the power of Eloquent casting directly to `\Illuminate\Http\Request`. With it, you can transparently transform raw request input into strongly typed, ready-to-use data — without mutating the original user input or interfering with validation.

This means you can:
- Use any of Laravel’s built-in or custom cast types (boolean, array, encrypted, datetime, etc.) directly on request data.
- Safely work with typed values in your controllers and services while still preserving the original request payload for validation and error reporting.
- Keep casting logic centralized and consistent across your application, reducing repetitive boilerplate for type conversions.

Making request data behave more like Eloquent attributes helps you write cleaner, more predictable code while keeping validation and user input intact.

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
