/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditCulture = {
    debug:false,
    saveData:{}
};

EditCulture.DrawTitle = function() {
    if(EditCulture.debug) console.log("ModifyConcept.DrawTitle",EditCulture.saveData);
    var value = (EditCulture.saveData.Title)?EditCulture.saveData.Title:"";
    var params = {
        divId:"EditCulture-title",
        idPerfix: "EditCulture-Title",
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

EditCulture.DrawAlbum = function() {
    var params = {
        divId: "EditCulture-album",
        uploadWS:EditCulture.uploadWS,
        title: "图集：",
        helpText: "",
        name: "ImageList",
        data:(EditCulture.saveData.ImageList)?EditCulture.saveData.ImageList:[]
    };
    CustomAlbum.Draw(params);
};

EditCulture.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditCulture-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditCulture-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var imageList = CustomAlbum.data;
        var disabled = $("#EditCulture-submit-btn").hasClass('disabled');
        if(!disabled && imageList === "" ){
            BootstrapDialog.alert("请上传图片");
        }
        else{
            document.mainForm.submit();
        }
    });
};

EditCulture.SetPostUrl = function() {
    var url = "index.php?p=backend&c=culture";
    url+= "&a=update";
    url+= "&id="+EditCulture.newsId;
    url+= "&ps="+EditCulture.pageSize;
    url+= "&pi="+EditCulture.pageIndex;
    url+= "&ob="+EditCulture.orderBy;
    url+= "&sb="+EditCulture.sortBy;
    url+= "&ci="+EditCulture.catId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditCulture.DrawTitle();
    EditCulture.DrawAlbum();
    EditCulture.DrawSaveBtn();
    EditCulture.SetPostUrl();
});