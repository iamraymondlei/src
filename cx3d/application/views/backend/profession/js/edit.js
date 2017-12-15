/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EditProfession = {
    debug:false,
    saveData:{}
};

EditProfession.DrawTitle = function() {
    if(EditProfession.debug) console.log("ModifyConcept.DrawTitle",EditProfession.saveData);
    var value = (EditProfession.saveData.Title)?EditProfession.saveData.Title:"";
    var params = {
        divId:"EditProfession-Title",
        idPerfix: "EditProfession-Title",
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

EditProfession.DrawAlbum = function() {
    var params = {
        divId: "EditProfession-album",
        uploadWS:EditProfession.uploadWS,
        title: "图集：",
        helpText: "",
        name: "ImageList",
        data:(EditProfession.saveData.ImageList)?EditProfession.saveData.ImageList:[]
    };
    CustomAlbum.Draw(params);
};

EditProfession.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: "EditProfession-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };

    var html = '<div class="form-group">'+
        '<div class="col-lg-offset-2 col-lg-10">'+
        '<button type="submit" class="btn btn-success pull-right" id="EditProfession-submit-btn" >'+params.title+'</button>'+
        '</div>'+
        '</div>';
    $("#"+params.divId).append(html);

    $("#main-group").submit(function(e){
        e.preventDefault();
        var imageList = CustomAlbum.data;
        var disabled = $("#EditProfession-submit-btn").hasClass('disabled');
        if(!disabled && imageList === "" ){
            BootstrapDialog.alert("请上传图片");
        }
        else{
            document.mainForm.submit();
        }
    });
};

EditProfession.SetPostUrl = function() {
    var url = "index.php?p=backend&c=Profession";
    url+= "&a=update";
    url+= "&id="+EditProfession.saveData.NewsId;
    url+= "&ps="+EditProfession.pageSize;
    url+= "&pi="+EditProfession.pageIndex;
    url+= "&ob="+EditProfession.orderBy;
    url+= "&sb="+EditProfession.sortBy;
    url+= "&ci="+EditProfession.catId;
    $("#main-group").attr("action",url);
};

$(function () {
    EditProfession.DrawTitle();
    EditProfession.DrawAlbum();
    EditProfession.DrawSaveBtn();
    EditProfession.SetPostUrl();
});