/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var projectDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    projectId:null,
    projectName:"",
    projectDesc:""
};

projectDialog.Clear = function() {
    projectDialog.html = "";
    projectDialog.action = null;
    projectDialog.dialogObj = null;
    projectDialog.projectId = null;
    projectDialog.projectName = "";
    projectDialog.projectDesc = "";
};

projectDialog.SetHtml = function() {
    projectDialog.html ='<div class="container-fluid" id="">'+
                            '<form class="form-horizontal" id="projectDialog-main-group">'+
                            '</form>'+
                        '</div>';
};

projectDialog.AddProject = function() {
    projectDialog.Clear();
    projectDialog.SetHtml();
    projectDialog.Init("添加");
    projectDialog.action = "add";
};

projectDialog.EditProject = function() {
    projectDialog.Clear();
    projectDialog.SetHtml();
    projectDialog.projectId = $('#'+setFile.idPerfix+"-Project-selectBox").val();
    if(projectDialog.projectId){
        var projectObj = {};
        $.each(setFile.projectList,function(index,project) {project
           if(project.ProjectId === projectDialog.projectId) {
               projectObj = project;
               return true;
           }
        });

        projectDialog.projectName = projectObj.ProjectName;
        projectDialog.projectDesc = projectObj.Desc;
        projectDialog.Init("修改");
        projectDialog.action = "update";
    }
    else{
        BootstrapDialog.alert("请先选择项目。");
    }
};

projectDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: projectDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            projectDialog.dialogObj = dialog;
            projectDialog.DrawProjectName();
            projectDialog.DrawProjectDesc();
            projectDialog.DrawSaveBtn();
            $('#projectDialog-main-group').validator();
        }
    }); 
};

projectDialog.DrawProjectName = function() {
    var params = {
        divId:"projectDialog-main-group",
        idPerfix: "projectDialog-name",
        value:projectDialog.projectName,
        title:"项目名称：",
        placeholder:'请输入项目名称. e.g. iNest',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

projectDialog.DrawProjectDesc = function() {
    var params = {
        divId:"projectDialog-main-group",
        idPerfix: "projectDialog-desc",
        value:projectDialog.projectDesc,
        title:"描述：",
        placeholder:'请输入描述. e.g. 移动家居城项目',
        type:"text",
        readonly:"",
        required:""
    };
    customTextWidget.Draw(params);
};

projectDialog.DrawSaveBtn = function() {
    var params = {
        divId:"projectDialog-main-group",
        btnId: "projectDialog-submit-btn",
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
        
    $("#projectDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            projectDialog.Save();
        }
    });
};

projectDialog.Save = function() {
    var saveData = projectDialog.GetSaveData();
    $.ajax({
        url: 'ws/SetProject.php',
        type: 'POST',
        data: saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            var saveResult = json;
            if(saveResult && saveResult.WebService.ResultCode === 200){
                setFile.RequestProjectList();
                setFile.saveData.projectId = saveResult.WebService.Result;
                setFile.DrawProject();
                projectDialog.dialogObj.close();
            }
            else{
                BootstrapDialog.alert(saveResult.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetProject服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

projectDialog.GetSaveData = function() {
    var saveData = {
        format:'json',
        action:projectDialog.action,
        name:$("#projectDialog-name-inputText").val(),
        desc:$("#projectDialog-desc-inputText").val()
    };
    if(projectDialog.action === "update"){
        saveData.id = projectDialog.projectId;
    }
    return saveData;
};