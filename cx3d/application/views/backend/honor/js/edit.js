/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditHonor = {
    debug:false,
    saveData:{}
};

EditHonor.DrawTitle = function() {
    if(EditHonor.debug) console.log("ModifyConcept.DrawTitle",EditHonor.saveData);
    var value = (EditHonor.saveData.Title)?EditHonor.saveData.Title:"";
    var params = {
        divId:"EditHonor-title",
        idPerfix: "EditHonor-Title",
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

EditHonor.DrawAlbum = function() {
    var params = {
        divId: "EditHonor-album",
        uploadWS:EditHonor.uploadWS,
        title: "图集：",
        helpText: "",
        name: "ImageList",
        data:(EditHonor.saveData.ImageList)?EditHonor.saveData.ImageList:[]
    };
    CustomAlbum.Draw(params);
};

EditHonor.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditHonor-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditHonor-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var imageList = CustomAlbum.data;
        var disabled = $("#EditHonor-submit-btn").hasClass('disabled');
        if(!disabled && imageList === "" ){
            BootstrapDialog.alert("请上传图片");
        }
        else{
            document.mainForm.submit();
        }
    });
};

EditHonor.SetPostUrl = function() {
    var url = "index.php?p=backend&c=honor";
    url+= "&a=update";
    url+= "&id="+EditHonor.saveData.NewsId;
    url+= "&ps="+EditHonor.pageSize;
    url+= "&pi="+EditHonor.pageIndex;
    url+= "&ob="+EditHonor.orderBy;
    url+= "&sb="+EditHonor.sortBy;
    url+= "&ci="+EditHonor.catId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditHonor.DrawTitle();
    EditHonor.DrawAlbum();
    EditHonor.DrawSaveBtn();
    EditHonor.SetPostUrl();
});