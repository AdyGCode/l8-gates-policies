# Laravel Tasks App With Gates, Permissions and Policies

The steps here use Laravel Sail.

Sail may be runs in a docker container hosted on PC (via WSL2), Mac or Linux system.

More at http://...

This mini-tutorial is based on:

- Task App tutorial: https://www.parthpatel.net/laravel-tutorial-for-beginner/
- Policies/Gates Tutorial:

With healthy sprinklings of Tailwind CSS in place of Bootstrap and other HTML/CSS frameworks.

## Create Application

Open a terminal window and then make sure you are in the folder in which you wish to create the
application.

Run:

```bash
curl -s "https://laravel.build/l8-gates-policies" | bash
```

Once finished, you will be asked for your password to correct folder permissions as required.

Change into the project folder and bring Sail Up:

```bash
cd l8-gates-policies && ./vendor/bin/sail up
```

Leave the "sail runner" running in this window.

**Aside:** *to stop the sail runner you may use <kbd>CTRL</kbd>+<kbd>C</kbd> in the window where
it is running. Alternatively, in a second window and in the project folder, use `sail down`
instead.*

## Install Required Components

Open second terminal window and change into the project folder:

```bash
cd l8-gates-policies
```

Now we install Breeze, Laravel DebugBar, and associated files:

```bash
sail composer require laravel/breeze --dev 
sail composer require barryvdh/laravel-debugbar --dev --with-all-dependencies
sail php artisan breeze:install
sail npm install
sail npm install tailwindcss-gradients@2.x tailwindcss-elevation
sail npm install @tailwindcss/line-clamp @tailwindcss/forms
sail npm install @tailwindcss/typography @tailwindcss/aspect-ratio
```

modify the `webpack.mix.js` file's `mix.js` section:

```js
mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [

        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .sass('resources/sass/app.scss', 'public/css');
if (mix.inProduction()) {
    mix.version();
}
```

### Install FontAwesome:

**Fort Awesome** is the name of the company who create Font Awesome.

```bash
npm install --save @fortawesome/fontawesome-free
```

Create a new folder: `resources/sass`

Add a new file `app.scss` to the `sass` folder with the following:

```sass
// Font-Awesome
@import "~@fortawesome/fontawesome-free/scss/fontawesome";
@import "~@fortawesome/fontawesome-free/scss/regular";
@import "~@fortawesome/fontawesome-free/scss/solid";
@import "~@fortawesome/fontawesome-free/scss/brands";
```

Now run:

```bash
sail npm install
sail npm run dev
```

If asked you may need to run the `sail npm run dev` command once more.

### Adding predefined colours to Tailwind CSS

We are almost done with this step. We now want to add the full list of colours for the Tailwind
CSS (such as amber, cool gray, warm gray and more).

Edit the `tailwind.config.js` file.

Add a new line just below the first line starting with `const defaultTheme `:

```js
const colors = require('tailwindcss/colors')
```

Next modify the theme section so it reads:

```js
    theme: {
    extend: {
        fontFamily: {
            sans: ['Nunito', ...defaultTheme.fontFamily.sans],
        }
    ,
    }
,
    colors: {
        // Build your palette here
        transparent: 'transparent',
            current
    :
        'currentColor',
            white
    :
        colors.white,
            black
    :
        colors.black,
            blueGray
    :
        colors.blueGray,
            coolGray
    :
        colors.coolGray,
            gray
    :
        colors.gray,
            trueGray
    :
        colors.trueGray,
            warmGray
    :
        colors.warmGray,
            red
    :
        colors.red,
            orange
    :
        colors.orange,
            amber
    :
        colors.amber,
            yellow
    :
        colors.yellow,
            lime
    :
        colors.lime,
            green
    :
        colors.green,
            emerald
    :
        colors.emerald,
            teal
    :
        colors.teal,
            cyan
    :
        colors.cyan,
            sky
    :
        colors.sky,
            blue
    :
        colors.blue,
            indigo
    :
        colors.indigo,
            violet
    :
        colors.violet,
            purple
    :
        colors.purple,
            fuchsia
    :
        colors.fuchsia,
            pink
    :
        colors.pink,
            rose
    :
        colors.rose,

            /* Alternative names for some colours */
            primary
    :
        colors.blue,
            secondary
    :
        colors.purple,
            tertiary
    :
        colors.gray,
            danger
    :
        colors.red,
            warning
    :
        colors.amber,
            info
    :
        colors.blueGray,
            success
    :
        colors.green,
    }
,
    linearGradientColors: theme => theme('colors'),
        radialGradientColors
:
    theme => theme('colors'),
        conicGradientColors
:
    theme => theme('colors'),
}
```

Finally, modify the `plugins` section of the `tailwind.config.js` file to read:

```js
    plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/line-clamp'),
    require('@tailwindcss/aspect-ratio'),

    require('tailwindcss-gradients'),
    require('tailwindcss-elevation')(['responsive']),
],
```

And then once more run:

```bash
sail npm install
sail npm run dev
```

## Publishing package files to allow customisation and configuration changes

We run the following command to publish the mail templates, pagination templates and
notifications templates. These allow us to customise the look and feel of the components. This
can be very useful.

```bash
sail php artisan vendor:publish --tag=laravel-mail --tag=laravel-pagination --tag=laravel-notifications
```

Check the `/resources/views/components` and `/resources/views/vendor` folders.

## Adding Other Helper Packages

We will add the `laravel-timezone` helper package to allow easier display of dates and times
with the local timezone, whilst storing data in the database using UTC as the reference point.

The second line is split into three parts as it is very long, but should be entered as one
single command.

```bash
sail composer require jamesmills/laravel-timezone
sail php artisan vendor:publish
           --provider="JamesMills\LaravelTimezone\LaravelTimezoneServiceProvider"
           --tag=migrations
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag=config
```

### Configuring Timezone packages

We are using two packages for the timezone detection. James Mills' Laravel Timezone, and a
required package, Torran's Geo IP Service Provider.

We need to perform a small amount of configuration to make sure that "tagging" is enabled when
caching results for the Geo Locations. Torran's Geo IP package does not allow for file or
database caching, so we either have to turn it off, or use an alternative.

In production, we would use something like Redis or similar.

Open the `/config/cache.php` file and change the `default` cache driver line to:

```php
    'default' => env('CACHE_DRIVER', 'array'),
```

## Constructing the Basic Application

We are now ready to create our basic application.

It will need a new Controller and Seeder for the User model, plus all the parts for the Task
model.

### Create the User seeder and controller

Execute the following to create the required seeder and controller for the User model:

```bash
sail php artisan make:seeder UserSeeder
sail php artisan make:controller UserController --resource
sail php artisan make:migration add_login_fields_to_users_table
```

The last step is creating a migration to modify the users table by adding a "last logged in"
field.

#### Modify add_login_fields_to_users_table migration

We will add two new fields to the user table - the first is the date/time that they last logged
in at, the second is the IP address they logged in from.

Open the `/database/migrations/yyyy_mm_dd_hhmmss_add_login_fields_to_users_table.php` file.

*(We've written the date in the general form of `yyyy_mm_dd_hhmmss` as you will have a different
date.)*

Modify the file by editing the `up` method to be:

```php
public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();
            });
        });
    }
```

Do the same for the `down` method, but dropping the new columns:

```php
 public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
            $table->dropColumn('last_login_ip');
        });
    }
```

Run the migrations from fresh to make sure the changes are correct:

```bash
sail php artisan migrate:fresh
```

#### Model modifications

Once the migration is working we can then modify the User model file and add the new fields so
that they are mass assignable and editable.

Edit the `/App/Models/User.php` file, and add the code as instructed:

In the `$fillable` array, add the last login detail fields:

```php
        'last_login_at',
        'last_login_ip,'
```

Make sure that the `$casts` variable contains:

```php
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
```

#### User Seeder

Now we will create the user seeder.

Open the `/database/seeders/UserSeeder.php` file.

First we need to add the User Model and Hash references:

```php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
```

Then we modify the run method to include the following seeding for the users:

```php
public function run()
    {
        $seedAdminUser =
            [
                'id' => 1,
                'name' => 'Ad Ministrator',
                'email' => 'ad.ministrator@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Australia/Perth',
            ];

        $user = User::create($seedAdminUser);

        $seedManagerUsers = [
            [
                'id' => 5,
                'name' => 'YOUR NAME',
                'email' => 'GIVEN@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Australia/Perth',
            ],
            [
                'id' => 6,
                'name' => 'Andy Manager',
                'email' => 'andy.manager@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Australia/Perth',
            ],
        ];

        foreach ($seedManagerUsers as $managerUser){
            $user = User::create($managerUser);
        }

        $seedUsers = [
            [
                'id' => 10,
                'name' => 'Eileen Dover',
                'email' => 'eileen@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Australia/Perth',
            ],
            [
                'id' => null,
                'name' => 'Jacques d\'Carre',
                'email' => 'jacques@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Europe/Paris',
            ],
            [
                'id' => null,
                'name' => 'Russell Leaves',
                'email' => 'russell@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Pacific/Pitcairn',
            ],
            [
                'id' => null,
                'name' => 'Ivana Vinn',
                'email' => 'ivanna@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Europe/Moscow',
            ],
            [
                'id' => null,
                'name' => 'Win Doh',
                'email' => 'win@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Europe/Sofia',
            ],

            [
                'id' => null,
                'name' => 'Sally Mander',
                'email' => 'Sally.Mander@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Reba Dirtchee',
                'email' => 'Reba.Dirtchee@example
                .com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Carl Breakdown',
                'email' => 'Carl.Breakdown@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Al Luminum',
                'email' => 'Al.Luminum@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Phil Graves',
                'email' => 'Phil.Graves@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            
            [
                'id' => null,
                'name' => 'Cy Yonarra',
                'email' => 'Cy.Yonarra@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Buddy System',
                'email' => 'Buddy.System@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Owen Moore',
                'email' => 'Owen.Moore@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Dwayne Pipe',
                'email' => 'Dwayne.Pipe@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Summer Dey',
                'email' => 'Summer.Dey@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            
            [
                'id' => null,
                'name' => 'Stan Dup',
                'email' => 'Stan.Dup@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Miles Tugo',
                'email' => 'Miles.Tugo@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Rusty Pipes',
                'email' => 'Rusty.Pipes@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Rusty Nails',
                'email' => 'Rusty.Nails@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Preston Cleaned',
                'email' => 'Preston.Cleaned@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            
            [
                'id' => null,
                'name' => 'Norma Lee',
                'email' => 'Norma.Lee@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Crystal Stemwear',
                'email' => 'Crystal.Stemwear@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Alf A. Romeo',
                'email' => 'Alf.A.Romeo@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Myles Long',
                'email' => 'Myles.Long@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Faye Kinnitt',
                'email' => 'Faye.Kinnitt@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            
            [
                'id' => null,
                'name' => 'Dee Zaster',
                'email' => 'Dee.Zaster@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Doug Graves',
                'email' => 'Doug.Graves@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Ada Lott',
                'email' => 'Ada.Lott@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Ginger Rayl',
                'email' => 'Ginger.Rayl@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Jim Shorts',
                'email' => 'Jim.Shorts@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Pacific/Port_Moresby',
            ],
            
            [
                'id' => null,
                'name' => 'Terry Achey',
                'email' => 'Terry.Achey@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Ed Abul',
                'email' => 'Ed.Abul@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Terry Kloth',
                'email' => 'Terry.Kloth@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Jasmine Rice',
                'email' => 'Jasmine.Rice@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Asia/Tokyo',
            ],
            [
                'id' => null,
                'name' => 'Ruda Wakening',
                'email' => 'Ruda.Wakening@example
                .com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            
            [
                'id' => null,
                'name' => 'Ann Chovie',
                'email' => 'Ann.Chovie@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Telly Vision',
                'email' => 'Telly.Vision@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'Europe/Dublin',
            ],
            [
                'id' => null,
                'name' => 'Al Gore-Rythim',
                'email' => 'Al.Gore-Rythim@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'America/Boise',
            ],
            [
                'id' => null,
                'name' => 'Steve Adore',
                'email' => 'Steve.Adore@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => '',
            ],
            [
                'id' => null,
                'name' => 'Lois Point',
                'email' => 'Lois.Point@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Password1'),
                'created_at' => now(),
                'timezone' => 'America/Boise',
            ],

        ];

        foreach ($seedUsers as $seed) {
            User::create($seed);
        }

    }
```

Modify the `/database/seeders/DatabaseSeeder.php` file to run the user seeder:

```php
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            TaskSeeder::class,
        ]);
    }
```

*(We also added the Task Seeder in advance, ready for later.)*

Now execute the migration again, but also seed the tables:

```bash
sail php artisan migrate:fresh --seed
```

#### Task model, migration and seeder...

Before we create any views and associated code, we will create the Tasks table and the required
model, controller, migration and other common files.

Execute the following on the command line:

```bash
sail php artisan make:model Task -a -r
```

Open the Tasks migration (`yyyy_mm_dd_hhmmss_create_tasks_table.php`) and add the following
fields to the `up` method:

```php
public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }
```

Next we edit the `TaskSeeder.php` file to provide default test data:

```php
 public function run()
    {
        $seedTasks = [
            [
                'name' => 'Roadmap Document: Defining Web Application, Purpose, Goals and Direction',
                'description' => 'This initial task is an important part of the process. It 
                requires putting together the Web Application project goals and purpose.',
                'user_id'=>12,
                'created_at' => now(),
            ], [
                'name' => 'Researching and Defining Audience Scope and Security Documents',
                'description' => 'This task requires researching the audience/users, and 
                prospective clients (if any), and creating an Analytic Report.',
                'created_at' => now(),
                'user_id'=>10,
            ], [
                'name' => 'Creating Functional Specifications or Feature Summary Document',
                'description' => 'A Web Application Functionality Specifications Document is the
                key document in any Web Application project. This document will list all of the
                functionalities and technical specifications that a web application will require
                to accomplish. Technically, this document can become overwhelming if one has to
                follow the Functional Specifications rule and detail out each type of user\'s
                behavior on a very large project. However, it is worth putting forth the effort
                to create this document which will help prevent any future confusion or
                misunderstanding of the project features and functionalities, by both the
                project owner and developer.',
                'created_at' => now(),
                'user_id'=>11,
            ], [
                'name' => 'Third Party Vendors Identification, Analysis and Selection',
                'description' => 'This task requires researching, identifying and selection of
                third party vendors, products and services.',
                'created_at' => now(),
                'user_id'=>12,
            ],[
                'name' => 'Technology Selection, Technical Specifications, Web Application Structure and Timelines',
                'description' => 'This document is the blueprint of the technology and platform
                selection, development environment, web application development structure and 
                framework.',
                'created_at' => now(),
                'user_id'=>14,
            ],[
                'name' => 'Application Visual Guide, Design Layout, Interface Design, Wire framing',
                'description' => 'One of the main ingredients to a successful project is to put
                together a web application that utilizes a user\'s interactions, interface and
                elements that have a proven record for ease of use, and provide the best user
                experience.',
                'created_at' => now(),
                'user_id'=>11,
            ],[
                'name' => 'Web Application Development',
                'description' => 'The application\'s Design Interface is turned over to the
                 Development Team.',
                'created_at' => now(),
                'user_id'=>12,
            ],[
                'name' => 'Beta Testing and Bug Fixing',
                'description' => 'Vigorous quality assurance testing help produce the most 
                secure and reliable web applications.',
                'created_at' => now(),
                'user_id'=>11,
            ],[
                'name' => 'Client Side Scripting / Coding ',
                'description' => 'Client Side Scripting is the type of code that is executed or 
                interpreted by browsers.',
                'created_at' => now(),
                'user_id'=>5,
            ],[
                'name' => 'Server Side Scripting / Coding',
                'description' => 'Server Side Scripting is the type of code that is executed or 
                interpreted by the web server.',
                'created_at' => now(),
                'user_id'=>6,
            ],[
                'name' => 'Web Application Frameworks - Benefits and Advantages',
                'description' => 'Program actions and logic are separated from the HTML, CSS and
                 design files. This helps designers (without any programming experience) to be 
                 able to edit the interface and make design changes without help from a 
                 programmer.',
                'created_at' => now(),
                'user_id'=>6,
            ],
            ];
        foreach ($seedTasks as $seed) {
            Task::create($seed);
        }
    }
```

Rerun the migrations and seeders...

```bash
sail php artisan migrate:fresh --seed
```

We need to make sure that the newly added fields are mass assignable and editable.

Edit the `/App/Models/Task.php` file, and add the required lines from the code below:

```php
class Task extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'description',
        'password',
        'user_id',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}
```

### Routing for the web

Let's get one more component ready, the routing.

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

### Dashboard

When a user is logged-in successfully, they will be presented with a dashboard. By default it is
very empty.

We are going to modify the following files:

- `resources/views/dashboard.blade.php`
- `resources/views/layouts/navigation.blade.php`

#### Modifying the App Blade File

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

Using the "Ad Ministration" account, you should be able to log in and check that you get a
dashboard with three menu options. At the moment we will be continuing with the Users pages.

One thing that is of interest is the `outeIs('users.*')` in the third item. This enables the
"Users" navigation menu item to be highlighted if you are visiting **any** of the users
routes (`users.index`, `users.create`, `users.delete`, etc).

### Create the Users index, create, delete, edit and show pages

Create anew folder in the application's root folder with the path `/resources/views/users`
and then create a new php file called `index.blade.php`.

In this file we will create the "browse" section of the **BREAD** (or **CRUD**).

#### index.blade.php

```html
<?php
/**
 * Filename:    index.blade.php
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
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    @if ($message = Session::get('success'))
                    <div class="border-green-900 border-2 border-solid bg-green-800
                                        text-white px-2 my-2 py-1 rounded">
                        <p class="flex-1">
                            <i class="fas fa-smile mr-6 pl-2"></i>
                            <span class="align-middle">
                                        {{ $message }}
                                    </span>
                        </p>
                    </div>
                    @endif
                    <div class="overflow-x-auto ">
                        <table
                            class="table w-full border border-0 border-bottom border-gray-300">
                            <caption>System Users</caption>
                            <thead class="bg-gray-200 border border-gray-300 text-gray-700">
                            <tr>
                                <th class="p-2 text-left" scope="col">Row</th>
                                <th class="p-2 text-left" scope="col">ID</th>
                                <th class="p-2 text-left" scope="col">Name</th>
                                <th class="p-2 text-left" scope="col">Last Login</th>
                                <th class="p-2 flex justify-between text-left" scope="col">
                                    <span class="pt-1 mr-2">Actions</span>
                                    <a href="{{ route('users.create') }}"
                                       class="rounded p-2 px-4 mr-2
                                              border border-blue-600 text-blue-800 bg-white
                                              hover:bg-blue-500 hover:text-white hover:border-blue-800
                                              transition-all ease-in-out duration-200">
                                        Add User
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key=>$user)
                            <tr class="p-2 m-0 border border-gray-300
                                           hover:bg-blue-100 hover:border-blue-300
                                           transition-all duration-200 ease-in-out
                                           @if($user->id == auth()->user()->id)
                                                bg-gray-100 text-blue-800 font-semibold
                                                hover:bg-blue-200 hover:text-black
                                           @endif">
                                <td class="p-2 py-3 m-0"><small class="badge"> {{ $key+1 }}
                                </small></td>
                                <td class="p-2 py-3 m-0">{{ $user->id }}</td>
                                <td class="p-2 py-3 m-0">{{ $user->name }}</td>
                                <td class="p-2 py-3 m-0">
                                    @if(is_null($user->last_login_at))
                                    -
                                    @else
                                    @displayDate($user->last_login_at)
                                    @endif
                                </td>
                                <td class="p-2 py-3 m-0" colspan="2">
                                    <a href="{{ route('users.show', [$user->id]) }}"
                                       class="rounded p-2 px-4 mr-4
                                           border border-green-600 text-green-800 bg-white
                                           hover:bg-green-600 hover:text-white
                                           transition-all ease-in-out duration-200">Details</a>
                                    <a href="{{ route('users.edit', ['user'=>$user->id]) }}"
                                       class="rounded p-2 px-4 mr-4
                                      border border-amber-600 text-amber-800 bg-white
                                      hover:bg-amber-500 hover:text-white
                                      transition-all ease-in-out duration-200">Update</a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-gray-200 text-gray-700 border border-gray-300">
                            <tr>
                                <td colspan="5" class="p-2  pb-3 m-0">
                                    {{ $users->links() }}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

Now we edit the controller for the Users and add the required code for retrieving and showing
them. Open the `/app/http/controllers/UserController.php` file.

Modify the index method to retrieve the users, and paginate the results in groups of 5.

```php
    public function index()
    {
        $users = User::paginate(5)->onEachSide(2);
        return view('users.index', compact(['users']));
    }
```

Remember to add the `use App\Models\User;` line at the top of the file so that the controller
has access to the User model.

![Users Browse page with default pagination](images/Users-Browse.png)

#### show.blade.php

Next onto the show page. Create a new file `/resources/views/users/show.blade.php` and add the
following code:

```html
<?php
/**
 * Filename:    show.blade.php
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
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <dl class="grid grid-cols-6 gap-2">
                        <dt class="col-span-1">ID</dt>
                        <dd class="col-span-5">{{ $user->id }}</dd>
                        <dt class="col-span-1">Name</dt>
                        <dd class="col-span-5">{{ $user->name }}</dd>
                        <dt class="col-span-1">Added</dt>
                        <dd class="col-span-5">@displayDate($user->created_at)</dd>
                        <dt class="col-span-1">Last Logged In</dt>
                        <dd class="col-span-5">
                            @if(is_null($user->last_login_at))
                            -
                            @else
                            @displayDate($user->last_login_at)
                            @endif
                        </dd>
                        <dt class="col-span-1">Last Logged In From</dt>
                        <dd class="col-span-5">{{ $user->last_login_ip ?? '-' }}</dd>
                        <dt class="col-span-1">Actions</dt>
                        <dd class="col-span-5">
                            <form
                                action="{{ route('users.delete',['user'=>$user]) }}"
                                method="post">
                                @csrf

                                <a href="{{ route('users.edit', ['user'=>$user]) }}"
                                   class="rounded p-2 px-4 mr-4
                                           border border-green-600 text-green-800
                                           hover:bg-green-600 hover:text-white
                                           transition-all ease-in-out duration-200">Update</a>

                                <button
                                    class="border border-red-600 rounded p-1.5 px-4 mr-4
                                           text-red-800
                                           hover:bg-red-800 hover:text-white
                                           ease-in-out transition-all duration-200"
                                    type="submit">
                                    Delete
                                </button>

                            </form>
                        </dd>
                    </dl>
                    <p class="pt-6">
                        <a href="{{ url('/users') }}"
                           class="rounded p-2 px-4 mr-2
                                  border border-blue-600 text-blue-800 bg-white
                                  hover:bg-blue-500 hover:text-white hover:border-blue-800
                                  transition-all ease-in-out duration-200">Back to Users</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

```

Then modify the `UserController.php` file and add the code for the show method:

```php
    public function show(User $user)
    {
        return view('users.show', compact(['user']));
    }
```

We employ Route-Model Binding to get laravel to automatically retrieve the user we want to show
by providing the ID in the URL: `http://localhost/users/4` will retrieve and show user 4.

#### create.blade.php

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

#### edit.blade.php

Next onto the show page. Create a new file `/resources/views/users/edit.blade.php` and add the
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

The update method is going to be very similar to the add, except we will only change (patch)
the required parts fo the user's details:

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

#### Delete User Confirmation and Destroy User

Our last two actions are the confirmation of deletion of the user and the destruction of the
user from the database.

The Delete Confirmation page and method are very similar to the details page and method.

```php
    public function delete(User $user)
    {
        return view('users.delete', compact('user'));
    }
```

The `delete.blade.php` page is then:

```html
<?php
/**
 * Filename:    delete.blade.php
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
            {{ __('Delete User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <dl class="grid grid-cols-6 gap-2">
                        <dt class="col-span-1">ID</dt>
                        <dd class="col-span-5">{{ $user->id }}</dd>
                        <dt class="col-span-1">Name</dt>
                        <dd class="col-span-5">{{ $user->name }}</dd>
                        <dt class="col-span-1">Added</dt>
                        <dd class="col-span-5">{{ $user->created_at }}</dd>
                        <dt class="col-span-1">Last Logged In</dt>
                        <dd class="col-span-5">{{ '-' }}</dd>
                        <dt class="col-span-1">Actions</dt>
                        <dd class="col-span-5">
                            <form
                                action="{{ route('users.destroy',['user'=>$user]) }}"
                                method="post">
                                @csrf
                                @method('delete')

                                <a href="{{ route('users.index', ['user'=>$user]) }}"
                                   class="rounded p-2 px-4 mr-4
                                           border border-green-600 text-green-800
                                           hover:bg-green-600 hover:text-white
                                           transition-all ease-in-out duration-200">Cancel
                                    Delete</a>

                                <button
                                    class="border border-red-600 rounded p-1.5 px-4 mr-4
                                           text-red-800
                                           hover:bg-red-800 hover:text-white
                                           ease-in-out transition-all duration-200"
                                    type="submit">
                                    Confirm
                                </button>

                            </form>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    </x-guest-layout>
```

Now the `destroy` method completes the 'user destruction' thus:

```php
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
```

That is the basics of the Users pages. The Task pages are a little simpler as we do not have so
much to complete on each page.
