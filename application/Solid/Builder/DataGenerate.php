<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/22/2016
 * Time: 11:09 AM
 */

namespace Solid\Builder;


class DataGenerate
{
	public static function generateTrustLimitData($total, $offset, $pageSize)
	{
		if ($total < $offset) {
			$offset = floor($total / $pageSize);
			$pageSize = $total - $offset;
		} elseif ($total == $offset) {
			$offset = floor($total / $pageSize) - 1;
			$offset = $offset > 0 ? $offset : 0;
		} elseif ($total > $offset && $total < $offset + $pageSize) {
			$pageSize = $total - $offset;
		}
		return ['offset' => $offset, 'pageSize' => $pageSize];
	}
}