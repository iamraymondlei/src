/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditTypical = {
    debug:false,
    saveData:{}
};

EditTypical.DrawTitle = function() {
    if(EditTypical.debug) console.log("ModifyConcept.DrawTitle",EditTypical.saveData);
    var value = (EditTypical.saveData.Title)?EditTypical.saveData.Title:"";
    var params = {
        divId:"EditTypical-Title",
        idPerfix: "EditTypical-Title",
        value:value,
        title:"标题：",
        placeholder:'请输入标题',
        type:"text",
        readonly:"",
        required:"required",
        name:"Title"
    };
    customTextWidget.Draw(params);
};

EditTypical.DrawVideoUrl = function() {
    if(EditTypical.debug) console.log("ModifyConcept.DrawTitle",EditTypical.saveData);
    var value = (EditTypical.saveData.VideoUrl)?EditTypical.saveData.VideoUrl:"";
    var params = {
        divId:"EditTypical-VideoUrl",
        idPerfix: "EditTypical-VideoUrl",
        value:value,
        title:"视频：",
        placeholder:'请输入视频地址',
        type:"text",
        readonly:"",
        required:"",
        name:"VideoUrl"
    };
    customTextWidget.Draw(params);
};

EditTypical.DrawContent = function() {
    var value = $("#EditTypical-Content").html();
    var params = {
        divId:"EditTypical-Content-Group",
        idPerfix: "EditTypical",
        value:value,
        title:"内容：",
        readonly:"",
        required:"required",
        name:"ArticleContent"
    };
    customEditorWidget.Draw(params);
    $("#EditTypical-Content").remove();
};

EditTypical.DrawAlbum = function() {
    var params = {
        divId: "EditTypical-album",
        uploadWS:EditTypical.uploadWS,
        title: "图集：",
        helpText: "",
        name: "ImageList",
        data:(EditTypical.saveData.ImageList)?EditTypical.saveData.ImageList:[]
    };
    CustomAlbum.Draw(params);
};

EditTypical.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditTypical-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditTypical-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var content = CKEDITOR.instances.EditTypicalCkeditor.getData();
        var imageList = CustomAlbum.data;
        var disabled = $("#EditTypical-submit-btn").hasClass('disabled');
        if(!disabled && imageList === "" ){
            BootstrapDialog.alert("请上传图片");
        }
        else if(!disabled && content === ""){
            BootstrapDialog.alert("请填写內容");
        }
        else{
            document.mainForm.submit();
        }
    });
};

EditTypical.SetPostUrl = function() {
    var url = "index.php?p=backend&c=typical";
    url+= "&a=update";
    url+= "&id="+EditTypical.saveData.NewsId;
    url+= "&ps="+EditTypical.pageSize;
    url+= "&pi="+EditTypical.pageIndex;
    url+= "&ob="+EditTypical.orderBy;
    url+= "&sb="+EditTypical.sortBy;
    url+= "&ci="+EditTypical.catId;
    url+= "&aid="+EditTypical.saveData.ArticleId;
    url+= "&vid="+EditTypical.saveData.VideoId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditTypical.DrawTitle();
    EditTypical.DrawVideoUrl();
    EditTypical.DrawContent();
    EditTypical.DrawAlbum();
    EditTypical.DrawSaveBtn();
    EditTypical.SetPostUrl();
});