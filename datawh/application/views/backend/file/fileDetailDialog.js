/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var fileDetailDialog = {
    debug:false,
    html:"",
    id:"",
    imgUrl:"",
    dwUrl:"",
    name:"",
    size:"",
    project:"",
    cat:"",
    tag:"",
    creationTime:"",
    updateTime:"",
    author:""
};

fileDetailDialog.Clear = function() {
    fileDetailDialog.html = "";
    fileDetailDialog.id = "";
};

fileDetailDialog.SetHtml = function() {
    fileDetailDialog.html ='<div class="main-box clearfix" style="box-shadow:none;">'+
                                '<header class="main-box-header clearfix">'+
                                    '<h2>'+fileDetailDialog.name+'</h2>'+
                                '</header>'+
                                '<div class="main-box-body clearfix">'+
                                    '<div class="row">'+
                                        '<div class="col-md-8" >'+
                                            '<img src="'+fileDetailDialog.imgUrl+'" alt="" class="profile-img img-responsive center-block">'+
                                        '</div>'+
                                        '<div class="col-md-4" >'+
                                            '<form class="form-horizontal">'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>模型大小：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.size+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>所属项目：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.project+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>所属类别：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.cat+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>所属标签：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.tag+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>上传时间：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.creationTime+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>修改时间：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.updateTime+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="form-group">'+
                                                    '<label class="col-sm-4 control-label"><strong>上传者：</strong></label>'+
                                                    '<div class="col-sm-8">'+
                                                        '<p class="form-control-static">'+fileDetailDialog.author+'</p>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</form>'+
                                            '<button type="button" class="btn btn-primary btn-lg btn-block" onClick="fileDetailDialog.DownloadOnClick()">下载</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
};

fileDetailDialog.Show = function(id) {
    fileDetailDialog.Clear();
    fileDetailDialog.id = id;
    fileDetailDialog.RequestFile();
    fileDetailDialog.SetHtml();
    fileDetailDialog.Init("添加");
};

fileDetailDialog.Init = function() {
    BootstrapDialog.show({
        title: "文件详细信息",
        message: fileDetailDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            
        }
    }); 
};

fileDetailDialog.RequestFile = function() {    
    var requestParams = {
        format:"json",
        id:fileDetailDialog.id
    };
    
    $.ajax({
        url: 'ws/GetFileList.php',
        type: 'POST',
        data: requestParams,
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.FileList.File.length === 1){
                var fileData = json.WebService.FileList.File[0];
                fileDetailDialog.imgUrl = fileData.PerviewImage;
                fileDetailDialog.dwUrl = fileData.FileUrl;
                fileDetailDialog.name = fileData.FileName;
                fileDetailDialog.size = fileDetailDialog.FormatFileSize(fileData.FileSize);
                fileDetailDialog.project = fileDetailDialog.GetProject(fileData.ProjectList);
                fileDetailDialog.cat = fileDetailDialog.GetCat(fileData.FileCatList);
                fileDetailDialog.tag = fileDetailDialog.GetTag(fileData.TagList);
                fileDetailDialog.creationTime = fileData.CreationTime;
                fileDetailDialog.updateTime = fileData.LastUpdate;
                fileDetailDialog.author = fileData.DisplayName;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetFileList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

fileDetailDialog.FormatFileSize = function(size) {
    var result = "0";
    if(size && size !== ""){
        var formatSize = Math.ceil(size/1024);
        if(formatSize > 1024){
            formatSize = Math.ceil(formatSize/1024) + "M";
        }
        else{
            formatSize+="K";
        }
        result = formatSize;
    }
    return result;
};

fileDetailDialog.GetProject = function(data) {
    var result = "";
    if(data && data.Project && data.Project.length > 0){
        $.each(data.Project,function(index,item) {
            result+=item.ProjectName+"、";
        });
        result = result.substring(0,result.length-1);
    }
    return result;
};

fileDetailDialog.GetCat = function(data) {
    var result = "";
    if(data && data.FileCat && data.FileCat.length > 0){
        $.each(data.FileCat,function(index,item) {
            result+=item.FileCatName+"、";
        });
        result = result.substring(0,result.length-1);
    }
    return result;
};

fileDetailDialog.GetTag = function(data) {
    var result = "";
    if(data && data.Tag && data.Tag.length > 0){
        $.each(data.Tag,function(index,item) {
            result+=item.TagName+"、";
        });
        result = result.substring(0,result.length-1);
    }
    return result;
};

fileDetailDialog.DownloadOnClick = function() {
    var fileUrl = fileDetailDialog.dwUrl;
    var index1=fileUrl.lastIndexOf(".");  
    var index2=fileUrl.length;
    var suffix=fileUrl.substring(index1,index2);//后缀名  

    var $a = $("<a></a>").attr("href", fileUrl).attr("download", fileDetailDialog.name+suffix);
    $a[0].click();
};