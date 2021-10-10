# Users: Add (`create.blade.php`)

Next onto the add page. Create a new file `/resources/views/users/create.blade.php` and add the
following code:

```html
<?php
/**
 * Filename:    create.blade.php
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
            {{ __('Add User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{route('users.store')}}" method="post">
                        @csrf

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
                                   value="{{ old('name') }}">
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
                                   value="{{ old('email') }}">
                            @error('email')
                            <small class="text-red-500 my-2 pt-2">
                                {{ $message }}
                            </small>
                            @enderror
                        </div>

                        <div class="w-full py-3">
                            <label class="text-md text-gray-600 w-full" for="password">
                                Password
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
                                   placeholder="Confirm Password"
                                   class="@error('password') border-red-500 @enderror
                                       border-gray-300 w-full">
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

Then modify the `UserController.php` file and add the code for the create method:

```php
    public function create()
    {
        return view('users.create');
    }
```

A create method is great, but clicking the Save button will not get the user actually added to
the document.

We edit the `store` method to do this for us.

```php
public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'max:255',
                    'unique:users',
                    'email:rfc'
                //  'email:rfc,dns'
                ],
                'password' => [
                    'confirmed',
                    'required',
                    Password::min(4)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                    //  ->symbols()
                    //  ->uncompromised()
                    ,
                ],
            ]
        );
```

The first stage of the store method is validation.

We create the rules that the inputs must comply with. For example:

| Rules For | Rules                     | Meaning                                              |
|-----------|---------------------------|------------------------------------------------------|
| name      | required, string, max:255 | Name is required, is a string and cannot be longer than 255 characters |
| email     | required, max:255, unique:users, email:rfc | eMail is required, must be the form of an email address that complies with the RFC, maximum length of 255 characters, and must no occur more than once in the Users table. |
| password  | required, confirmed, min(4), letters, mixedCase, numbers | The Password is required and the password_confirmation field must also be completed and match the password. The password must also be at least 4 characters long, have letters, at least one upper case letter, and at least one number. |

Other options for the password validation include checking to see if it has been compromised in
a data leak. This is the `uncompromised` option. The `symbols` options requires at least one
symbol in the password.

Continuing with the store method...

```php
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }
```

If the validation is successful then the user is created and stored in the database.

Remember that we will need to have two imports added to the top of the file:

```php
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
```

### Next: [Users: Delete](08-Users-Delete.md)
