# LiteMVC

## Installation

### Required PHP version 8.1 or higher 

Run the command: `git clone https://github.com/DMITRII1548/LiteMVC.git`

Open your project and run the command in the public directory `php -S localhost:8000`

Open your browser and paste the following URL `http://localhost:8000`

### Notification: 
If you use Apache or Nginx, you must set a redirect to the `public/index.php` file

## About LiteMVC
LiteMVC is a simple MVC framework. It has validation, routing, ORM, middlewares, controllers and views functionality. You should use it if you need to write a simple SPA application with MySQL.

# Documentation

## Basic Concepts
1. Routing
2. Controllers
3. Views
4. Models
5. Middlewares 
6. Requests

## Routing 
Routing is needed for determining the URL and running the required action in the controller.

### You need to register all routes in routes/web.php

### Usage example:
```php 
// routes/web.php
Route::get('welcome', [\App\Http\Controllers\WelcomeController::class, 'index']);
````

This route will handle the action index of `\App\Http\Controllers\WelcomeController`.

### Class Route has the following methods for your routing and simple CRUD

| Method    | Action   |
|-----------|----------|
| get       | index, show, edit   |
| post      | store   |
| patch, put  | update   |
| delete  | destroy |

### Also you can set a name for your route and middleware 

```php
Route::get('welcome', [\App\Http\Controllers\WelcomeController::class, 'index'])
    ->name('welcome')
    ->middleware('one.is.one');
```

In addition, you can get the Route path by name
```php
Route::getRoutePathByName('route name');
```

You can also redirect to another page or website 

Redirect by route name 
```php 
Route::redirectByRouteName('Route name', ['params']);
```

Or URL 
```php 
Route::redirect('url', ['params']);
```

You can set parameters in your route path instead of using GET construction in PHP and LiteMVC engine automatically sets them in action params

### Example
```php
// web.php
Route::get('/posts/{id}', [PostController::class, 'show']);

// app/Http/Controllers/PostController.php action show
public function show($id) 
{
    // ... code
}
```

## Controllers
Controllers are a place where you can handle your logic.

You should create your controllers in the app/Http/Controllers directory 

### Example 
```php
<?php 

namespace App\Http\Controllers;

use App\LMVC\View\View;
use App\Models\Post;

class PostController 
{
    public function index(): string
    {
        $postModel = new Post();
        $posts = $postModel->all();

        return View::view('post.index', [
            'posts' => $posts
        ]);
    }
}
```

### Example standard CRUD naming for actions
| Route          | Route Method   | Action            | Description            |
|----------------|-----------------|-------------------|------------------------|
| posts          | get             | index             | Get all posts          |
| posts/{id}     | get             | show              | Show one post          |
| posts/create   | get             | create            | Create post page       |
| posts          | post            | store             | Create post            |
| posts/{id}/edit| get             | edit              | Edit post page         |
| posts/{id}     | patch/put       | update            | Update post            |
| posts/{id}     | delete          | destroy            | Delete post            |

### Warning
If you use any route method except 'get', you need to add a CSRF token in your form with this construction: 

```php
<input type="hidden" name="csrf" value="<?php csrf() ?>">
```

Also, if you want the methods patch, put, and delete, you need to use this construction in your form
```php 
<?php method('method name') ?>
```
The method 'method' will create an input on your page.

## Views
Views are pages functionality

You need views to show your pages

All views are kept in the resources/views directory 

You can return a View in your controller and compact variables in your controller with this construction

```php
$post = $postModel->find(1);

return View::view('welcome', [
    'post' => $post 
]);
```

Then you can just get your variables in your views

### Example
```php
// resources/views/welcome.php
Post title: <?php echo $post['title'] ?>
```

## Models
Models are needed for working with the database. It provides convenient functionality for MySQL queries

### To use a model, You need 
Create a model in the 'app/Models' directory, extend the Model class and write the $table property. 
In addition, you need to set the connection in the `app/LMVC/Model/Model` class in the __construct method 

### Example 
```php 
<?php 

namespace App\Models;

use App\LMVC\Model\Model;

class User extends Model
{
    protected string $table = 'users';
}
```

Then you need to initialize a model in a controller

```php
$userModel = new User();
```

After that, you can perform queries in the database

## Model methods 

### all(int $limit = 50): array
Return all items as an array

Returns an array

Has one attribute $limit by default = 50

### find(int $id): array|false|null
Return one item by id 

Returns an array, false, or null

Has one required attribute $id

### where(string $paramName, mixed $paramValue, string $operand = '='): array|false|null
Return an item by paramName and paramVlue with the operand

Returns an array, false, or null

Has two required attributes $paramName, $paramValue, and an optional operand by default equal to '='

### create(array $data): bool
Save an item in the table

Returns true or false

Has one attribute where you need to compact data 

Example:
```php
$userModel->create([
    'name' => $name,
    'password' => $password,
    'email' => $email
]);
```

### update(int $id, array $data): bool
Find an item by id and then update it

Returns true or false

Has two required parameters $id of the needed item and $data contents keys and values in an array

Example:
```php
$userModel->update(1, [
    'name' => 'John'
]);
```

### delete(int $id): bool
Find an item by id and then destroy it

Returns true or false

Has one required parameter $id of the item

## Middlewares 
Middleware is needed if you want to protect routes or another functionality

You should create a middleware in the `app/Http/Middleware` directory

### Example 
```php 
<?php 

namespace App\Http\Middlewares;

class OneEqualOne
{
    public function handle(): void
    {
        if (1 !== 1) {
            header('HTTP/1.1 403 Forbidden');
            
            die();
        }
    }
}
```

Then you need to register your middleware in the `app/Http/Kernel.php` file in the $middlewares array

```php
// Register middlewares
public array $middlewares = [
    // 'middleware_name' => 'class'
    'one.is.one' => OneEqualOne::class
];
```

After that, you can use middlewares in your routes and controllers 

```php
// routes
Route::get('/', [WelcomeController::class, 'index'])->middleware('one.is.one');

// Controller 
(new OneEqualOne)->handle();
```

## Requests
Requests are needed for validating post queries 

For it, you need to initialize the Request class in your controller
```php 
$request = new Request();
```

After that, you can use two methods 

### all(): array
Return all params from $_POST 

### validate(array $rules): array
This method will validate your data by rules and then return them as an array

Example: 
```php
$request->validate([
    'title' => 'required|string',
    'image' => 'required|image',
    'quantity' => 'required|int'
])
```

By default, you have only five validation rules: required, string, int, float, and image, but you can add more rules

To do that, create a rule class in `app/Rules` and create a `validate` method with two attributes: $attribute and $value. And handle the validation 

### Example 
```php
<?php

namespace App\Rules;

class Image
{
    public function validate(string $attribute, mixed $value): void
    {
        if (
            !(
                $value['type'] === 'image/png'
                || $value['type'] === 'image/jpeg'
                || $value['type'] === 'image/jpg'                
            )
        ) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $_SESSION['errors']['validation'][$attribute] = "attribute $attribute must be an image type";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header('HTTP/1.0 322 Forbidden');
            }

            die();
        }
    }
}
```

### If you want to write validation rules in a class and then initialize it, you need to do the following:
Create a class in `app/Http/Request` and extend it from `FormRequest`

### Example
```php 
<?php 

namespace App\Http\Requests;

use App\LMVC\Request\FormRequest;

class StoreRequest extends FormRequest
{
    protected function rules(): array
    {
        return [
            'title' => 'required|string'
        ];
    }
}
```

Now you need to initialize this class in your controller and run the validated method. After this, you will get your validated fields

### Example
```php
$request = new StoreRequest();

$request->validated();
```

Also, you can use Request class functionality in your request class.

## License
The LiteMVC framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).