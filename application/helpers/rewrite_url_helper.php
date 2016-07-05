<?php
/**
 * Created by PhpStorm.
 * User: KienDang
 * Date: 6/13/2016
 * Time: 3:38 PM
 */
namespace RewriteUrlFn;


function get_picture_path($picture_name, $type = 'original')
{
    if (!$picture_name) {
        return false;
    }
    $x = explode('_', $picture_name);
    $time = preg_replace('/[^0-9]*/', '', end($x));
    $time = getdate($time);
    $path = '/files/images/' . $time['year'] . '/' . $time['mon'] . '/' . $time['mday'] . '/' . $type . '/' . $picture_name;
    return $path;
}

function get_audio_path($filename)
{
	if (!$filename) {
		return false;
	}
	$x = explode('_', $filename);
	$time = preg_replace('/[^0-9]*/', '', end($x));
	$time = getdate($time);
	$path = '/files/audio/' . $time['year'] . '/' . $time['mon'] . '/' . $time['mday'] . '/' . $filename;
	return $path;
}

function generate_dir_upload($filename, $type)
{
    if (!$filename) {
        return false;
    }
	//remove extension in filename
	$filename = implode(array_pop(explode('.',$filename)));
	$filename = explode('_', $filename);
    $time = preg_replace('/[^0-9]*/', '', end($filename));

    $time = getdate($time);
	log_message('error',implode('-',$time));
	if($type != 'audio') {
		$path_dir = BASEPATH . '../public/files/images/' . $time['year'] . '/' . $time['mon'] . '/' . $time['mday'] . '/' . $type . '/';
	}else{
		$path_dir = BASEPATH . '../public/files/audio/' . $time['year'] . '/' . $time['mon'] . '/' . $time['mday'] . '/';
	}

    if (file_exists($path_dir)) {
        return $path_dir;
    }
    if (mkdir($path_dir, 0777, 1)) {
        return $path_dir;
    } else {
        return false;
    }
}

function admin_dashboard()
{
    return '/admin';
}
function admin_login()
{
    return '/admin/login';
}

function admin_config_module()
{
    return '/admin/config-module';
}

function admin_add_module()
{
    return '/admin/config-module/add';
}

function admin_edit_module($id) {
    return '/admin/config-module/edit/'.$id;
}

function admin_delete_module() {
    return '/admin/config-module/delete';
}