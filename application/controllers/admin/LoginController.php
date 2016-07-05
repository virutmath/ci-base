<?php
use Solid\Repositories\AdminUserRepository as AdminUserRepository;

/**
 * Class LoginController
 * @property Admin_user_model $adminUserModel
 */
class LoginController extends AuthController
{
    /**
     * @var \Solid\Collections\AdminUser $dataLogin
     */
    protected $dataLogin;
    protected $passwordSave;
    /**
     * @var AdminUserRepository $adminUserRepository
     */
    private $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepository)
    {
        parent::__construct();
        $this->load->model('admin_user_model', 'adminUserModel');
        $this->adminUserRepository = $adminUserRepository;
        $this->adminUserRepository->setModel($this->adminUserModel);
    }

    public function login()
    {
        $this->dataView['login_success'] = RewriteUrlFn\admin_dashboard();
        $this->blade->render('admin.login', $this->dataView);
    }

    public function postLogin()
    {
        $this->dataView['errors'] = [];
        $this->dataView['login_success'] = RewriteUrlFn\admin_dashboard();
        $redirectUrl = $this->input->get('redirect',TRUE);
        $redirectUrl = $redirectUrl ?: RewriteUrlFn\admin_dashboard();
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $adminUser = $this->adminUserRepository->findByLoginname($username);
        if (empty($adminUser)) {
            $this->dataView['errors'][] = 'Account not found';
        }elseif (!$this->checkHashPassword($password, $adminUser->getHash(), $adminUser->getPassword())) {
            $this->dataView['errors'][] = 'Password not correct';
        }
        if($this->dataView['errors']) {
            $this->blade->render('admin.login',$this->dataView);
        }else{
            $this->dataLogin = $adminUser;
            $this->passwordSave = $password;
            $this->setSession();
            redirect($redirectUrl);
        }
    }

    private function setSession()
    {
        $this->session->set_userdata([
            'admin' => $this->dataLogin->getLoginname(),
            'adminName'=>$this->dataLogin->getName(),
            'password' => $this->passwordSave,
            'isAdmin' => $this->dataLogin->getIsAdmin(),
            'groupId'=>$this->dataLogin->getGroup()->getId()
        ]);
    }
}