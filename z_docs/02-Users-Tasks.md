# Migrations and Controller Generation for Users & Tasks

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

### Next: [Routing & Navigation](03-Routing-and-Navigation.md)
