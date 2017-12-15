/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditConceptvideo = {
    debug:false,
    saveData:{}
};

EditConceptvideo.DrawTitle = function() {
    if(EditConceptvideo.debug) console.log("ModifyConcept.DrawTitle",EditConceptvideo.saveData);
    var value = (EditConceptvideo.saveData.Title)?EditConceptvideo.saveData.Title:"";
    var params = {
        divId:"EditConceptvideo-Title",
        idPerfix: "EditConceptvideo-Title",
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

EditConceptvideo.DrawRepresentImage = function() {
    if(EditConceptvideo.debug) console.log("EditConceptvideo.PreviewImageUrl",EditConceptvideo.saveData.PreviewImageUrl );
    var params = {
        divId:"main-group",
        idPrefix:"EditConceptvideo-PreviewImageUrl",
        imageWidth: 250,
        imageHeight: 300,
        imageUrl:EditConceptvideo.saveData.PreviewImageUrl,
        thumbnailImageUrl:EditConceptvideo.saveData.PreviewImageUrl,
        title:"代表图",
        helpText:" ",
        uploadWS:EditConceptvideo.uploadWS,
        name:"PreviewImageUrl"
    };
    customImageWidget.Draw(params);
};

EditConceptvideo.DrawVideoUrl = function() {
    if(EditConceptvideo.debug) console.log("ModifyConcept.DrawTitle",EditConceptvideo.saveData);
    var value = (EditConceptvideo.saveData.VideoUrl )?EditConceptvideo.saveData.VideoUrl :"";
    var params = {
        divId:"EditConceptvideo-VideoUrl",
        idPerfix: "EditConceptvideo-VideoUrl",
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

EditConceptvideo.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditConceptvideo-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditConceptvideo-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#EditConceptvideo-submit-btn").hasClass('disabled');
        if(!disabled){
            document.mainForm.submit();
        }
    });
};

EditConceptvideo.SetPostUrl = function() {
    var url = "index.php?p=backend&c=conceptvideo";
    url+= "&a=update";
    url+= "&id="+EditConceptvideo.saveData.NewsId;
    url+= "&ps="+EditConceptvideo.pageSize;
    url+= "&pi="+EditConceptvideo.pageIndex;
    url+= "&ob="+EditConceptvideo.orderBy;
    url+= "&sb="+EditConceptvideo.sortBy;
    url+= "&ci="+EditConceptvideo.catId;
    url+= "&vid="+EditConceptvideo.videoId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditConceptvideo.DrawTitle();
    EditConceptvideo.DrawRepresentImage();
    EditConceptvideo.DrawVideoUrl();
    EditConceptvideo.DrawSaveBtn();
    EditConceptvideo.SetPostUrl();
});