/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditHistory = {
    debug:false,
    saveData:{}
};

EditHistory.DrawTitle = function() {
    if(EditHistory.debug) console.log("ModifyConcept.DrawTitle",EditHistory.saveData);
    var value = (EditHistory.saveData.Title)?EditHistory.saveData.Title:"";
    var params = {
        divId:"EditHistory-title",
        idPerfix: "EditHistory-Title",
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

EditHistory.DrawContent = function() {
    var value = $("#EditHistory-Content").html();
    var params = {
        divId:"EditHistory-Content-Group",
        idPerfix: "EditHistory",
        value:value,
        title:"内容：",
        readonly:"",
        required:"required",
        name:"ArticleContent"
    };
    customEditorWidget.Draw(params);
    $("#EditHistory-Content").remove();
};

EditHistory.DrawAlbum = function() {
    var params = {
        divId: "EditHistory-album",
        uploadWS:EditHistory.uploadWS,
        title: "图集：",
        helpText: "",
        name: "ImageList",
        data:(EditHistory.saveData.ImageList)?EditHistory.saveData.ImageList:[]
    };
    CustomAlbum.Draw(params);
};

EditHistory.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditHistory-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditHistory-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var content = CKEDITOR.instances.EditHistoryCkeditor.getData();
        var imageList = CustomAlbum.data;
        var disabled = $("#EditHistory-submit-btn").hasClass('disabled');
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

EditHistory.SetPostUrl = function() {
    var url = "index.php?p=backend&c=history";
    url+= "&a=update";
    url+= "&id="+EditHistory.newsId;
    url+= "&ps="+EditHistory.pageSize;
    url+= "&pi="+EditHistory.pageIndex;
    url+= "&ob="+EditHistory.orderBy;
    url+= "&sb="+EditHistory.sortBy;
    url+= "&ci="+EditHistory.catId;
    url+= "&aid="+EditHistory.saveData.ArticleId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditHistory.DrawTitle();
    EditHistory.DrawContent();
    EditHistory.DrawAlbum();
    EditHistory.DrawSaveBtn();
    EditHistory.SetPostUrl();
});