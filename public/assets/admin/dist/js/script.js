function UploaderScript() {
	this.config = {
		url: '/file/upload',
		file_ext: 'jpg,gif,png,jpeg,bmp',
		browse_button: '',
		image_wrapper: '',
		csrf_token: {},
		loading: '',
		error_wrapper: '',
		max_file_size: '10mb',
		file_name: ''
	}
}
UploaderScript.prototype.init = function (option, callback) {
	var self = this;
	$.extend(self.config, option);
    if (!self.config.browse_button || !$('#' + self.config.browse_button).length) {
        console.log('Error : Khong tim thay browse_button');
        return false;
    }

    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,html4',
        multipart_params: self.config.csrf_token,
        browse_button: self.config.browse_button,
        max_file_size: self.config.max_file_size,
        url: self.config.url,
        flash_swf_url: 'plupload/js/plupload.flash.swf',
        silverlight_xap_url: 'plupload/js/plupload.silverlight.xap',
        filters: [{
            title: "Files",
            extensions: self.config.file_ext
        }]
    });
    uploader.init();
    uploader.bind('FilesAdded', function (up, file) {
        uploader.start();
    });
    uploader.bind('UploadProgress', function (up, file) {
        if (self.config.loading) {
            $('#' + self.config.loading).html(file.percent + '%');
        }
    });
    uploader.bind('FileUploaded', function (up, file, resp) {
        if (resp.status == 200) {
            var fileInfo = resp.response;
            fileInfo = JSON.parse(fileInfo);
            filename = fileInfo.result.filename;
            filepath = fileInfo.result.filepath;
            $('#' + self.config.image_wrapper).attr('src', filepath);
	        self.config.file_name = filename;
            callback();
        }
    });
};
$.alert = function (option) {
    return TableAdminJs.alert(option);
};

function TableAdminJs(option) {
    option = option ? option : {};
    this.option = option;
    if(!option.method) {
        this.option.method = 'post';
    }
    if(!option.dataType) {
        this.option.dataType = 'json';
    }
}
TableAdminJs.triggerAlert = 0;
TableAdminJs.alert = function (option) {
    if (typeof option === 'string') {
        option = {
            message: option
        }
    }
    if (typeof option !== 'object') {
        throw new Error("Not allowed!");
    }
    option.timeout = option.timeout || 3000;
    option.bgClassSuccess = option.bgClassSuccess || 'bg-success';
    option.bgClassError = option.bgClassError || 'bg-danger';
    option.type = option.type || 'success';
    option.message = option.message || option.type.toUpperCase();
    var body = $('body');
    clearTimeout(TableAdminJs.triggerAlert);
    body.find('.tableAdmin-alert').remove();
    var bgClass;
    switch (option.type) {
        case 'success':
            bgClass = option.bgClassSuccess;
            break;
        case 'error':
            bgClass = option.bgClassError;
            break;
    }
    var content = '<div class="tableAdmin-alert ' + bgClass + '" style="display: block;">' +
        '<a href="#" class="tableAdmin-alertBody" target="_blank">' +
        option.message +
        '</a>' +
        '</div>';
    body.append(content);
    TableAdminJs.triggerAlert = setTimeout(function () {
        body.find('.tableAdmin-alert').remove()
    }, 3000);
};
TableAdminJs.prototype.deleteRecord = function (option, successCallback, failureCallback) {
    if (option.data) {
        this.option.data = option.data;
    }
    if (option.url) {
        this.option.url = option.url;
    }
    if (option.method) {
        this.option.method = option.method;
    }
    if (option.dataType) {
        this.option.dataType = option.dataType;
    }
    ajax(this.option, successCallback, failureCallback);
};

TableAdminJs.prototype.updateRecord = function (option, successCallback, failureCallback) {
    if (option.data) {
        this.option.data = option.data;
    }
    if (option.url) {
        this.option.url = option.url;
    }
    if (option.method) {
        this.option.method = option.method;
    }
    if (option.dataType) {
        this.option.dataType = option.dataType;
    }
    ajax(this.option, successCallback, failureCallback);
};

function ajax(option, successCallback, failureCallback) {
    option.data.csrf_token = Cookies.get('csrf_token');
    $.ajax({
        type: option.method,
        data: option.data,
        url: option.url,
        dataType: option.dataType,
        success: function (resp) {
            if (successCallback) {
                successCallback(resp);
            }
        },
        error: function (resp) {
            if (failureCallback) {
                if(resp.responseJSON) {
                    failureCallback(resp.responseJSON);
                }else{
                    failureCallback(JSON.parse(resp.responseText));
                }
            }
        }
    })
}

//document ready function
$(document).ready(function(){
    $('.select2').select2();
});