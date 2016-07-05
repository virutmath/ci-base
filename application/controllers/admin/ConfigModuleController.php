<?php
use Solid\Repositories\AdminUserRepository as AdminUserRepository;
use Solid\Repositories\AdminModuleRepository as AdminModuleRepository;
use Solid\Builder\TableAdmin as TableAdmin;

/**
 * Class ConfigModuleController
 * @property Admin_user_model $adminUserModel
 * @property CI_Loader $load
 */
class ConfigModuleController extends AdminController
{

	protected $adminModule = 'config-module';

    public function __construct(AdminUserRepository $adminUserRepository, AdminModuleRepository $adminModuleRepository)
    {
        parent::__construct($adminUserRepository, $adminModuleRepository);
        //check super admin
        if (!$this->checkSuperAdmin()) {
            $this->accessDenied();
        }
    }

    public function index()
    {
        //list module
        $moduleList = $this->getModuleList();
        //parse child
        $moduleList = $this->parseMultiLevelData($moduleList);
        $this->dataView['moduleList'] = $moduleList;
        //table admin
        $tableAdmin = TableAdmin::initialize($moduleList);
        $tableAdmin->column('id', 'ID');
        $tableAdmin->column('publicName', 'Module name');
        $tableAdmin->column('controller', 'Controller');
        $tableAdmin->column('method', 'Method');
        $tableAdmin->column('id', 'Edit', 'edit');
        $tableAdmin->column('id', 'Delete', 'delete');
        $tableAdmin->setEditLink(RewriteUrlFn\admin_edit_module('$id'));
        $tableAdmin->setDeleteLink(RewriteUrlFn\admin_delete_module());
        $this->dataView['tableAdmin'] = $tableAdmin->render();
        $this->blade->render('admin.config-module', $this->dataView);
    }   

    public function postAdd()
    {
        global $moduleName,$controllerName,$methodName,$parentId;
        //extract parameter from form
        $this->__prepareDataForm();

        if (!$moduleName) {
            redirect(RewriteUrlFn\admin_config_module());
        }
        //TODO refactor - add order and active config
        $this->adminModuleRepository->setDataInsert($moduleName, $controllerName, $methodName, $parentId);
        try {
            //TODO need refactor - handle result after add
            $result = $this->adminModuleRepository->save();
            redirect(RewriteUrlFn\admin_config_module());
        } catch (Exception $e) {
            log_message('error', $e->getTraceAsString());
            redirect(RewriteUrlFn\admin_config_module());
        }
    }


    public function edit($moduleId)
    {
        if (!$moduleId) {
            $this->show404Page();
        }
        $detail = $this->adminModuleRepository->findById($moduleId);
        $this->dataView['moduleName'] = $detail->getName();
        $this->dataView['controllerName'] = $detail->getController();
        $this->dataView['methodName'] = $detail->getMethod();
        $this->dataView['parentId'] = $detail->getParentId();

        //list module
        $moduleList = $this->getModuleList();
        //parse child
        $moduleList = $this->parseMultiLevelData($moduleList);
        $this->dataView['moduleList'] = $moduleList;
        //table admin
        $tableAdmin = TableAdmin::initialize($moduleList);
        $tableAdmin->column('id', 'ID');
        $tableAdmin->column('publicName', 'Module name');
        $tableAdmin->column('controller', 'Controller');
        $tableAdmin->column('method', 'Method');
        $tableAdmin->column('id', 'Edit', 'edit');
        $tableAdmin->column('id', 'Delete', 'delete');
        $tableAdmin->setEditLink(RewriteUrlFn\admin_edit_module('$id'));
        $tableAdmin->setDeleteLink(RewriteUrlFn\admin_delete_module());
        $this->dataView['tableAdmin'] = $tableAdmin->render();
        $this->dataView['formActionUrl'] = RewriteUrlFn\admin_edit_module($moduleId);
        $this->blade->render('admin.config-module', $this->dataView);
    }

    public function postEdit($moduleId)
    {
        //check detail
        $detail = $this->adminModuleRepository->findById($moduleId);
        //TODO refactor - check error when module not exist
        if(!$detail) {
            $this->show404Page();
        }

        global $moduleName,$controllerName,$methodName,$parentId;
        //extract parameter from form
        $this->__prepareDataForm();
        //TODO refactor - add order and active config
        if (!$moduleName) {
            redirect(RewriteUrlFn\admin_config_module());
        }
        $dataUpdate = [
            'id'=>$moduleId,
            'name'=>$moduleName,
            'controller'=>$controllerName,
            'method'=>$methodName,
            'parent_id'=>$parentId
        ];
        try {
            //TODO need refactor - handle result after update
            $result = $this->adminModuleRepository->updateById($dataUpdate);
            redirect(RewriteUrlFn\admin_edit_module($moduleId));
        } catch (Exception $e) {
            log_message('error', $e->getTraceAsString());
            redirect(RewriteUrlFn\admin_edit_module($moduleId));
        }
    }

    public function ajaxDelete() {
        $id = $this->input->post('recordId',TRUE);
        //delete record
        $result = $this->adminModuleRepository->deleteById($id);
        if($result) {
            echo json_encode(['success'=>'Bạn đã xóa thành công']);
            die();
        }else{
            echo json_encode(['error'=>'Không thể thực hiện tác vụ này']);
            die();
        }
    }

    private function __prepareDataForm()
    {
        global $moduleName,$controllerName,$methodName,$parentId;
        extract([
            'moduleName' => $this->input->post('name', TRUE),
            'controllerName' => $this->input->post('controller', TRUE),
            'methodName' => $this->input->post('method', TRUE),
            'parentId' => $this->input->post('parent_id', TRUE)
        ]);
    }
}