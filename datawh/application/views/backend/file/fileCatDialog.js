/* 
 * To change this license header, choose License Headers in FileCat Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var fileCatDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    fileCatId:null,
    fileCatName:"",
    fileCatParentId:"",
    csmsbwObj:null
};

fileCatDialog.Clear = function() {
    fileCatDialog.html = "";
    fileCatDialog.action = null;
    fileCatDialog.dialogObj = null;
    fileCatDialog.fileCatId = null;
    fileCatDialog.fileCatName = "";
    fileCatDialog.fileCatParentId = "";
};

fileCatDialog.SetHtml = function() {
    fileCatDialog.html ='<div class="container-fluid" id="">'+
                            '<form class="form-horizontal" id="fileCatDialog-main-group">'+
                            '</form>'+
                        '</div>';
};

fileCatDialog.AddFileCat = function() {
    fileCatDialog.Clear();
    fileCatDialog.SetHtml();
    fileCatDialog.Init("添加");
    fileCatDialog.action = "add";
};

fileCatDialog.EditFileCat = function() {
    fileCatDialog.Clear();
    fileCatDialog.SetHtml();
    var fileCat = setFile.csmsbwObj.GetSelectedItem();
    
    if(fileCat.length === 1){
        fileCatDialog.fileCatName = fileCat[0].name;
        fileCatDialog.fileCatId = fileCat[0].id;
        fileCatDialog.fileCatParentId = fileCat[0].parentId;
        fileCatDialog.Init("修改");
        fileCatDialog.action = "update";
    }
    else{
        BootstrapDialog.alert("请先选择一个分类。");
    }
};

fileCatDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: fileCatDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            fileCatDialog.dialogObj = dialog;
            fileCatDialog.DrawFileCatName();
            fileCatDialog.DrawFileCatParent();
            fileCatDialog.DrawSaveBtn();
            $('#fileCatDialog-main-group').validator();
        }
    }); 
};

fileCatDialog.DrawFileCatName = function() {
    var params = {
        divId:"fileCatDialog-main-group",
        idPerfix: "fileCatDialog-name",
        value:fileCatDialog.fileCatName,
        title:"分类名称：",
        placeholder:'请输入分类名称. e.g. 沙发',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

fileCatDialog.DrawFileCatParent = function() {    
    var configSetting = {};
     configSetting.divId = "fileCatDialog-main-group";
     configSetting.title = "所属父分类";
     configSetting.selectData = setFile.catList;
     configSetting.selectedItemsId = (fileCatDialog.fileCatParentId)?[fileCatDialog.fileCatParentId]:false;
     configSetting.isMultipleSelect = false;
     fileCatDialog.csmsbwObj = customSubMenuSelectBoxWidget.Create();
     fileCatDialog.csmsbwObj.Draw(configSetting);
};

fileCatDialog.DrawSaveBtn = function() {
    var params = {
        divId:"fileCatDialog-main-group",
        btnId: "fileCatDialog-submit-btn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };
    
    var html =  '<div class="form-group">'+
                    '<div class="col-lg-offset-2 col-lg-10">'+
                        '<button type="submit" class="btn btn-success pull-right" id="'+params.btnId+'" >'+params.title+'</button>'+
                    '</div>'+
                '</div>';
    $("#"+params.divId).append(html);
        
    $("#fileCatDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            fileCatDialog.Save();
        }
    });
};

fileCatDialog.Save = function() {
    var saveData = fileCatDialog.GetSaveData();
    if(saveData){
        $.ajax({
            url: 'ws/SetFileCat.php',
            type: 'POST',
            data: saveData,
            dataType: 'json',
            async: false,
            success: function(json) {
                var saveResult = json;
                if(saveResult && saveResult.WebService.ResultCode === 200){
                    var catId = $.urlParams("get", "catId");
                    setFile.RequestCatList(catId);
                    setFile.saveData.catList = saveResult.WebService.Result;
                    var selectedCat = setFile.csmsbwObj.GetSelectedItem();
                    $.each(selectedCat, function(index,cat) {
                        setFile.saveData.catList+= ","+cat.id;
                    });
                    setFile.DrawCat();
                    fileCatDialog.dialogObj.close();
                }
                else{
                    BootstrapDialog.alert(saveResult.WebService.ResultMessage);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                BootstrapDialog.alert("访问SetFileCat服务异常!" + XMLHttpRequest.responseText);
            }
        });
    }
};

fileCatDialog.GetSaveData = function() {
    var parentIds = fileCatDialog.csmsbwObj.GetSelectedItem();
    var parentId = "";
    if(parentIds.length > 0){
        $.each(parentIds, function(index,item) {
           parentId = item.id+","; 
        });
        parentId = parentId.substr(0, parentId.length - 1);  
    }
    
    if(parentId.length === 0){
        BootstrapDialog.alert("请选择所属父分类。");
        return false;
    }
    else{
        var saveData = {
            format:'json',
            action:fileCatDialog.action,
            name:$("#fileCatDialog-name-inputText").val(),
            parentId:parentId
        };
        if(fileCatDialog.action === "update"){
            saveData.id = fileCatDialog.fileCatId;
        }
        return saveData;
    }
};