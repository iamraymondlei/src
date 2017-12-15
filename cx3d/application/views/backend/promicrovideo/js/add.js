/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var AddPromicrovideo = {
    debug:false,
    saveData:{}
};

AddPromicrovideo.DrawTitle = function() {
    if(AddPromicrovideo.debug) console.log("ModifyConcept.DrawTitle",AddPromicrovideo.saveData);
    var value = "";
    var params = {
        divId:"AddPromicrovideo-Title",
        idPerfix: "AddPromicrovideo-Title",
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

AddPromicrovideo.DrawRepresentImage = function() {
    if(AddPromicrovideo.debug) console.log("AddPromicrovideo.PreviewImageUrl",AddPromicrovideo.saveData.PreviewImageUrl );
    var params = {
        divId:"main-group",
        idPrefix:"AddPromicrovideo-PreviewImageUrl",
        imageWidth: 250,
        imageHeight: 300,
        imageUrl:AddPromicrovideo.saveData.PreviewImageUrl ,
        thumbnailImageUrl:"" ,
        title:"代表图",
        helpText:" ",
        uploadWS:AddPromicrovideo.uploadWS,
        name:"PreviewImageUrl"
    };
    customImageWidget.Draw(params);
};

AddPromicrovideo.DrawVideoUrl = function() {
    if(AddPromicrovideo.debug) console.log("ModifyConcept.DrawTitle",AddPromicrovideo.saveData);
    var value = "";
    var params = {
        divId:"AddPromicrovideo-VideoUrl",
        idPerfix: "AddPromicrovideo-VideoUrl",
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

AddPromicrovideo.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "AddPromicrovideo-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="AddPromicrovideo-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#AddPromicrovideo-submit-btn").hasClass('disabled');
        if(!disabled){
            document.mainForm.submit();
        }
    });
};

AddPromicrovideo.SetPostUrl = function() {
    var url = "index.php?p=backend&c=promicrovideo";
    url+= "&a=update";
    url+= "&id="+AddPromicrovideo.newsId;
    url+= "&ps="+AddPromicrovideo.pageSize;
    url+= "&pi="+AddPromicrovideo.pageIndex;
    url+= "&ob="+AddPromicrovideo.orderBy;
    url+= "&sb="+AddPromicrovideo.sortBy;
    url+= "&ci="+AddPromicrovideo.catId;
    $("#main-group").attr("action",url);
};

$(function () {
    AddPromicrovideo.DrawTitle();
    AddPromicrovideo.DrawRepresentImage();
    AddPromicrovideo.DrawVideoUrl();
    AddPromicrovideo.DrawSaveBtn();
    AddPromicrovideo.SetPostUrl();
});