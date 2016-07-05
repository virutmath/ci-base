<?php
use Solid\Repositories\AdminUserRepository as AdminUserRepository;
use Solid\Repositories\AdminModuleRepository as AdminModuleRepository;
use Solid\Repositories\CategoryRepository as CategoryRepository;
use Solid\Builder\TableAdmin as TableAdmin;

/**
 * Class CategoryController
 * @property Category_model $categoryModel
 */
class CategoryController extends AdminController {
	protected $pageSize = 30;
	/**
	 * @var string $adminModule Module in modules
	 */
	protected $adminModule = 'category';
	/**
	 * @var CategoryRepository $categoryRepository
	 */
	private $categoryRepository;
	public function __construct(AdminUserRepository $adminUserRepository,
								AdminModuleRepository $adminModuleRepository,
								CategoryRepository $categoryRepository)
	{
		parent::__construct($adminUserRepository, $adminModuleRepository);
		$this->categoryRepository = $categoryRepository;
        $this->load->model('category_model','categoryModel');
        $this->categoryRepository->setModel($this->categoryModel);
	}

	public function index() {
		$allCat = $this->categoryRepository->getAllCategory(0);
		$this->dataView['list'] = $this->parseMultiLevelData($allCat);
		$listIcon = \Solid\Collections\Category::ICON;
		$tableConfig = [
			'module'=>$this->adminModule
		];
		$table = new TableAdmin($this->dataView['list'],$tableConfig);
		$table->column('id','ID');
		$table->column('image','Ảnh trang chủ','image');
		$table->column('publicName','Tên danh mục');
		$table->columnDropdown('icon','Biểu tượng',$listIcon);
		$table->column('active','Kích hoạt','checkbox');
		$table->column('id','Edit','edit');
		$table->column('id','Delete','delete');

		$this->dataView['tableAdmin'] = $table->render();
		$this->blade->render('admin.category.index',$this->dataView);
	}


	public function add() {
		$this->dataView['listIcon'] = \Solid\Collections\Category::ICON;
        $allCat = $this->categoryRepository->getAllCategory(0);
        $this->dataView['list'] = $this->parseMultiLevelData($allCat);
		$this->blade->render('admin.category.add',$this->dataView);
	}

	public function postAdd() {
		$data = [
			'name' => $this->input->post('category_name', TRUE),
			'parent_id' => $this->input->post('category_parent'),
			'icon' => $this->input->post('category_icon'),
			'active' => $this->input->post('category_active'),
			'description' => $this->input->post('category_description', TRUE),
			'keyword' => $this->input->post('category_keyword', TRUE),
			'title' => $this->input->post('category_title', TRUE)
		];
		$imagePost= $this->input->post('image', TRUE);
		if($imagePost) {
			$data['image'] = $imagePost;
		}

        try {
            $result = $this->categoryRepository->addCategory($data);
            if($result) {
                //save image
                isset($data['image']) AND $this->_saveFileFromTmpUpload($data['image']);
                //update has child for category parent
                $data['parent_id'] AND $this->categoryRepository->updateChild($data['parent_id']);
                //redirect to index
                redirect(\Solid\Collections\AdminModule::getAdminModuleIndexUrl($this->adminModule));
            }
        }catch (Exception $e) {
            log_message('error',$e->getMessage() . ' at ' .$e->getTraceAsString());
            echo 'Bug';
            die();
        }
	}

	public function edit($id) {
		$all_cat = $this->categoryRepository->getAllCategory();

        $this->dataView['listIcon'] = \Solid\Collections\Category::ICON;
        $this->dataView['list'] = $this->parseMultiLevelData($all_cat);
		$this->dataView['detail'] = $this->categoryRepository->findById($id);
		
		$this->blade->render('admin.category.edit',$this->dataView);
	}
	public function postEdit($id) {
		$data = [
            'id'=>$id,
			'name' => $this->input->post('category_name', TRUE),
			'parent_id' => $this->input->post('category_parent'),
			'icon' => $this->input->post('category_icon'),
			'active' => $this->input->post('category_active'),
			'description' => $this->input->post('category_description', TRUE),
			'keyword' => $this->input->post('category_keyword', TRUE),
			'title' => $this->input->post('category_title', TRUE)
		];
		$image_post= $this->input->post('image', TRUE);
		if($image_post) {
			$data['image'] = $image_post;
		}
		//get old parent id from category
		$old_parent_id = $this->getCategoryParentId($id);
		if($old_parent_id === false) {
			$this->showErrorPage("Category not found");
		}
		//update category
		$result = $this->categoryRepository->updateById($data);
		if($result) {
			//save image
			isset($data['image']) AND $this->_saveFileFromTmpUpload($data['image']);
			//update has child
			if(isset($data['parent_id']) && $data['parent_id']) {
				$this->categoryRepository->updateChild($data['parent_id']);
			}
			//remove has child with old parent_id
			if($old_parent_id) {
				$check_has_child = $this->categoryRepository->findById($old_parent_id);
				if(!$check_has_child) {
					$this->categoryRepository->removeChild($old_parent_id);
				}
			}
			redirect(\Solid\Collections\AdminModule::getAdminModuleIndexUrl($this->adminModule));
		}
	}
	public function ajaxDelete() {
		$recordId = $this->input->post('recordId',TRUE);
		//check category has child or not
		//TODO check
		//delete category
		$result = $this->categoryRepository->deleteById($recordId);
		if($result) {
			//TODO need update has_child for category parent
			echo json_encode(['success'=>1,'msg'=>'Bạn đã xóa thành công']);
			die();
		}else{
            set_status_header(400);
			echo json_encode(['success'=>0,'msg'=>'Không thể thực hiện tác vụ này']);
			die();
		}
	}

	public function ajaxUpdate() {
		$arrayReturn = [];
		$recordId = $this->input->post('recordId',TRUE);
		$field = $this->input->post('field',TRUE);
		switch ($field) {
			case 'active':
				$result = $this->categoryRepository->toggleBooleanField($field, $recordId);
				if($result) {
					$arrayReturn['success'] = 1;
					$arrayReturn['msg'] = 'Cập nhật thành công';
					echo json_encode($arrayReturn);
				}else{
					set_status_header(400);
					$arrayReturn['error'] = 1;
					$arrayReturn['msg'] = 'Bad request';
					echo json_encode($arrayReturn);
				}
				break;
		}
		die();
	}


	private function getCategoryParentId($id) {
		$detail = $this->categoryRepository->findById($id);
		if($detail) {
			return $detail->getParentId();
		}else{
			return false;
		}
	}
}