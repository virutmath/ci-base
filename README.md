# ci-base
Codeigniter startup project

#Require 
- PHP > 5.4
- Composer

#Support
- MY_Model CRUD query (like Eloquent in Laravel)
- Admin CMS with basic authen, basic role. Front base on Bootstrap AdminLTE2
- Codeigniter-CLI of @kenjii
- Auto-generate code with `$ php cli cms create table_name` (this command will generate admin controller and client controller and views file)

#Setup
- Clone project
- Run `$ composer install`
- Run `$ php vendor/kenjis/codeigniter-cli/install.php` for install kenjis project
- In ci_instance.php, config your `system` folder
- Run `$ php cli migrate` and `$ php cli seed`
