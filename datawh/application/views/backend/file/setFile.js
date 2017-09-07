var setFile = {
    debug:false,
    idPerfix:"setFile",
    projectList:null,
    catId:null,
    tagList:[],
    fileTypeList:[],
    catList:null,
    csmsbwObj:null,
    saveData:{
        fileType: null,
        folder: null,
        name : null,
        description : null,
        fileSize : null,
        perviewImage: null,
        fileUrl: null,
        tagList : null,
        catList : null,
        projectList: null,
        action : null,
        format : 'json'
    }
};

setFile.RequestFile = function() {
    $.ajax({
        url: 'ws/GetFileList.php',
        type: 'POST',
        data: {
            format:'json',
            id:setFile.saveData.id
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            var data = (json.WebService.FileList.File)[0];
            setFile.saveData.name = data.FileName;
            setFile.saveData.fileType = data.FileTypeId;
            setFile.saveData.perviewImage = data.PerviewImage;
            setFile.saveData.fileUrl = data.FileUrl;
            setFile.saveData.fileSize = data.FileSize;
            setFile.saveData.description = data.Description;
            setFile.saveData.catList = "";
            setFile.saveData.projectList = "";
            setFile.saveData.tagList = "";
            
            $.each(data.FileCatList.FileCat, function(index,cat) {
                setFile.saveData.catList+= (index < data.FileCatList.FileCat.length - 1)?cat.FileCatNodeId + ",":cat.FileCatNodeId;
            });
            
            $.each(data.ProjectList.Project, function(index,project) {
                setFile.saveData.projectList+= (index < data.ProjectList.Project.length - 1)?project.ProjectId + ",":project.ProjectId;
            });
            
            $.each(data.TagList.Tag, function(index,tag) {
                setFile.saveData.tagList+= (index < data.TagList.Tag.length - 1)?tag.TagId + ",":tag.TagId;
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetFileList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setFile.RequestProjectList = function() {
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
                setFile.projectList = json.WebService.ProjectList.Project;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetProductList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setFile.RequestCatList = function() {
    $.ajax({
        url: 'ws/GetFileCatList.php',
        type: 'POST',
        data:{
            format: 'json',
            catId: setFile.catId
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.FileCatList){
                var catList = json.WebService.FileCatList.FileCat;
                setFile.catList = {"ItemList":setFile.SetSubMenuSelectBoxData(catList)};
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetPaymentList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setFile.SetSubMenuSelectBoxData = function(catList) {
    var result = [];
    $.each(catList, function(index,cat){
        var item = {ItemId:cat.FileCatNodeId, ItemName:cat.FileCatName, ParentId: cat.ParentCatId};
        if(cat.FileCatList){
            var subItem = setFile.SetSubMenuSelectBoxData(cat.FileCatList.FileCat);
            item.SubItem = subItem;
        }
        else
            item.Checked = "false";
        result.push(item);
    });
    return result;
};

setFile.RequestTagList = function() {
    $.ajax({
        url: 'ws/GetTagList.php',
        type: 'POST',
        data:{
            format: 'json'
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.TagList){
                var tagList = json.WebService.TagList.Tag;
                setFile.tagList = [];
                $.each(tagList, function(index,tag) {
                    setFile.tagList.push({"value":tag.TagId,"text":tag.TagName });
                });
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetStoreList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setFile.RequestFileType = function() {
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
                setFile.fileTypeList = json.WebService.FileTypeList.FileType;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetStoreList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setFile.RequestSetFile = function() {
    $.ajax({
        url: 'ws/SetFile.php',
        type: 'POST',
        data: setFile.saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.Result > 0 && json.WebService.ResultCode === 200){
                var action = (setFile.saveData.action === "add")?"添加":"修改";
                
                BootstrapDialog.confirm({
                    title: '提示',
                    message: '保存成功，是否继续'+action+'?',
                    closable: true, // <-- Default value is false
                    draggable: true, // <-- Default value is false
                    btnCancelLabel: '进入列表页', // <-- Default value is 'Cancel',
                    btnOKLabel: '继续'+action, // <-- Default value is 'OK',
                    btnOKClass: 'btn-primary', // <-- If you didn't specify it, dialog type will be used,
                    callback: function(result) {
                        // result will be true if button was click, while it will be false if users close the dialog directly.
                        if(result) {
                            if(setFile.saveData.action === "add")
                                location.href="index.php?p=backend&c=File&a=add&catId="+setFile.catId+"&type="+setFile.saveData.fileType+"&folder="+setFile.saveData.folder;
                            else if(setFile.saveData.action === "update")
                                location.href="index.php?p=backend&c=File&a=update&catId="+setFile.catId+"&type="+setFile.saveData.fileType+"&folder="+setFile.saveData.folder+"&uid="+setFile.saveData.id;
                        }else {
                            location.href = "index.php?p=backend&c=File&a=list&catId="+setFile.catId+"&type="+setFile.saveData.fileType+"&folder="+setFile.saveData.folder;
                        }
                    }
                });
            }
            else{
                BootstrapDialog.alert(json.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetFile服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setFile.DrawName = function() {
    if(setFile.debug) console.log("setFile.DrawName",setFile.saveData);
    var value = (setFile.saveData.name)?setFile.saveData.name:"";
    var params = {
        divId:"main-group",
        idPerfix: setFile.idPerfix+"-name",
        value:value,
        title:"文件名：",
        placeholder:'请输入文件名. e.g. 布艺双人沙发',
        type:"text",
        readonly:"",
        required:"required"
    };
    customTextWidget.Draw(params);
};

setFile.DrawDescription = function() {
    var value = (setFile.saveData.description)?setFile.saveData.description:"";
    var params = {
        divId:"main-group",
        idPerfix: setFile.idPerfix+"-description",
        title:"描述：",
        placeholder:'请输入备注. e.g. 带贴图',
        value:value
    };
    customTextareaWidget.Draw(params);
};

setFile.DrawPreviewImage = function() {
    if(setFile.debug) console.log("setFile.DrawImage",setFile.saveData.previewImage);
    var params = {
        divId:"main-group",
        idPrefix: setFile.idPerfix+"-previewImage",
        imageWidth: 250,
        imageHeight: 300,
        imageUrl:setFile.saveData.fileUrl,
        thumbnailImageUrl:setFile.saveData.perviewImage,
        fileSize:setFile.saveData.fileSize,
        title:"图片",
        helpText:" "
    };
    
    if(setFile.saveData.fileType == 2){
        params.onFinishCallBack = setFile.UploadFileOnFinishCB;
    }
    
    customImageWidget.Draw(params);
};

setFile.DrawUploadFile = function() {
    if(setFile.debug) console.log("setFile.DrawUploadFile",setFile.saveData.fileUrl);
    if(setFile.saveData.fileType == 1 || setFile.saveData.fileType == 3){
        var params = {
            divId:"main-group",
            idPrefix: setFile.idPerfix+"-fileUpload",
            fileUrl:setFile.saveData.fileUrl,
            fileSize: setFile.saveData.fileSize,
            title:"文件",
            onFinishCallBack:setFile.UploadFileOnFinishCB,
            helpText:" "
        };
        customFileUploadWidget.Draw(params);
    }
};

setFile.DrawProject = function() {
    if(setFile.debug) console.log("setFile.DrawProject",setFile.saveData);
    var selectedId = (setFile.saveData.projectList)?(setFile.saveData.projectList).split(","):"";
    var option = [];
    
    $.each(setFile.projectList, function(index,project) {
        var selected = 0;
        $.each(selectedId, function(index,projectId) {
            if(project.ProjectId === projectId)
                selected = 1;
        });
        option.push({name: project.ProjectName, id:project.ProjectId, selected: selected });
    });
    
    var params = {
        divId:"main-group",
        idPerfix: setFile.idPerfix+"-Project",
        placeholder: "",
        value:option,
        type: "multiple",
        title:"所属项目：<a onClick=\"projectDialog.AddProject()\">[添加]</a> <a onClick=\"projectDialog.EditProject()\">[修改]</a>"
    };   
    customSelectBoxWidget.Draw(params);
};

setFile.DrawCat = function() {
    var selectedItemsId = null;
    if(setFile.saveData.catList){
        selectedItemsId = (setFile.saveData.catList).split(",");
    }
    
    var configSetting = {};
    configSetting.divId = "main-group";
    configSetting.title = "所属分类：<a onClick=\"fileCatDialog.AddFileCat()\">[添加]</a> <a onClick=\"fileCatDialog.EditFileCat()\">[修改]</a>";
    configSetting.selectData = setFile.catList;
    configSetting.selectedItemsId = (selectedItemsId)?selectedItemsId:"";
    if(setFile.csmsbwObj){ 
        setFile.csmsbwObj.ReDraw(configSetting); 
    }
    else{
        setFile.csmsbwObj = customSubMenuSelectBoxWidget.Create();
        setFile.csmsbwObj.Draw(configSetting);
    }
};

setFile.DrawTag = function() {
//    var selectedData = [];
//    if(setFile.saveData.tagList){
//        var selectedTag = (setFile.saveData.tagList).split(",");
//        $.each(setFile.tagList, function(index,tag) {
//            $.each(selectedTag,function(selIndex,selTagId) {
//                if(selTagId === tag.value){
//                    selectedData.push(tag);
//                }
//            });
//        });
//    }
    
    //var configSetting = {};
    //configSetting.divId = "main-group";
    //configSetting.title = "所属标签：";//<a onClick=\"tagDialog.AddTag()\">[添加可选标签]</a>
    //configSetting.url = 'ws/GetTagList.php?format=json';
    //configSetting.selectedData = selectedData;//{"value":1 ,"text": "木"}
    //setFile.csmtagsObj = customTagsGroup.Create();
    //setFile.csmtagsObj.Draw(configSetting);
    var option = [];
    $.each(setFile.tagList, function(index,tag) {
        var selected = 0;
        if(setFile.saveData.tagList){
            var selectedTag = (setFile.saveData.tagList).split(",");
	        $.each(selectedTag, function(index,selTagId) {
	        	if(selTagId === tag.value){
	                selected = 1;
	        	}
	        });
        }
        option.push({name: tag.text, id:tag.value, selected: selected });
    });
    
    var params = {
        divId:"main-group",
        idPerfix: setFile.idPerfix+"-Tag",
        placeholder: "",
        value:option,
        type: "multiple",
        title:"所属标签："
    };   
    customSelectBoxWidget.Draw(params);
};

setFile.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: setFile.idPerfix+"-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };
    
    var html = '<div class="form-group">'+
                    '<div class="col-lg-offset-2 col-lg-10">'+
                        '<button type="submit" class="btn btn-success pull-right" id="setFile-submit-btn" >'+params.title+'</button>'+
                    '</div>'+
                '</div>';
    $("#"+params.divId).append(html);
    
    $("#main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#setFile-submit-btn").hasClass('disabled');
        if(!disabled){
            setFile.Save();
        }
    });
};

setFile.Save = function() {
    setFile.saveData.name = $("#setFile-name-inputText").val();
    setFile.saveData.perviewImage = $("#setFile-previewImage-image").attr("thumbnail");
    if(setFile.saveData.fileType == 1 || setFile.saveData.fileType == 3){
        setFile.saveData.fileUrl = $("#setFile-fileUpload-file").attr("val");
        setFile.saveData.fileSize = $('#setFile-fileUpload-file').attr("size");
    }
    else{
        setFile.saveData.fileUrl = $("#setFile-previewImage-image").attr("val");
        setFile.saveData.fileSize = $('#setFile-previewImage-image').attr("size");
    }
    setFile.saveData.description = $("#setFile-description-inputTextarea").val();
    setFile.saveData.projectList = ($("#setFile-Project-selectBox").val())?($("#setFile-Project-selectBox").val()).join():"";
    setFile.saveData.catList = setFile.csmsbwObj.GetSelectedItemId();
    setFile.saveData.tagList = ($("#setFile-Tag-selectBox").val())?($("#setFile-Tag-selectBox").val()).join():"";//setFile.csmtagsObj.GetSelectedData();
    
    var isPass = setFile.CheckSaveValue();
    if(isPass) setFile.RequestSetFile();
};

setFile.CheckSaveValue = function() {
    if(setFile.debug) console.log("setFile.Save",setFile.saveData);
    var errorMsg = "";
    if(setFile.saveData.perviewImage.length === 0){
        errorMsg = "请上传预览图";
    }
//    if(setFile.saveData.projectList.length === 0){
//        errorMsg = "请选择所属项目";
//    }
    if(setFile.saveData.catList.length === 0){
        errorMsg = "请选择所属分类";
    }
    if(setFile.saveData.fileUrl.length === 0){
        errorMsg = "请上传文件";
    }
    if(errorMsg.length > 0){
        BootstrapDialog.alert(errorMsg);
        return false;
    }
    else{
        return true;
    }
};

$(document).ready(function () {
    setFile.RequestFileType();
    setFile.Init();
    setFile.RequestProjectList();
    setFile.RequestTagList();
    
    setFile.DrawName();
    setFile.DrawPreviewImage();
    setFile.DrawUploadFile();
    setFile.DrawDescription();
    setFile.DrawProject();
    setFile.DrawCat();
    setFile.DrawTag();
    setFile.DrawSaveBtn();
    
    $('#main-group').validator();
});

setFile.Init = function() {
    setFile.saveData.action = $.urlParams("get", "a");
    setFile.saveData.fileType = $.urlParams("get", "type");
    setFile.saveData.folder = $.urlParams("get", "folder");
    
    if(setFile.saveData.action === "update"){
        setFile.saveData.id = $.urlParams("get", "uid");
        setFile.RequestFile();
    }
    
    setFile.catId = $.urlParams("get", "catId");
    setFile.SetTitle();
    setFile.RequestCatList();
};

setFile.UploadFileOnFinishCB = function(data) {
    $.each(data, function (index, file) {
        var fileName = file.orgName;
        $("#setFile-name-inputText").val(fileName.substring(0,fileName.lastIndexOf(".")));
        setFile.saveData.name = file.name;
    });
};

setFile.SetTitle = function() {
    var title = "";
    var href = "index.php?p=backend&c=File&a=list&catId="+setFile.catId+"&type="+setFile.saveData.fileType+"&folder="+setFile.saveData.folder;
    
    $.each(setFile.fileTypeList,function(index,type) {
        if(type.FileTypeId == setFile.saveData.fileType){
            title = type.FileTypeName;
            return true;
        }
    });
    
    $("#setFile-nav-title").text(title);
    $("#setFile-nav-title").attr("href",href);
    
    if(setFile.saveData.action === "add"){
        title = "添加";
    }
    else if(setFile.saveData.action === "update"){
        title = "修改";
    }
    $("#setFile-title").text(title);
};