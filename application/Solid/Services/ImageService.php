<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/28/2016
 * Time: 9:21 AM
 */

namespace Solid\Services;
use Intervention\Image\ImageManager;

class ImageService
{
	public static function squareImage($filename) {
		//get the path of image
		$path_dir = dirname(BASEPATH . '../public' . \RewriteUrlFn\get_picture_path($filename));
		$parent_dir = dirname($path_dir);
		//make dir
		if(!file_exists($parent_dir . '/square/')) {
			mkdir($parent_dir . '/square/');
		}
		$manager = new ImageManager();
		$imageSrc = $manager->make($path_dir . '/' . $filename);
		//get square size
		$square_fit = min($imageSrc->width(),$imageSrc->height());
		//fit ratio 1:1
		$imageSrc->fit($square_fit);
		//save to square dir
		$imageSrc->save($parent_dir . '/square/' . $filename);
		return $parent_dir . '/square/' . $filename;
	}
}