/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var fileList = {
    rootCat:null,
    type:null,
    folder:null,
    pageSize:100,
    pageIndex:1,
    fileCount:0,
    fileList:null,
    projectList:null,
    catList:null,
    fileTypeList:null,
    fileGridWidget:null,
    csmsbwObj:null,
    cat:null,
    project:null,
    keyword:null,
    format:"json"
};

$(function () {
    fileList.rootCat = $.urlParams("get", "catId");
    fileList.fileType = $.urlParams("get", "type");
    fileList.folder = $.urlParams("get", "folder");
    
    var requestParams = {format:fileList.format,folder:fileList.folder,type:fileList.fileType};
    fileList.RequestFileType();
    fileList.SetTitle();
    fileList.RequestFileList(requestParams);
    fileList.RequestCatList();
    fileList.RequestProjectList();
    fileList.SetupProjectListMultiselect();
    fileList.DrawCat();
    fileList.DrawGridTable();    
});

fileList.SetTitle = function() {
    var title = "";
        
    $.each(fileList.fileTypeList,function(index,type) {
        if(type.FileTypeId === fileList.fileType){
            title = type.FileTypeName;
            return true;
        }
    });
    
    $("#fileList-title").text(title);
};

fileList.RequestFileList = function(requestParams) {
    $.ajax({
        url: 'ws/GetFileList.php',
        type: 'POST',
        data: requestParams,
        dataType: 'json',
        async: false,
        success: function(json) {
            fileList.fileList = json.WebService.FileList.File;
            fileList.fileCount = json.WebService.Count;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetFileList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

fileList.RequestProjectList = function() {
    $.ajax({
        url: 'ws/GetProjectList.php',
        type: 'POST',
        data:{
            format: 'json',
            pageSize: 999
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.ProjectList){
                fileList.projectList = json.WebService.ProjectList.Project;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetProductList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

fileList.RequestCatList = function() {
    $.ajax({
        url: 'ws/GetFileCatList.php',
        type: 'POST',
        data:{
            format: 'json',
            catId: fileList.rootCat
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.FileCatList){
                var catList = json.WebService.FileCatList.FileCat;
                fileList.catList = {"ItemList":fileList.SetSubMenuSelectBoxData(catList)};
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetPaymentList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

fileList.RequestFileType = function() {
    $.ajax({
        url: 'ws/GetFileType.php',
        type: 'POST',
        data:{
            format: 'json'
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.FileTypeList){
                fileList.fileTypeList = json.WebService.FileTypeList.FileType;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetStoreList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

fileList.SetupProjectListMultiselect = function(){
    var projectList = fileList.projectList;
    var multiselectObj = $('#fileList-project-multiselect');
    multiselectObj.empty();
    
    $.each(projectList, function(index, item){
        var id = item.ProjectId;
        var optinHtml = '<option value="'+id+'">'+item.ProjectName+'</option>';
        
        multiselectObj.append(optinHtml);
    });
    //multiselectObj.prepend('<option value="all" selected>全部店铺</option>');
    multiselectObj.multiselect('rebuild');
};

fileList.SetSubMenuSelectBoxData = function(catList) {
    var result = [];
    $.each(catList, function(index,cat){
        var item = {ItemId:cat.FileCatNodeId, ItemName:cat.FileCatName, ParentId: cat.ParentCatId};
        if(cat.FileCatList){
            var subItem = fileList.SetSubMenuSelectBoxData(cat.FileCatList.FileCat);
            item.SubItem = subItem;
        }
        else
            item.Checked = "false";
        result.push(item);
    });
    return result;
};

fileList.DrawCat = function() {
    var selectedItemsId = null;
    
    var configSetting = {};
    configSetting.divId = "fileList-cat-subment";
    configSetting.title = "";
    configSetting.selectData = fileList.catList;
    configSetting.selectedItemsId = (selectedItemsId)?selectedItemsId:"";
    
    fileList.csmsbwObj = customSubMenuSelectBoxWidget.Create();
    fileList.csmsbwObj.Draw(configSetting);
};

fileList.DrawGridTable = function() {
    var gridData = [];
    $.each(fileList.fileList,function(index,file){
        gridData.push({
            id:file.FileId,
            imageUrl:file.PerviewImage,
            title:file.FileName,
            mask:{btn:[
                    {icon:"fa fa-info-circle", name:"查看", onClick:"fileList.ViewFileOnClick"},
                    {icon:"fa fa-pencil", name:"编缉", onClick:"fileList.EditFileOnClick"},
                    {icon:"fa fa-download", name:"下载", onClick:"fileList.DownloadOnClick"}
                ]}
        });
    });
    
    var configSetting = {
        divId:"fileList-file-grid",
        gridEffect:"3",
        dataCount:fileList.fileCount,
        gridData: gridData,
        page:{
            pagination:true,
            pSizeOption:[20,50,100,200],
            pIndex:fileList.pageIndex,
            pSize:fileList.pageSize,
            pSizeOnClick:"fileList.PageSizeOnClick",
            pIndexOnClick:fileList.PageIndexOnClick
        }
    };
    
    if(fileList.fileGridWidget){ 
        fileList.fileGridWidget.Refresh(configSetting);
    }
    else{
        fileList.fileGridWidget = customGridView.Create(); 
        fileList.fileGridWidget.Draw(configSetting);
    }
};

fileList.SetRequestFileListParams = function() {
    var projectAry = $('#fileList-project-multiselect').val();
    
    fileList.keyword = $("#fileList-file-searchBox").val();
    fileList.cat = fileList.csmsbwObj.GetSelectedItemId();
    fileList.project = (projectAry && projectAry.length > 0)?projectAry.join():"";
    
    var params = {
        format:fileList.format,
        folder:fileList.folder,
        type:fileList.fileType,
        keyword:fileList.keyword,
        page:fileList.page,
        pageSize:fileList.pageSize
    };
    
    if(fileList.cat !== "") params.cat = fileList.cat;
    if(fileList.project !== "") params.project = fileList.project;
    return params;
};

fileList.RefreshGridTable = function() {
    var requestParams = fileList.SetRequestFileListParams();
    fileList.RequestFileList(requestParams);
    fileList.DrawGridTable();
};

fileList.PageSizeOnClick = function(size) {
    fileList.pageSize = size;
    var requestParams = fileList.SetRequestFileListParams();
    fileList.RequestFileList(requestParams);
    fileList.DrawGridTable();
};

fileList.PageIndexOnClick = function(event,selectedPage) {
    fileList.pageIndex = selectedPage;
     var requestParams = fileList.SetRequestFileListParams();
    fileList.RequestFileList(requestParams);
    fileList.DrawGridTable();
};

fileList.EditFileOnClick = function(itemId) {
    location.href="index.php?p=backend&c=File&a=update&catId="+fileList.rootCat+"&type="+fileList.fileType+"&folder="+fileList.folder+"&uid="+itemId;
};

fileList.DownloadOnClick = function(itemId) {
    $.each(fileList.fileList,function(index,file){
       if(file.FileId == itemId){
            var url = file.FileUrl;
            var index1=url.lastIndexOf(".");  
            var index2=url.length;
            var suffix=url.substring(index1,index2);//后缀名  

            var $a = $("<a></a>").attr("href", url).attr("download", file.FileName+suffix);
            $a[0].click();
            return false;
       }
    });
};

fileList.ViewFileOnClick = function(itemId) {
    fileDetailDialog.Show(itemId);
};

fileList.SearchFileOnKeypress = function(event) {
    if(event.keyCode === 13){
        fileList.page = 1;
        var requestParams = fileList.SetRequestFileListParams();
        fileList.RequestFileList(requestParams);
        fileList.DrawGridTable();
    }
};