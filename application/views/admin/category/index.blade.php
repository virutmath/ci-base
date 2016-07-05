@extends('admin.template')
@section('breadcrumb')
	<h1>
		Danh sách danh mục
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-folder-o"></i> Trang chủ</a></li>
		<li class="active">Quản lý danh mục</li>
	</ol>
@stop
@section('main')
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					{{$tableAdmin}}
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>

@stop

@section('script')
	<script>
		var adminJs = new TableAdminJs();
		$('.deleteRecord').click(function (e) {
			e.preventDefault();
			var recordId = $(this).data('id'),
					url = $(this).data('delete-url'),
					option = {
						url: url,
						data: {
							recordId: recordId
						}
					};
			adminJs.deleteRecord(option, function (response) {
				if (response.success) {
					$.alert(response.msg);
					$('#tableAdmin-tr-' + recordId).remove();
				}
			}, function (response) {
				console.log(response);
			})
		});

		$('.iCheck').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'icheckbox_minimal-blue',
			increaseArea: '20%' // optional
		});

		$('.control-active').on('ifChanged', function () {
			var recordId = $(this).data('id'),
					url = '/admin/category/update',
					option = {
						url: url,
						data: {
							field: 'active',
							recordId: recordId
						}
					};
			adminJs.updateRecord(option, function (resp) {
				$.alert(resp.msg || 'Cập nhật thành công');
			}, function (resp) {
				$.alert({
					message: resp.msg || 'Cập nhật thất bại',
					type: 'error'
				});
			})
		})
	</script>
@stop