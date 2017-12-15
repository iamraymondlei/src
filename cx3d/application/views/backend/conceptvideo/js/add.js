/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var AddConceptvideo = {
    debug:false,
    saveData:{}
};

AddConceptvideo.DrawTitle = function() {
    if(AddConceptvideo.debug) console.log("ModifyConcept.DrawTitle",AddConceptvideo.saveData);
    var value = "";
    var params = {
        divId:"AddConceptvideo-Title",
        idPerfix: "AddConceptvideo-Title",
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

AddConceptvideo.DrawRepresentImage = function() {
    if(AddConceptvideo.debug) console.log("AddConceptvideo.PreviewImageUrl",AddConceptvideo.saveData.PreviewImageUrl );
    var params = {
        divId:"main-group",
        idPrefix:"AddConceptvideo-PreviewImageUrl",
        imageWidth: 250,
        imageHeight: 300,
        imageUrl:AddConceptvideo.saveData.PreviewImageUrl ,
        thumbnailImageUrl:"" ,
        title:"代表图",
        helpText:" ",
        uploadWS:AddConceptvideo.uploadWS,
        name:"PreviewImageUrl"
    };
    customImageWidget.Draw(params);
};

AddConceptvideo.DrawVideoUrl = function() {
    if(AddConceptvideo.debug) console.log("ModifyConcept.DrawTitle",AddConceptvideo.saveData);
    var value = "";
    var params = {
        divId:"AddConceptvideo-VideoUrl",
        idPerfix: "AddConceptvideo-VideoUrl",
        value:value,
        title:"视频：",
        placeholder:'请输入视频地址',
        type:"text",
        readonly:"",
        required:"required",
        name:"VideoUrl"
    };
    customTextWidget.Draw(params);
};

AddConceptvideo.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "AddConceptvideo-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="AddConceptvideo-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#AddConceptvideo-submit-btn").hasClass('disabled');
        if(!disabled){
            document.mainForm.submit();
        }
    });
};

AddConceptvideo.SetPostUrl = function() {
    var url = "index.php?p=backend&c=conceptvideo";
    url+= "&a=update";
    url+= "&id="+AddConceptvideo.newsId;
    url+= "&ps="+AddConceptvideo.pageSize;
    url+= "&pi="+AddConceptvideo.pageIndex;
    url+= "&ob="+AddConceptvideo.orderBy;
    url+= "&sb="+AddConceptvideo.sortBy;
    url+= "&ci="+AddConceptvideo.catId;
    $("#main-group").attr("action",url);
};

$(function () {
    AddConceptvideo.DrawTitle();
    AddConceptvideo.DrawRepresentImage();
    AddConceptvideo.DrawVideoUrl();
    AddConceptvideo.DrawSaveBtn();
    AddConceptvideo.SetPostUrl();
});