<?php
namespace CommonHelperFn;
function check_logged()
{
	return isset($_SESSION['isLoggedIn']);
}
function unique_id($prefix = '')
{
	return str_replace('.','',uniqid($prefix,true));
}
/**
 * Check development environtment
 * @return bool
 */
function isDevEnv()
{
	return ENVIRONMENT == 'development';
}

function ajaxUploadFile($attribute = array())
{
	$default = array(
		'label' => '',
		'name' => '',
		'csrf_token_name' => '',
		'csrf_token_hash' => '',
		'id' => '',
		'value' => '',
		'browse_id' => '',
		'viewer_id' => ''
	);
	_extend_attributes($attribute, $default);
	$default['browse_id'] OR $default['browse_id'] = unique_id('file_upload_browse_btn_');
	$default['viewer_id'] OR $default['viewer_id'] = unique_id('file_upload_viewer_btn_');
	$default['csrf_token_name'] OR $default['csrf_token_name'] = get_csrf_token_name();
	$default['csrf_token_hash'] OR $default['csrf_token_hash'] = get_csrf_token_hash();

	$html = '<div class="form-group">
					<label>' . $default['label'] . '</label>
					<div class="ajax-upload-container">
						<div class="row">
							<div class="col-xs-6">
								<input type="file" id="' . $default['browse_id'] . '"/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 ajax-upload-viewer">
								<input type="hidden" name="' . $default['name'] . '" id="' . $default['id'] . '" value=""/>
								<img src="' . $default['value'] . '" alt="" id="' . $default['viewer_id'] . '"/>
							</div>
						</div>
					</div>
				</div>
				<script>
				document.addEventListener("DOMContentLoaded", function(event) {
				  //do work
				  var uploadScript = new UploaderScript();
				  uploadScript.init({
						csrf_token : {' . $default['csrf_token_name'] . ': "' . $default['csrf_token_hash'] . '"},
						browse_button : "' . $default['browse_id'] . '",
						image_wrapper : "' . $default['viewer_id'] . '",
						loading : "' . $default['viewer_id'] . '"
					},function(){
						$("#' . $default['id'] . '").val(uploadScript.config.file_name);
					});
				});
				</script>
				';
	return $html;
}

function ajaxUploadAudioFile($attribute = [
	'label' => '',
	'name' => '',
	'csrf_token_name' => '',
	'csrf_token_hash' => '',
	'id' => '',
	'value' => '',
	'browse_id' => '',
	'viewer_id' => ''])
{
	$default = array(
		'label' => '',
		'name' => '',
		'csrf_token_name' => '',
		'csrf_token_hash' => '',
		'id' => '',
		'value' => '',
		'browse_id' => '',
		'viewer_id' => ''
	);
	_extend_attributes($attribute, $default);
	$default['browse_id'] OR $default['browse_id'] = unique_id('file_upload_browse_btn_');
	$default['viewer_id'] OR $default['viewer_id'] = unique_id('file_upload_viewer_btn_');
	$default['csrf_token_name'] OR $default['csrf_token_name'] = get_csrf_token_name();
	$default['csrf_token_hash'] OR $default['csrf_token_hash'] = get_csrf_token_hash();

	$html = '<div class="form-group">
					<label>' . $default['label'] . '</label>
					<div class="ajax-upload-container">
						<div class="row">
							<div class="col-xs-6">
								<input type="file" id="' . $default['browse_id'] . '"/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6 ajax-upload-viewer">
								<input type="hidden" name="' . $default['name'] . '" id="' . $default['id'] . '" value=""/>
								<audio controls src="' . $default['value'] . '" id="' . $default['viewer_id'] . '" style="opacity:0">
								</audio>
							</div>
						</div>
					</div>
				</div>
				<script>
				document.addEventListener("DOMContentLoaded", function(event) {
				  //do work
				  var uploadScript = new UploaderScript();
				  uploadScript.init({
				        file_ext : "mp3,ogg,wav",
						csrf_token : {' . $default['csrf_token_name'] . ': "' . $default['csrf_token_hash'] . '"},
						browse_button : "' . $default['browse_id'] . '",
						image_wrapper : "' . $default['viewer_id'] . '",
						loading : "' . $default['viewer_id'] . '",
						max_file_size: "40mb"
					},function(){
						$("#' . $default['id'] . '").val(uploadScript.config.file_name);
						$("#' . $default['viewer_id'] . '").css({opacity : 1})
					});
				});
				</script>
				';
	return $html;
}

/**
 * @param string[] $errors
 * @return string
 */
function showFormError($errors = [])
{
	$str = '';
	if (!empty($errors)) {
		$str = '<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4><i class="icon fa fa-warning"></i> Đã có lỗi xảy ra!</h4>';
		foreach ($errors as $err) {
			$str .= '<p> - ' . $err . '</p>';
		}
		$str .= '</div>';
	}
	return $str;
}

function _extend_attributes($attributes, &$default)
{
	if (is_array($attributes)) {
		foreach ($default as $key => $val) {
			if (isset($attributes[$key])) {
				$default[$key] = $attributes[$key];
				unset($attributes[$key]);
			}
		}
		if (count($attributes) > 0) {
			$default = array_merge($default, $attributes);
		}
	}
	return $default;
}

function get_csrf_token_hash()
{
	$CI =& get_instance();
	return $CI->security->get_csrf_hash();
}

function get_csrf_token_name()
{
	$CI =& get_instance();
	return $CI->security->get_csrf_token_name();
}