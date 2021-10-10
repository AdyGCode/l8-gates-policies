<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seedTasks = [
            [
                'name' => 'Roadmap Document: Defining Web Application, Purpose, Goals and Direction',
                'description' => 'This initial task is an important part of the process. It
                requires putting together the Web Application project goals and purpose.',
                'user_id' => 12,
                'created_at' => now(),
            ],
            [
                'name' => 'Researching and Defining Audience Scope and Security Documents',
                'description' => 'This task requires researching the audience/users, and
                prospective clients (if any), and creating an Analytic Report.',
                'created_at' => now(),
                'user_id' => 10,
            ],
            [
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
                'user_id' => 11,
            ],
            [
                'name' => 'Third Party Vendors Identification, Analysis and Selection',
                'description' => 'This task requires researching, identifying and selection of
                third party vendors, products and services.',
                'created_at' => now(),
                'user_id' => 12,
            ],
            [
                'name' => 'Technology Selection, Technical Specifications, Web Application Structure and Timelines',
                'description' => 'This document is the blueprint of the technology and platform
                selection, development environment, web application development structure and
                framework.',
                'created_at' => now(),
                'user_id' => 14,
            ],
            [
                'name' => 'Application Visual Guide, Design Layout, Interface Design, Wire framing',
                'description' => 'One of the main ingredients to a successful project is to put
                together a web application that utilizes a user\'s interactions, interface and
                elements that have a proven record for ease of use, and provide the best user
                experience.',
                'created_at' => now(),
                'user_id' => 11,
            ],
            [
                'name' => 'Web Application Development',
                'description' => 'The application\'s Interface Design is turned over to the
                 Development Team.',
                'created_at' => now(),
                'user_id' => 12,
            ],
            [
                'name' => 'Beta Testing and Bug Fixing',
                'description' => 'Vigorous quality assurance testing help produce the most
                secure and reliable web applications.',
                'created_at' => now(),
                'user_id' => 11,
            ],
            [
                'name' => 'Client Side Scripting / Coding ',
                'description' => 'Client Side Scripting is the type of code that is executed or
                interpreted by browsers.',
                'created_at' => now(),
                'user_id' => 5,
            ],
            [
                'name' => 'Server Side Scripting / Coding',
                'description' => 'Server Side Scripting is the type of code that is executed or
                interpreted by the web server.',
                'created_at' => now(),
                'user_id' => 6,
            ],
            [
                'name' => 'Web Application Frameworks - Benefits and Advantages',
                'description' => 'Program actions and logic are separated from the HTML, CSS and
                 design files. This helps designers (without any programming experience) to be
                 able to edit the interface and make design changes without help from a
                 programmer.',
                'created_at' => now(),
                'user_id' => 6,
            ],
        ];
        foreach ($seedTasks as $seed) {
            Task::create($seed);
        }
    }
}
