# Laravel generator
Simple files generator used to easily create multiple files at once.

* [Installation](#installation)
* [Usage](#usage)
* [Example](#example)


## Installation
The package can be installed using composer.
```bash
composer require-dev vleroy/laravel-gen
```


## Usage
```bash
# This will prompt you for {replacement values} found in folder's files
php artisan gen <folder name>
```
### Files structure
The files structure in the `resources/templates/<folder>` folder will be replicated in the root folder of the project.
```bash
├── app
│   ├── ...
├── artisan
├── bootstrap
│   ├── ...
├── composer.json
├── config
│   ├── ...
├── database
│   ├── ...
├── public
│   ├── ...
├── resources
│   ├── ...
│   ├── templates
│   │   └── Model
│   │       ├── app
│   │       │   ├── Http
│   │       │   │   └── Controllers
│   │       │   │       └── {ModelName}Controller.php
│   │       │   ├── Models
│   │       │   │   └── {ModelName}.php
│   │       │   └── Services
│   │       │       └── {ModelName}Service.php
│   │       └── routes
│   │           └── {model_name}.php
├── routes
│   ├── ...
├── server.php
├── storage
│   ├── ...
```


## Example
### Artisan
- The `{my_value}` pattern indicates a dynamic value.
- The command `php artisan <folder>` will prompt you for a replacement value.
- These replacement values can be used in paths and in files content.
```bash
$ php artisan gen Model                                                          

 ModelName:
 > Post

 model_name:
 > post

 model_table:
 > posts
```

### Destination file
```php
<?php
// Source -> resources/templates/Model/app/Models/{ModelName}.php
// Destination -> app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";
    
    ...
}

```
