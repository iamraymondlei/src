/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditConcept = {
    debug:false,
    saveData:{}
};

EditConcept.DrawTitle = function() {
    if(EditConcept.debug) console.log("ModifyConcept.DrawTitle",EditConcept.saveData);
    var value = (EditConcept.saveData.ArticleTitle)?EditConcept.saveData.ArticleTitle:"";
    var params = {
        divId:"main-group",
        idPerfix: "EditConcept-Title",
        value:value,
        title:"标题：",
        placeholder:'请输入标题',
        type:"text",
        readonly:"",
        required:"required",
        name:"ArticleTitle"
    };
    customTextWidget.Draw(params);
};

EditConcept.DrawSubTitle = function() {
    if(EditConcept.debug) console.log("ModifyConcept.DrawTitle",EditConcept.saveData);
    var value = (EditConcept.saveData.ArticleSubTitle)?EditConcept.saveData.ArticleSubTitle:"";
    var params = {
        divId:"main-group",
        idPerfix: "EditConcept-SubTitle",
        value:value,
        title:"子标题：",
        placeholder:'请输入子标题',
        type:"text",
        readonly:"",
        required:"",
        name:"ArticleSubTitle"
    };
    customTextWidget.Draw(params);
};

EditConcept.DrawRepresentImage = function() {
    if(EditConcept.debug) console.log("EditConcept.ArticleRepresentImageUrl",EditConcept.saveData.ArticleRepresentImageUrl );
    var params = {
        divId:"main-group",
        idPrefix:"EditConcept-RepresentImage",
        imageWidth: 250,
        imageHeight: 300,
        imageUrl:EditConcept.saveData.ArticleRepresentImageUrl ,
        thumbnailImageUrl:EditConcept.saveData.ArticleRepresentImageUrl ,
        title:"代表图",
        helpText:" ",
        uploadWS:EditConcept.uploadWS,
        name:"ArticleRepresentImageUrl"
    };
    customImageWidget.Draw(params);
};

EditConcept.DrawVideoUrl = function() {
    if(EditConcept.debug) console.log("ModifyConcept.DrawTitle",EditConcept.saveData);
    var value = (EditConcept.saveData.VideoUrl)?EditConcept.saveData.VideoUrl:"";
    var params = {
        divId:"main-group",
        idPerfix: "EditConcept-VideoUrl",
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

EditConcept.DrawContent = function() {
    var value = $("#EditConcept-Content").html();
    var params = {
        divId:"main-group",
        idPerfix: "EditConcept",
        value:value,
        title:"内容：",
        readonly:"",
        required:"required",
        name:"ArticleContent"
    };
    customEditorWidget.Draw(params);
    $("#EditConcept-Content").remove();
};

EditConcept.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditConcept-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditConcept-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var content = CKEDITOR.instances.EditConceptCkeditor.getData();
        var disabled = $("#EditConcept-submit-btn").hasClass('disabled');
        if(!disabled && content === ""){
            BootstrapDialog.alert("请填写內容");
        }
        else{
            document.mainForm.submit();
        }
    });
};

EditConcept.SetPostUrl = function() {
    var url = "index.php?p=backend&c=concept";
    url+= "&a=update";
    url+= "&id="+EditConcept.newsId;
    url+= "&ps="+EditConcept.pageSize;
    url+= "&pi="+EditConcept.pageIndex;
    url+= "&ob="+EditConcept.orderBy;
    url+= "&sb="+EditConcept.sortBy;
    url+= "&ci="+EditConcept.catId;
    url+= "&vid="+EditConcept.saveData.VideoId;
    url+= "&aid="+EditConcept.saveData.ArticleId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditConcept.DrawTitle();
    EditConcept.DrawSubTitle();
    EditConcept.DrawRepresentImage();
    EditConcept.DrawVideoUrl();
    EditConcept.DrawContent();
    EditConcept.DrawSaveBtn();
    EditConcept.SetPostUrl();
});