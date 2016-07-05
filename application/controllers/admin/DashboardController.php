<?php
class DashboardController extends AdminController {
	protected $adminModule = 'dashboard';
    public function index() {
        $this->blade->render('admin.index',$this->dataView);
    }
}