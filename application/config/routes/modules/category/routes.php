<?php
$route['admin/category'] = 'admin/CategoryController';
$route['admin/category/add']['get'] = 'admin/CategoryController/add';
$route['admin/category/add']['post'] = 'admin/CategoryController/postAdd';
$route['admin/category/edit/(:num)']['get'] = 'admin/CategoryController/edit/$1';
$route['admin/category/edit/(:num)']['post'] = 'admin/CategoryController/postEdit/$1';
$route['admin/category/delete']['post'] = 'admin/CategoryController/ajaxDelete';
$route['admin/category/update']['post'] = 'admin/CategoryController/ajaxUpdate';