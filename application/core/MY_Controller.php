<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class MY_Controller
 * @property Blade $blade
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Form_validation $form_validation
 * @property CI_Encryption encryption
 * @property CI_DB $db
 * @property CI_Loader $load
 * @property array $dataView
 */
class MY_Controller extends CI_Controller {

    public $isPjax = false;
	public $isAjax = false;
    public $dataView = [];

    public function before() {

    }

    public function after() {

    }

    public function _remap($method, $args) {
        // Call before action
	    $arg_method = array_merge([$method],$args);

	    call_user_func_array(array($this, 'before'), $arg_method);

        if ( method_exists($this, $method) )
        {
            //  Call the method
            call_user_func_array(array($this, $method), $args);
        }
        else
        {
            show_404(self::class. '/'.$method);
        }

        // Call after action
	    call_user_func_array(array($this, 'after'), $arg_method);
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->library('blade');
        $this->load->library('encryption');
        $this->load->library('session');
	    $this->load->library('form_validation');
        $this->load->helper('translate');
        $this->load->helper('inflector');
        $this->load->helper('string');
        $this->load->helper('form');
        $this->load->helper('common');
        $this->load->helper('template');
        $this->load->helper('url');
        $this->load->helper('rewrite_url');
        $this->output->enable_profiler($this->config->item('enable_profiler'));

        $this->encryption->initialize([
            'cipher' => 'aes-256',
            'mode' => 'ctr',
            'key' => hex2bin('124d65cd6a939e1116425c5d47f8a2e9')
        ]);

        //check pjax
        if($this->input->is_ajax_request()) {
	        $this->isAjax = true;
            $headers = $this->input->request_headers();
            if(isset($headers['X-PJAX'])) {
                $this->isPjax = true;
            }
        }
    }


	/**
	 * @param $filename
	 * @return bool
	 */
    protected function _saveFileFromTmpUpload($filename) {
        if(!$filename) {
            return false;
        }
	    //check extension
	    $tmp = explode('.',$filename);
	    if(count($tmp) < 2) {
		    log_message('error','Invalid file '. $filename);
		    return false;
	    }
	    $ext = array_pop($tmp);
	    if(in_array($ext,['mp3,wma,wav,ogg'])) {
		    $pathDir = RewriteUrlFn\generate_dir_upload($filename,'audio');    
	    }else{
		    $pathDir = RewriteUrlFn\generate_dir_upload($filename,'original');
	    }
        
        try {
	        return rename(TEMP_UPLOAD_DIR . '/' .$filename,$pathDir . $filename);
        }catch (Exception $e) {
	        log_message('error', $e->getTraceAsString());
	        return false;
        }
    }

	/**
	 * @param $data
	 * @return array
	 */
    protected function parseMultiLevelData($data) {
        $list = [];
        foreach($data as $item) {
            $list[] = $item;
            if($item->child) {
                $parse_child = $this->parseMultiLevelData($item->child);
                foreach($parse_child as $child) {
                    $child->publicName = ' |___ ' . $child->name;
                    $list[] = $child;
                }
            }
        }
        return $list;
    }
}
//require base controller
require_once APPPATH . 'controllers/AuthController.php';
require_once APPPATH . 'controllers/AdminController.php';