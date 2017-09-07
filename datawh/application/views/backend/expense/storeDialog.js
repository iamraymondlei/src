/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var storeDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    storeId:null,
    storeName:"",
    storeAddress:""
};

storeDialog.Clear = function() {
    storeDialog.html = "";
    storeDialog.action = null;
    storeDialog.dialogObj = null;
    storeDialog.storeId = null;
    storeDialog.storeName = "";
    storeDialog.storeAddress = "";
};

storeDialog.SetHtml = function() {
    storeDialog.html ='<div class="container-fluid" id="">'+
                            '<form class="form-horizontal" id="storeDialog-main-group">'+
                            '</form>'+
                        '</div>';
};

storeDialog.AddStore = function() {
    storeDialog.Clear();
    storeDialog.SetHtml();
    storeDialog.Init("添加");
    storeDialog.action = "add";
};

storeDialog.EditStore = function() {
    storeDialog.Clear();
    storeDialog.SetHtml();
    storeDialog.storeId = $('#'+setExpense.idPerfix+"-store-selectBox").val();
    if(storeDialog.storeId){
        var storeObj = {};
        $.each(setExpense.storeList,function(index,store) {
           if(store.StoreId === storeDialog.storeId) {
               storeObj = store;
               return true;
           }
        });

        storeDialog.storeName = storeObj.StoreName;
        storeDialog.storeAddress = storeObj.Address;
        storeDialog.Init("修改");
        storeDialog.action = "update";
    }
    else{
        BootstrapDialog.alert("请先选择店铺。");
    }
};

storeDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: storeDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            storeDialog.dialogObj = dialog;
            storeDialog.DrawStoreName();
            storeDialog.DrawStoreAddress();
            storeDialog.DrawSaveBtn();
            $('#storeDialog-main-group').validator();
        }
    }); 
};

storeDialog.DrawStoreName = function() {
    var params = {
        divId:"storeDialog-main-group",
        idPerfix: "storeDialog-name",
        value:storeDialog.storeName,
        title:"店铺名称：",
        placeholder:'请输入店铺名称. e.g. 广州东百花地湾百货',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

storeDialog.DrawStoreAddress = function() {
    var params = {
        divId:"storeDialog-main-group",
        idPerfix: "storeDialog-address",
        value:storeDialog.storeAddress,
        title:"地址：",
        placeholder:'请输入地址. e.g. 花地大道200号',
        type:"text",
        readonly:"",
        required:""
    };
    customTextWidget.Draw(params);
};

storeDialog.DrawSaveBtn = function() {
    var params = {
        divId:"storeDialog-main-group",
        btnId: "storeDialog-submit-btn",
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
        
    $("#storeDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            storeDialog.Save();
        }
    });
};

storeDialog.Save = function() {
    var saveData = storeDialog.GetSaveData();
    $.ajax({
        url: 'ws/SetStore.php',
        type: 'POST',
        data: saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            var saveResult = json;
            if(saveResult && saveResult.WebService.ResultCode === 200){
                setExpense.RequestStoreList();
                setExpense.saveData.storeId = saveResult.WebService.Result;
                setExpense.DrawStore();
                storeDialog.dialogObj.close();
            }
            else{
                BootstrapDialog.alert(saveResult.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetStore服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

storeDialog.GetSaveData = function() {
    var saveData = {
        format:'json',
        action:storeDialog.action,
        name:$("#storeDialog-name-inputText").val(),
        address:$("#storeDialog-address-inputText").val()
    };
    if(storeDialog.action === "update"){
        saveData.id = storeDialog.storeId;
    }
    return saveData;
};