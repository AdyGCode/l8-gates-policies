# Resourceful Routes and Navigation

Let's get one more component ready, the routing.

## Resourceful Routes

If we create "resourceful routes" we can save quite a bit of time.

This convention in laravel means we write one or two lines for a Model's routing, rather than at
least 5.

Open the `routes/web.php` file and edit it to include the following line before the
`require` line.

```php
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    
    Route::resource('/users', UserController::class);
    Route::post('/users/{user}/delete', [UserController::class, 'delete']);
    
});
```

The `Route::resource('/users'...` call created the following routes automatically:

| Method    | URI                 | Name          | Action                     | Middleware                                                |
|-----------|---------------------|---------------|----------------------------|-----------------------------------------------------------|
| GET/HEAD  | users               | users.index   | ...\UserController@index   | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |
| POST      | users               | users.store   | ...\UserController@store   | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |
| GET/HEAD  | users/create        | users.index   | ...\UserController@create  | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |
| GET/HEAD  | users/{user}        | users.show    | ...\UserController@show    | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |
| PUT/PATCH | users/{user}        | users.update  | ...\UserController@update  | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |
| DELETE    | users/{user}        | users.destroy | ...\UserController@destroy | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |
| GET/HEAD  | users/{user}/edit   | users.edit    | ...\UserController@edit    | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |

*We have abbreviated the paths to make the table a little smaller.*

The `Route::post(...` call creates the last route:

| Method    | URI                 | Name          | Action                     | Middleware                                                |
|-----------|---------------------|---------------|----------------------------|-----------------------------------------------------------|
| GET/HEAD  | users/{user}/delete | users.delete  | ...\UserController@delete  | web / ...\Authenticate:sanctum / ...\EnsureEmailIsVerified |

The `Authenticate:sanctum` is the method of authentication, and it must be successful for the
actions to be able to be executed. Likewise, the email must be verified.

We are now able to look at the authenticated user dashboard.

## Dashboard

When a user is logged-in successfully, they will be presented with a dashboard. By default it is
very empty.

We are going to modify the following files:

- `resources/views/dashboard.blade.php`
- `resources/views/layouts/navigation.blade.php`

### Modifying the App Blade File

Open the `resources/views/layouts/navigation.blade.php` file, and then locate the section below:

```html
<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
</div>
```

We are going to add two more navigation links by duplicating the `x-nav-link` block:

```html
<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
        {{ __('Tasks') }}
    </x-nav-link>
    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
        {{ __('Users') }}
    </x-nav-link>
</div>
```

*The Tasks link will direct to the homepage for now, until we add the Tasks to the application a
little later.*

Using the "Ad Ministration" account, you should be able to log in and check that you get a
dashboard with three menu options. At the moment we will be continuing with the Users pages.

One thing that is of interest is the `routeIs('users.*')` in the third item. This enables the
"Users" navigation menu item to be highlighted if you are visiting **any** of the users
routes (`users.index`, `users.create`, `users.delete`, etc).

### Next: [Users: Browse](04-Users-Browse.md)
