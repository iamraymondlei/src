var customImageWidget = {
    debug:false,
    divId:"",
    idPrefix: "",
    imageWidth: 250,
    imageHeight: 300,
    title:"图片",
    imageUrl:"",
    fileSize:0,
    onFinishCallBack:"",
    isMultiple:false,
    helpText:"照片尺寸不少于300x500，每张图片大小不超过1M",
    uploadWS:"",
    name:"files"
};

customImageWidget.Draw = function(option) {
    customImageWidget.divId = option.divId;
    customImageWidget.uploadWS = option.uploadWS;
    customImageWidget.title = (option.title)?option.title:'图片';
    customImageWidget.idPrefix = option.idPrefix;
    customImageWidget.imageWidth = option.imageWidth;
    customImageWidget.imageHeight = option.imageHeight;
    customImageWidget.thumbnailImageUrl = option.thumbnailImageUrl;
    customImageWidget.imageUrl = option.imageUrl;
    customImageWidget.fileSize = (option.fileSize)?option.fileSize:0;
    customImageWidget.onFinishCallBack = (option.onFinishCallBack)?option.onFinishCallBack:"";
    customImageWidget.isMultiple = (option.isMultiple)?option.isMultiple:false;
    customImageWidget.name = (option.name)?option.name:"files";
    if(option.helpText){ customImageWidget.helpText = option.helpText; }
    
    var prefix = customImageWidget.idPrefix,
        groupId = prefix+"-imageWidget-group",
        fileUploadGroupId = prefix+"-fileupload-group",
        imageUrl = (customImageWidget.imageUrl)?customImageWidget.imageUrl:"",
        thumbnailImageUrl = (customImageWidget.thumbnailImageUrl)?customImageWidget.thumbnailImageUrl:"",
        fileSize = (customImageWidget.fileSize)?"size='"+customImageWidget.fileSize+"'":"size=''",
        removeBtn = '<div class="well-sm grid-item-mask-btn-1" title="删除" onclick="customImageWidget.RemoveImage(\''+prefix+'-image\')">'+
                        '<span class="glyphicon glyphicon-trash"></span>'+
                    '</div>',
        html = 
        '<div class="media">'+
            '<div class="media-left" id="'+prefix+'-gallery-photos-wrapper" >'+ //onmouseover="customImageWidget.SetImgBtnVisibility(\'show\',this)" onmouseout="customImageWidget.SetImgBtnVisibility(\'hide\',this)"
                '<div class="grid-item-mask" style="display:none;">'+ removeBtn + '</div>'+
                '<img class="img-rounded" id="'+prefix+'-image" src="'+thumbnailImageUrl+'" thumbnail="'+thumbnailImageUrl+'" val="'+imageUrl+'" '+fileSize+' width="250" />'+
                '<input name="'+customImageWidget.name+'" value="'+imageUrl+'" hidden />'+
                '<input name="'+customImageWidget.name+'-thumb" value="'+thumbnailImageUrl+'" hidden />'+
            '</div>'+
            '<div class="media-body" id="'+fileUploadGroupId+'">'+
            '</div>'+
        '</div>';
    customImageWidget.DrawFormGroup(customImageWidget.title,html,groupId);
    
    var fileUploadUISetting = {
        title:"选择本地图片", 
        fileUploadId:prefix+"-fileupload",
        progressBarId:prefix+"-progress",
        helpText:customImageWidget.helpText,
        parentId:fileUploadGroupId,
        isMultiple:customImageWidget.isMultiple,
        name:customImageWidget.name
    };
    customImageWidget.DrawFileUploadBtn(fileUploadUISetting);
    
    var setupFileUploadParams  = {
        fileUploadGroupId : fileUploadGroupId,
        fileUploadId : prefix+"-fileupload",
        progressBarId : prefix+"-progress",
        onFinishCallBack: customImageWidget.UploadImageOnFinish
    }
    customImageWidget.SetupFileUpload(setupFileUploadParams);
};

customImageWidget.RemoveImage = function(elementId) {
    $("#"+elementId).attr("src","");
    $("#"+elementId).attr("val","");
};

customImageWidget.SetImgBtnVisibility = function(action,elementObj) {
    var maskHight = $(elementObj).children("img").height(),
        maskWidth = $(elementObj).children("img").width();
    if(action === "show"){
        $(elementObj).children(".grid-item-mask").css("width",maskWidth);
        $(elementObj).children(".grid-item-mask").css("height",maskHight);
        $(elementObj).children(".grid-item-mask").show();
    }
    else if(action === "hide")
        $(elementObj).children(".grid-item-mask").hide();
};

customImageWidget.ImageNvpUploadOnFinish = function(data,setupParams) {
    //加载上传成功的图片
    $.each(data.result.files, function (index, file) {
        $('#'+setupParams.imgId).attr("src",file.url);
        $("#"+setupParams.imgId).aeImageResize({ height: customImageWidget.imageWidth, width: customImageWidget.imageHeight });
    });
    //以下两行为启用上传按钮
    $("#"+setupParams.fileUploadGroupId+" .fileinput-button").removeClass("disabled");
    $("#"+setupParams.fileUploadId).removeAttr("disabled","disabled");
};

customImageWidget.DrawFormGroup = function(title,content,divId) {
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="control-label" for="'+divId+'">'+title+'： <small></small></label>'+
            content+
        '</div>';
    $("#"+customImageWidget.divId).append(html);
};

customImageWidget.UploadImageOnFinish = function(data,setupParams) {
    //加载上传成功的图片
    var prefix = customImageWidget.idPrefix,
        divId = prefix+'-image';
    $.each(data.result.files, function (index, file) {
        customImageWidget.imageUrl = file.url;
//        customImageWidget.ImageW = file.width;
//        customImageWidget.ImageH = file.height;
        $('#' + divId).attr("src", file.thumbnailUrl);
        $('#' + divId).attr("thumbnail", file.thumbnailUrl);
        $('#' + divId).attr("val", file.url);
        $('#' + divId).attr("name", file.orgName);
        $('#' + divId).attr("size", file.size);
        $('#' + divId).aeImageResize({height: customImageWidget.imageWidth, width: customImageWidget.imageHeight});

        //更新input标签图片地址
        if (data.result.files.length === 1 && index === 0) {
            $('#' + prefix + '-gallery-photos-wrapper > input[name="' + customImageWidget.name + '"]').attr("value", file.url);
            $('#' + prefix + '-gallery-photos-wrapper > input[name="' + customImageWidget.name + '-thumb"]').attr("value", file.thumbnailUrl);
        }
//        if(file.size > 1048576){
//            $('label[for="'+divId+'"]').parent().addClass("has-error");
//            $('label[for="'+divId+'"] > small').text("(图片大小不可超过1M)");
//            $('#bootstrap-dialog-footer-buttons > button').attr("disabled");
//        }
//        else
//            $('#bootstrap-dialog-footer-buttons > button').removeAttr("disabled");
//
//        if(file.width < 400 || file.height < 500){
//            $('label[for="'+divId+'"]').parent().addClass("has-error");
//            $('label[for="'+divId+'"] > small').text("(图片大小不可小于300x500)");
//            $('#bootstrap-dialog-footer-buttons > button').attr("disabled");
//        }
//        else
//            $('#bootstrap-dialog-footer-buttons > button').removeAttr("disabled");
    });
    //以下两行为启用上传按钮
    $("#"+setupParams.fileUploadGroupId+" .fileinput-button").removeClass("disabled");
    $("#"+setupParams.fileUploadId).removeAttr("disabled","disabled");
    //显示图片
    $("#"+prefix+"-gallery-photos-wrapper").show(); 
    if(customImageWidget.onFinishCallBack !== ""){
        customImageWidget.onFinishCallBack(data.result.files);
    }
};

customImageWidget.SetupFileUpload = function(setupParams) {    
    $('#'+setupParams.fileUploadId).fileupload({
        url: customImageWidget.uploadWS,
        dataType: 'json',
        done: function (e, data) {
            setupParams.onFinishCallBack(data,setupParams);
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

customImageWidget.DrawFileUploadBtn = function(params) {
    if( customImageWidget.debug ) console.log("DrawFileUploadBtnGroup",params);
    if(params){
        var title = params.title, 
            fileUploadId = params.fileUploadId,
            progressBarId = params.progressBarId,
            helpText = params.helpText,
            parentId = params.parentId,
            multiple = params.isMultiple?"multiple":"",
            name = params.isMultiple?"files[]":"files",//params.name
            content = 
            '<span class="btn btn-primary fileinput-button">'+
                '<i class="glyphicon glyphicon-plus"></i>'+
                '<span>'+title+'</span>'+
                '<input id="'+fileUploadId+'" type="file" name="'+name+'" '+multiple+' accept="image/gif,image/jpeg,image/png"/>'+
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