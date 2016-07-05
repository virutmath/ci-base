@extends('admin.template')

@section('main')
    {{form_open(empty($formActionUrl) ? RewriteUrlFn\admin_add_module() : $formActionUrl,'autocomplete="off"')}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Cấu hình module</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Module name</label>
                                <input type="text" name="name"
                                       value="{{empty($moduleName) ? '' :$moduleName}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Controller</label>
                                <input type="text" name="controller"
                                       value="{{empty($controllerName) ? '' : $controllerName}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Method</label>
                                <input type="text" name="method"
                                       value="{{empty($methodName) ? '' : $methodName}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Parent module</label>
                                <select name="parent_id" class="form-control select2">
                                    <option value="0">Menu cha</option>
                                    @foreach($moduleList as $module)
                                        <option value="{{$module->getId()}}"
                                                {{!empty($parentId) && $parentId == $module->getId() ? 'selected' : ''}}>{{$module->publicName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"> &nbsp;Lưu&nbsp; </button>
                </div>
            </div>
        </div>
    </div>
    {{form_close()}}

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
    </script>
@stop