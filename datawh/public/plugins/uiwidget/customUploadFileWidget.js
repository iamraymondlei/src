var customFileUploadWidget = {
    debug:false,
    divId:"",
    idPrefix: "",
    title:"文件",
    fileUrl:"",
    fileSize:0,
    onFinishCallBack:"",
    helpText:"每个文件大小不超过1M"
};

customFileUploadWidget.Draw = function(option) {
    
    customFileUploadWidget.divId = option.divId;
    customFileUploadWidget.title = (option.title)?option.title:'文件';
    customFileUploadWidget.idPrefix = option.idPrefix;
    customFileUploadWidget.fileUrl = (option.fileUrl)?option.fileUrl:"";
    customFileUploadWidget.fileSize = (option.fileSize)?option.fileSize:0;
    customFileUploadWidget.onFinishCallBack = (option.onFinishCallBack)?option.onFinishCallBack:"";
    if(option.helpText){ customFileUploadWidget.helpText = option.helpText; }
    
    var prefix = customFileUploadWidget.idPrefix,
        groupId = prefix+"-imageWidget-group",
        fileUploadGroupId = prefix+"-fileupload-group",
        removeBtn = '<div class="well-sm grid-item-mask-btn-1" title="删除" onclick="customFileUploadWidget.RemoveFile(\''+prefix+'-file\')">'+
                        '<span class="glyphicon glyphicon-trash"></span>'+
                    '</div>',
        fileVal = (option.fileUrl)?"val='"+option.fileUrl+"'":"val=''",
        fileSize = (option.fileSize)?"size='"+option.fileSize+"'":"size=''",
        html = 
        '<div class="media">'+
            '<div class="media-left" id="'+prefix+'-gallery-file-wrapper" >'+
                '<div class="grid-item-mask" style="display:none;">'+ removeBtn + '</div>'+
                '<img class="img-rounded" id="'+prefix+'-file" src="../datawh/public/images/icon_zip.jpg" width="125" '+fileVal+' '+fileSize+' />'+
            '</div>'+
            '<div class="media-body" id="'+fileUploadGroupId+'">'+
            '</div>'+
        '</div>';
    customFileUploadWidget.DrawFormGroup(customFileUploadWidget.title,html,groupId);
    
    var fileUploadUISetting = {
        title:"选择本地文件", 
        fileUrl:(customFileUploadWidget.fileUrl)?customFileUploadWidget.fileUrl:"",
        fileUploadId:prefix+"-fileupload",
        progressBarId:prefix+"-progress",
        helpText:customFileUploadWidget.helpText,
        parentId:fileUploadGroupId,
        isMultiple:false
    };
    customFileUploadWidget.DrawFileUploadBtn(fileUploadUISetting);
    
    var setupFileUploadParams  = {
        fileUploadGroupId : fileUploadGroupId,
        fileUploadId : prefix+"-fileupload",
        progressBarId : prefix+"-progress",
        onFinishCallBack: customFileUploadWidget.UploadFileOnFinish
    };
    customFileUploadWidget.SetupFileUpload(setupFileUploadParams);
};

customFileUploadWidget.RemoveFile = function(elementId) {
    $("#"+elementId).attr("src","");
};

customFileUploadWidget.DrawFormGroup = function(title,content,divId) {
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="control-label" for="'+divId+'">'+title+'： <small></small></label>'+
            content+
        '</div>';
    $("#"+customFileUploadWidget.divId).append(html);
    
    if(!customFileUploadWidget.fileUrl)
        $("#"+customFileUploadWidget.idPrefix+"-gallery-file-wrapper").hide(); 
};

customFileUploadWidget.UploadFileOnFinish = function(data,setupParams) {
    //加载上传成功的文件
    var prefix = customFileUploadWidget.idPrefix,
        divId = prefix+'-file';
    
    $.each(data.result.files, function (index, file) {
        customFileUploadWidget.fileUrl = file.url;
        customFileUploadWidget.fileSize = file.size;

        $('#'+divId).attr("val",file.url);
        $('#'+divId).attr("name",file.orgName);
        $('#'+divId).attr("size",file.size);
        
//        if(file.size > (1048576*20)){
//            $('label[for="'+divId+'"]').parent().addClass("has-error");
//            $('label[for="'+divId+'"] > small').text("(文件大小不可超过20M)");
//            $('#bootstrap-dialog-footer-buttons > button').attr("disabled");
//        }
//        else
//            $('#bootstrap-dialog-footer-buttons > button').removeAttr("disabled");
    });

    //显示文件
    $("#"+prefix+"-gallery-file-wrapper").show(); 
    if(customFileUploadWidget.onFinishCallBack !== ""){
        customFileUploadWidget.onFinishCallBack(data.result.files);
    }
};

customFileUploadWidget.SetupFileUpload = function(setupParams) {    
    $('#'+setupParams.fileUploadId).fileupload({
        url: '../datawh/ws/UploadFile.php',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.files.length > 0){
                setupParams.onFinishCallBack(data,setupParams);
            }
            else{
                BootstrapDialog.alert("上传失败，检查文件大小是否超过20M。");
                $('#'+setupParams.progressBarId+' .progress-bar').css('width','0%');
                $('#'+setupParams.progressBarId+' .progress-bar').attr('aria-valuenow','0');
                $('#'+setupParams.progressBarId+' .progress-bar span').text('0%');
            }
            //以下两行为启用上传按钮
            $("#"+setupParams.fileUploadGroupId+" .fileinput-button").removeClass("disabled");
            $("#"+setupParams.fileUploadId).removeAttr("disabled","disabled");
        },
        progressall: function (e, data) {
            //以下四行为显示进度
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#'+setupParams.progressBarId+' .progress-bar').css('width',progress + '%');
            $('#'+setupParams.progressBarId+' .progress-bar').attr('aria-valuenow',progress);
            $('#'+setupParams.progressBarId+' .progress-bar span').text(progress+'%');
        },
        start: function(e, data) {
            //以下两行为禁用上传按钮
            $("#"+setupParams.fileUploadGroupId+" .fileinput-button").addClass("disabled");
            $("#"+setupParams.fileUploadId).attr("disabled","disabled");
            //以下三行为重置进度条长度
            $('#'+setupParams.progressBarId+' .progress-bar').css('width','0%');
            $('#'+setupParams.progressBarId+' .progress-bar').attr('aria-valuenow','0');
            $('#'+setupParams.progressBarId+' .progress-bar span').text('0%');
        }
    });
};

customFileUploadWidget.DrawFileUploadBtn = function(params) {
    if( customFileUploadWidget.debug ) console.log("DrawFileUploadBtnGroup",params);
    if(params){
        var title = params.title, 
            fileUploadId = params.fileUploadId,
            progressBarId = params.progressBarId,
            helpText = params.helpText,
            parentId = params.parentId,
            multiple = params.isMultiple?"multiple":"",
            name = params.isMultiple?"files[]":"files",
            content = 
            '<span class="btn btn-primary fileinput-button">'+
                '<i class="glyphicon glyphicon-plus"></i>'+
                '<span>'+title+'</span>'+
                '<input id="'+fileUploadId+'" type="file" name="'+name+'" '+multiple+' accept="zip,rar"/>'+
            '</span>'+
            '<p class="help-block">'+helpText+'</p>'+
            '<div class="progress" id="'+progressBarId+'" style="height:20px;">'+
                '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
                    '<span></span>'+
                '</div>'+
            '</div>';
        $("#"+parentId).append(content);
    }
};

customFileUploadWidget.GetData = function() {
    var data = {"url":customFileUploadWidget.fileUrl, "size":customFileUploadWidget.fileSize};
    return data;
};