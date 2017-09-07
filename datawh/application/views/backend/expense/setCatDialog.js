/* 
 * To change this license header, choose License Headers in FileCat Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var setCatDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    catId:null,
    catName:"",
    parentId:""
};

setCatDialog.Clear = function() {
    setCatDialog.html = "";
    setCatDialog.action = null;
    setCatDialog.dialogObj = null;
    setCatDialog.catId = null;
    setCatDialog.catName = "";
    setCatDialog.parentId = "";
};

setCatDialog.SetHtml = function() {
    setCatDialog.html ='<div class="container-fluid" id="">'+
                            '<form class="form-horizontal" id="setCatDialog-main-group">'+
                            '</form>'+
                        '</div>';
};

setCatDialog.AddFileCat = function(id) {
    setCatDialog.Clear();
    setCatDialog.SetHtml();
    setCatDialog.Init("添加");
    setCatDialog.action = "add";
    setCatDialog.parentId = id;
};

setCatDialog.EditFileCat = function(id,name,pId) {
    setCatDialog.Clear();
    setCatDialog.SetHtml();
    
    if(id && name){
        setCatDialog.catName = name;
        setCatDialog.catId = id;
        setCatDialog.parentId = pId;
        setCatDialog.Init("修改");
        setCatDialog.action = "update";
    }
    else{
        BootstrapDialog.alert("请先选择一个分类。");
    }
};

setCatDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: setCatDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            setCatDialog.dialogObj = dialog;
            setCatDialog.DrawFileCatName();
            setCatDialog.DrawSaveBtn();
            $('#setCatDialog-main-group').validator();
        }
    }); 
};

setCatDialog.DrawFileCatName = function() {
    var params = {
        divId:"setCatDialog-main-group",
        idPerfix: "setCatDialog-name",
        value:setCatDialog.catName,
        title:"分类名称：",
        placeholder:'请输入分类名称. e.g. 沙发',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

setCatDialog.DrawSaveBtn = function() {
    var params = {
        divId:"setCatDialog-main-group",
        btnId: "setCatDialog-submit-btn",
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
    
    $("#setCatDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            setCatDialog.Save();
        }
    });
};

setCatDialog.Save = function() {
    var saveData = setCatDialog.GetSaveData();
    if(saveData){
        $.ajax({
            url: 'ws/SetProductCat.php',
            type: 'POST',
            data: saveData,
            dataType: 'json',
            async: false,
            success: function(json) {
                var saveResult = json;
                if(saveResult && saveResult.WebService.ResultCode === 200){
                    setCat.RefreshNestable();
                    setCatDialog.dialogObj.close();
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

setCatDialog.GetSaveData = function() {
    var saveData = {
        format:'json',
        action:setCatDialog.action,
        name:$("#setCatDialog-name-inputText").val(),
        parentId:setCatDialog.parentId
    };
    if(setCatDialog.action === "update"){
        saveData.id = setCatDialog.catId;
    }
    return saveData;
};