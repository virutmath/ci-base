<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>{{!empty($adminName) ? $adminName : ''}}</p>
				<a href="#"><i class="fa fa-circle text-success"></i> Đang online</a>
			</div>
		</div>
		<!-- search form -->
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Tìm kiếm chức năng...">
			  <span class="input-group-btn">
				<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
				</button>
			  </span>
			</div>
		</form>
		<!-- /.search form -->
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li class="header text-uppercase">Thanh điều hướng</li>
			<li class="{{!$activeModule ? 'active' : ''}}">
				<a href="{{RewriteUrlFn\admin_dashboard()}}">
					<i class="fa fa-dashboard"></i> <span>Tổng quan</span>
				</a>
			</li>
			@if(!empty($listModule))
				@foreach($listModule as $module)
					<li class="{{$activeModule == $module->getId() ? 'active' : ''}}
					{{$module->child ? 'treeview' : ''}}">
						<a href="{{$module->getAdminModuleUrl() ?: '#'}}">
							<i class="fa fa-folder-o"></i>
							<span>{{ $module->name }}</span>
							@if($module->child)
								<i class="fa fa-angle-right pull-right"></i>
							@endif
						</a>
						@if($module->child)
							<ul class="treeview-menu">
								@foreach($module->child as $child)
									<li>
										<a href="{{$child->getAdminModuleUrl() ?: '#'}}">
											<i class="fa fa-caret-right"></i> {{ $child->name }}
										</a>
									</li>
								@endforeach
							</ul>
						@endif
					</li>
				@endforeach
			@endif
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>