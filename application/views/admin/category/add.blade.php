@extends('admin.template')

@section('breadcrumb')
    <h1>
        {{ trans('admin.page.categories.edit-page-title') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-folder-o"></i> {{ trans('admin.sidebar.categories') }}</a></li>
        <li class="active">{{ trans('admin.page.categories.edit-page-title') }}</li>
    </ol>
@stop

@section('main')
    {{ form_open_multipart() }}
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Thông tin cơ bản</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <input type="text" name="category_name" class="form-control" placeholder="Nhập tên danh mục..." value="">
                    </div>
                    <div class="form-group">
                        <label>Danh mục cha</label>
                        <select class="form-control select2" name="category_parent" style="width: 100%;">
                            <option value=""> - Chọn danh mục - </option>
                            @foreach($list as $cat)
                                <option value="{{$cat->id}}" >{{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    {{CommonHelperFn\ajaxUploadFile(['label'=>'Ảnh danh mục','name'=>'image','id'=>'category_image'])}}
                    <div class="form-group">
                        <label>Biểu tượng</label>
                        <div class="row">
                            @foreach($listIcon as $key=>$item)
                                <div class="col-xs-2">
                                    <label>
                                        <input type="radio" class="form-control iCheck"
                                               name="category_icon" value="{{$key}}">
                                        <i class="{{$item}}"></i>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kích hoạt</label>
                        <div>
                            <input type="checkbox" class="form-control iCheck"
                                   name="category_active" value="1" >
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Thông tin nâng cao</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <label>Mô tả danh mục</label>
                        <textarea class="form-control" name="category_description" rows="3" placeholder="Nhập mô tả ..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Từ khóa danh mục</label>
                        <input type="text" name="category_keyword" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Tiêu đề danh mục</label>
                        <input type="text" class="form-control" name="category_title">
                        <p class="help-block">Mặc định nếu không khai báo, tiêu đề sẽ trùng với tên danh mục</p>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    {{ form_close() }}
@stop

@section('script')
    <script>
        $('.select2').select2();
        $('.iCheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    </script>
@stop