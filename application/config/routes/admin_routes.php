<?php
$route['admin'] = 'admin/DashboardController';
$route['admin/dashboard'] = 'admin/DashboardController';
$route['admin/login']['get'] = 'admin/LoginController/login';
$route['admin/login']['post'] = 'admin/LoginController/postLogin';
$route['admin/setting-group-role'] = 'admin/SettingGroupRoleController';
$route['admin/setting-group-role/update']['post'] = 'admin/SettingGroupRoleController/ajaxUpdate';