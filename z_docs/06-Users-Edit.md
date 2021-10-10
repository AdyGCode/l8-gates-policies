# Users: Edit (`edit.blade.php`)

Next onto the Edit page. Create a new file `/resources/views/users/edit.blade.php` and add the
following code:

```html
<?php
/**
 * Filename:    edit.blade.php
 * Project:     l8-gate-policies
 * Location:    resources\views\users
 * Author:      Adrian Gould 
 * Created:     08/09/21
 * Description:
 *     Add description here
 */

?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('users.update',['user' => $user]) }}"
                          method="post">
                        @csrf
                        @method('patch')

                        @if ($errors->any())
                        <div class="border-red-900 border-2 border-solid bg-red-800
                                        text-white px-2 my-2 py-1 rounded">
                            <p class="flex-1">
                                <i class="fas fa-exclamation-triangle mr-6 pl-2"></i>
                                <span class="align-middle">
                                        Please correct the errors on this form.
                                    </span>
                            </p>
                        </div>
                        @endif

                        <div class="w-full py-3">
                            <label class="text-gray-600 w-full" for="username">
                                Name
                            </label>
                            <input type="text"
                                   name="name" id="username"
                                   placeholder="Enter Given and Family Names"
                                   class="@error('name') border-red-500 @enderror
                                       border-gray-300 w-full"
                                   value="{{ old('name') ?? $user->name }}">
                            @error('name')
                            <small class="text-red-500 my-2 pt-2">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="w-full py-3">
                            <label class=" text-gray-600 w-full" for="email">
                                eMail
                            </label>
                            <input type="text"
                                   name="email" id="email"
                                   placeholder="eMail"
                                   class="@error('email') border-red-500 @enderror
                                       border-gray-300 w-full"
                                   value="{{ old('email')??$user->email }}">
                            @error('email')
                            <small class="text-red-500 my-2 pt-2">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="w-full py-3">
                            <label class="text-md text-gray-600 w-full" for="password">
                                Password
                                <small class="pl-4 pt-1 text-gray-600">
                                    (Leave blank if not updating the password)
                                </small>
                            </label>
                            <input type="password"
                                   name="password" id="password"
                                   placeholder="Password"
                                   class="@error('password') border-red-500 @enderror
                                          border-gray-300 w-full">
                            @error('password')
                            <small class="text-red-500 my-2 pt-2">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="w-full py-3">
                            <label class="text-gray-600 w-full"
                                   for="password_confirmation">
                                Confirm Password
                            </label>
                            <input type="password"
                                   name="password_confirmation" id="password_confirmation"
                                   placeholder="Confirm Password" class="input input-bordered
                                    w-full ">
                            @error('password_confirmation')
                            <small class="text-red-500 my-2 pt-2">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="py-6">
                            <button class="rounded p-2 px-4 mr-4
                                           border border-green-600 text-green-800
                                           hover:bg-green-600 hover:text-white
                                           transition-all ease-in-out duration-200"
                                    type="submit">
                                Save Changes
                            </button>
                            <a class="rounded p-2 px-4 mr-4
                                      border border-amber-600 text-amber-800
                                      hover:bg-amber-500 hover:text-white
                                      transition-all ease-in-out duration-200"
                               href="{{ route('users.index') }}">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

```

Then modify the `UserController.php` file and add the code for the edit method:

```php
    public function edit(User $user)
    {
        return view('users.edit', compact(['user']));
    }
```

Obviously, when the 'save changes' button is pressed we will want the application to update the
user's details.

#### Creating the update method

The update method is going to be very similar to the add (which we will see in [Users: Add]
(07-Users-Add.md) in the next section).

Let's start by creating the validation for the submitted data.

```php
public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255'
                ],
                'email' => [
                    'required',
                    'max:255',
                    'email:rfc',
                    //'email:rfc,dns', // also check for valid smtp response
                    Rule::unique('users')->ignore($user),
                ],
```

So far the only change is the rule to replace the `unique:users`. In this case we call the
`Rules` class and apply the unique requirement, but then modify it to ignore the current user.

Why? Because if we did not do this, the user would have to change their email every time they
needed to do an update, even if they have the same email address.

Carrying on...

```php                
        'password' =>
            (isset($request->password) && !is_null($request->password) ?
                [
                'sometimes',
                'confirmed',
                'required',
                Password::min(4)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                //  ->symbols()
                //  ->uncompromised(),
            ] : [null]),
        ],
    ]
);
```

Next we have some small changes to how we handle the password. In this case we check the
password to see if it is empty, if so we can ignore the password_confirmation and password
conditions and set the rules to `[null]`.

If we have the password set, then, as for `create` we need to confirm the password adn ensure
the conditions are met.

You will note the duplication of the password conditions... we can DRY this code out later.

```php
        if ($request->input('name') !== $user->name) {
            $user->name = $request->input('name');
        }

        if ($request->input('email') !== $user->email) {
            $user->email = $request->input('email');
        }

        if (!is_null($request->input('password'))) {
            $user->password = $request->input('password');
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success','User updated successfully');
    }
```

The next step is to check each of the name, password and email fields to see if they have
changed, if so we fill the relevant parts of the user then save the changes.

Redirection of the page is achieved after the patch is complete.

### Next: [Users: Add](07-Users-Add.md)
