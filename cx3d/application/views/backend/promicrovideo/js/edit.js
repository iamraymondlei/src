/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditPromicrovideo = {
    debug:false,
    saveData:{}
};

EditPromicrovideo.DrawTitle = function() {
    if(EditPromicrovideo.debug) console.log("ModifyConcept.DrawTitle",EditPromicrovideo.saveData);
    var value = (EditPromicrovideo.saveData.Title)?EditPromicrovideo.saveData.Title:"";
    var params = {
        divId:"EditPromicrovideo-Title",
        idPerfix: "EditPromicrovideo-Title",
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

EditPromicrovideo.DrawRepresentImage = function() {
    if(EditPromicrovideo.debug) console.log("EditPromicrovideo.PreviewImageUrl",EditPromicrovideo.saveData.PreviewImageUrl );
    var params = {
        divId:"main-group",
        idPrefix:"EditPromicrovideo-PreviewImageUrl",
        imageWidth: 250,
        imageHeight: 300,
        imageUrl:EditPromicrovideo.saveData.PreviewImageUrl,
        thumbnailImageUrl:EditPromicrovideo.saveData.PreviewImageUrl,
        title:"代表图",
        helpText:" ",
        uploadWS:EditPromicrovideo.uploadWS,
        name:"PreviewImageUrl"
    };
    customImageWidget.Draw(params);
};

EditPromicrovideo.DrawVideoUrl = function() {
    if(EditPromicrovideo.debug) console.log("ModifyConcept.DrawTitle",EditPromicrovideo.saveData);
    var value = (EditPromicrovideo.saveData.VideoUrl )?EditPromicrovideo.saveData.VideoUrl :"";
    var params = {
        divId:"EditPromicrovideo-VideoUrl",
        idPerfix: "EditPromicrovideo-VideoUrl",
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

EditPromicrovideo.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditPromicrovideo-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditPromicrovideo-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#EditPromicrovideo-submit-btn").hasClass('disabled');
        if(!disabled){
            document.mainForm.submit();
        }
    });
};

EditPromicrovideo.SetPostUrl = function() {
    var url = "index.php?p=backend&c=promicrovideo";
    url+= "&a=update";
    url+= "&id="+EditPromicrovideo.saveData.NewsId;
    url+= "&ps="+EditPromicrovideo.pageSize;
    url+= "&pi="+EditPromicrovideo.pageIndex;
    url+= "&ob="+EditPromicrovideo.orderBy;
    url+= "&sb="+EditPromicrovideo.sortBy;
    url+= "&ci="+EditPromicrovideo.catId;
    url+= "&vid="+EditPromicrovideo.videoId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditPromicrovideo.DrawTitle();
    EditPromicrovideo.DrawRepresentImage();
    EditPromicrovideo.DrawVideoUrl();
    EditPromicrovideo.DrawSaveBtn();
    EditPromicrovideo.SetPostUrl();
});