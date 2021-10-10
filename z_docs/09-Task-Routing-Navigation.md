# Task Routing and Navigation

Head back to the `/wrotues/web.php` file and edit it. We will add the following after the
equivalent for the users:

```php
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    
    Route::resource('/tasks', TaskController::class);
    Route::post('/tasks/{task}/delete', [TaskController::class, 'delete']);
    
});
```

Next we update the navigation for the Dashboard.

## Modifying the App Blade File

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
