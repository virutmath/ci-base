<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/15/2016
 * Time: 4:31 PM
 */

namespace Solid\Builder;


use Solid\Collections\AdminModule;

class SidebarMenu
{
    private $adminName;
	/**
	 * @var \Solid\Collections\AdminModule[]
	 */
    private $listModule;
    private $currentModule;

	/**
	 * SidebarMenu constructor.
	 * @param $adminName
	 * @param AdminModule[] $listModule
	 * @param $currentModule
	 */
    public function __construct($adminName,$listModule, $currentModule)
    {
        $this->adminName = $adminName;
        $this->listModule = $listModule;
        $this->currentModule = $currentModule;
    }

    public static function initialize($adminName,$listModule, $currentModule = '')
    {
        return new SidebarMenu($adminName, $listModule, $currentModule);
    }

    public function render()
    {
	    //lấy ra module đang active
	    $activeModuleId = 0;
	    $activeModuleChild = 0;
	    foreach ($this->listModule as $module) {
		    if($module->getController() == $this->currentModule) {
			    $activeModuleId = $module->getId();
			    break;
		    }
		    if($moduleChild = $module->getChild()) {
			     foreach ($moduleChild as $child) {
				     if($child->getController() == $this->currentModule) {
					     $activeModuleId = $module->getId();
				     }
			     }
		    }
	    }
        $dataView = [
            'adminName'=>$this->adminName,
            'listModule'=>$this->listModule,
            'activeModule'=>$activeModuleId
        ];
        
        /**
         * @var \Blade $blade
         */
        $blade = get_instance()->blade;
        return $blade->render('admin.includes.sidebar',$dataView,TRUE);
    }
}