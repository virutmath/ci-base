@extends('admin.template')

@section('main')

	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Danh sách module</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					{{$tableAdmin}}
				</div>
				<!-- /.box-body -->
				<div class="box-footer">

				</div>
				<!-- box-footer -->
			</div>
			<!-- /.box -->
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
				$.alert('Delete success');
				$('#tableAdmin-tr-' + recordId).remove();
			})
		});
		$('.iCheck').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass: 'icheckbox_minimal-blue',
			increaseArea: '20%' // optional
		});
		$('.select2').select2();
		$('.control-roleTmp-role_view, .control-roleTmp-role_edit, .control-roleTmp-role_delete, .control-roleTmp-role_add, .control-roleTmp-role_import, .control-roleTmp-role_export').on('ifChanged', function() {
			var className = $(this).attr('class'),
					field = className.split(' ').slice(-1).pop().replace('control-roleTmp-role_','');

			var recordId = $(this).data('id');
			var option = {
				url: '/admin/setting-group-role/update',
				data: {
					record: recordId,
					field: field
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