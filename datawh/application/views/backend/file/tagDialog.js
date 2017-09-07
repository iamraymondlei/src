/* 
 * To change this license header, choose License Headers in Tag Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var tagDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    tagId:null,
    tagName:""
};

tagDialog.Clear = function() {
    tagDialog.html = "";
    tagDialog.action = null;
    tagDialog.dialogObj = null;
    tagDialog.tagId = null;
    tagDialog.tagName = "";
};

tagDialog.SetHtml = function() {
    tagDialog.html ='<div class="container-fluid" id="">'+
                        '<form class="form-horizontal" id="tagDialog-main-group">'+
                        '</form>'+
                    '</div>';
};

tagDialog.AddTag = function() {
    tagDialog.Clear();
    tagDialog.SetHtml();
    tagDialog.Init("添加");
    tagDialog.action = "add";
};

tagDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: tagDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            tagDialog.dialogObj = dialog;
            tagDialog.DrawTagName();
            tagDialog.DrawSaveBtn();
            $('#tagDialog-main-group').validator();
        }
    }); 
};

tagDialog.DrawTagName = function() {
    var params = {
        divId:"tagDialog-main-group",
        idPerfix: "tagDialog-name",
        value:tagDialog.tagName,
        title:"标签名称：",
        placeholder:'请输入标签名称. e.g. 木质',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

tagDialog.DrawSaveBtn = function() {
    var params = {
        divId:"tagDialog-main-group",
        btnId: "tagDialog-submit-btn",
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
        
    $("#tagDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            tagDialog.Save();
        }
    });
};

tagDialog.Save = function() {
    var saveData = tagDialog.GetSaveData();
    $.ajax({
        url: 'ws/SetTag.php',
        type: 'POST',
        data: saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            var saveResult = json;
            if(saveResult && saveResult.WebService.ResultCode === 200){
                setFile.RequestTagList();
                setFile.csmtagsObj.Add({"value":saveResult.WebService.Result ,"text": saveData.name});
                setFile.csmtagsObj.RefreshTags(setFile.tagList);
                tagDialog.dialogObj.close();
            }
            else{
                BootstrapDialog.alert(saveResult.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetTag服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

tagDialog.GetSaveData = function() {
    var saveData = {
        format:'json',
        action:tagDialog.action,
        name:$("#tagDialog-name-inputText").val()
    };
    return saveData;
};