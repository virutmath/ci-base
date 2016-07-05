@extends('admin.template')

@section('main')
	{{form_open('',['method'=>'get'])}}
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Chọn nhóm quản trị</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="form-group">
						<select name="group" class="form-control select2">
							<option value="">-- Lựa chọn --</option>
							@foreach($list as $group)
								<option value="{{$group->getId()}}">{{$group->getName()}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<div class="form-action pull-right">
						<button type="submit" class="btn btn-primary">Check it out</button>
					</div>
				</div>
				<!-- box-footer -->
			</div>
			<!-- /.box -->
		</div>
	</div>
	{{form_close()}}
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
		$('.select2').select2();
	</script>
@stop