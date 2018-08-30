<h2>The app is made with Laravel Framework 5.6 and VueJS 2 Framework</h2>
<h3>Steps to set up the app:</h3>
<ol>
<li>Clone the repository</li>
<li>
Enter the project directory, open a terminal and run the commands:
<ul>
<li>composer install</li>
<li>npm install</li>
<li>npm run dev</li>
</ul>
</li>
<li>Create a database based on the databese dump that I've sent you </li>
<li>In the root of the project directory rename the file .env.example to .env and inside enter your DB credentials and DB name</li>
<li>Login credentials:
<ul>
<li>email: tester@email.com</li>
<li>password: tester</li>
</ul>
</li>
<li>I recommend to create a virtual host for better experience or run the command: php artisan serve.</li>
</ol>

<hr>

<h3>Structure of the app:</h3>
<ol>
<li>Backend part:
<ul>
<li>The main controller (HomeController) can be found in the app/Http/Controllers directory</li>
<li>In the app directory there are two models User and Task, but you need to pay attention on the Task model, because there the DB queries are implemented</li>
<li>In the app/routes directory there's a file web.php where the used routes are defined</li>
<li>The authentication is self generated by Laravel</li>
</ul>
</li>
<li>
Frontend part
<ul>
<li>In the app/resources/views directory you can find the blades (views). The blades in the auth and layouts subdirectories are generated with the Laravel authentication</li>
<li>Inside the home.blade and overview.blade are included the VueJS components</li>
<li>The VueJS components can be found in the app/resources/assets/js directory</li>
<li>In the app.js are loaded the VueJS framework itself and the components that are used in the blades</li>
<li>In the components subdirectory there are the HomeComponent.vue and the OverviewComponent.vue where the html/css/js is implemented</li>
</ul>
</li>
</ol>
