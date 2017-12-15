/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var customListGroup = {
    debug:false,
    divId:"",
    typeList:["Textarea","ImageUrl"],//"Textarea","ImageUrl","InputGroup"
    lastAutoNum:0,
    data:{
        GroupList:[
            {value:"http://localhost/malldb/images/0A/0A21FB78-210D-48B6-15C2-90736EBE1E7D.jpg",type:"ImageUrl",sortOrder:"1"},
            {value:"aa",type:"Textarea",sortOrder:"2"},
            {value:"娇俏的木质床头柜，有那么一点憨态可掬，有那么一点自然气息，可以放置公主灯，也可以收纳平日里的小惊喜。",type:"Textarea",sortOrder:"3"},
            {value:[{inputName:"编码",inputVal:"1223"},{inputName:"地址",inputVal:"http://www.baidu.com/"}],type:"InputGroup",sortOrder:"3"}
        ]
    }
}

customListGroup.Init = function(params) {
    customListGroup.lastAutoNum = 0;
    if(params.typeList) customListGroup.typeList = params.typeList;
    customListGroup.divId = params.divId;
    customListGroup.data = params.data;
    customListGroup.DrawGroup();
    customListGroup.DrawAddButton();
    customListGroup.DrawItemList();
    customListGroup.SetSortable();
}

customListGroup.GetSaveData = function() {
    var data = {
        GroupList:[]
    }
    $("#"+customListGroup.divId+" li").each(function(index,item){
        var itemElement = $(item).children(".item-main").children(".item-body").children();
        var itemData = {value:"",type:"Textarea",sortOrder:index};
       
        if( itemElement.is("img") ) {
            itemData.value = itemElement.attr("src");
            itemData.type = "ImageUrl";
        }
        else if( itemElement.is("textarea") ) {
            itemData.value = itemElement.val();
            itemData.type = "Textarea";
        }
        else if( itemElement.is("form") ) {
            itemData.type = "InputGroup";
            itemData.value = [];
            $(itemElement).children("div").each(function(inputIndex,inputItem){
                var keyName = $(inputItem).children("label").text().replace(/：/g,"");
                var keyVal = $(inputItem).children("div").children("input").val();
                itemData.value.push({inputName:keyName,inputVal:keyVal});
            });
        }
        else
            itemData.value = $(item).children(".item-main").children(".item-body").text();
        
        data.GroupList.push(itemData);
    });
    return data;
}

customListGroup.DrawGroup = function() {
    var divId = customListGroup.divId+"-GroupList";
    var html = '<div id="'+divId+'" class="container-fluid"></div>';
    $("#"+customListGroup.divId).append(html);
}

customListGroup.DrawAddButton = function() {
    var divId = customListGroup.divId+"-GroupList";
    var imgBtn = '<a class="btn btn-primary" onClick="customListGroup.NewItem(\'ImageUrl\')"><i class="fa fa-picture-o fa-lg"></i> 添加图片</a>';
    var txtBtn = '<a class="btn btn-primary" onClick="customListGroup.NewItem(\'Textarea\')"><i class="fa fa-pencil fa-lg"></i> 添加文字</a>';
    var inputGroupBtn = '<a class="btn btn-primary" onClick="customListGroup.NewItem(\'InputGroup\')"><i class="fa fa-pencil fa-lg"></i> 添加</a>';
    var btnList = '';
    
    $.each(customListGroup.typeList, function(index, item) {
        if(item === "Textarea")
            btnList += txtBtn+"&nbsp";
        else if(item === "ImageUrl")
            btnList += imgBtn+"&nbsp";
        else if(item === "InputGroup")
            btnList += inputGroupBtn+"&nbsp";
    });
    
    var btnGroup ='<div class="row">'+
                    '<div class="pull-right">'+btnList+'</div>'+
                '</div><div class="row">&nbsp;</div>';
        
    $("#"+divId).append(btnGroup);
}

customListGroup.NewItem = function(itemType,param) {
    var autoNum = ++customListGroup.lastAutoNum,
        params = (param)?param:{
                    autoNum:autoNum,
                    value:"",
                    sort:"",
                    title:"New-"+autoNum,
                    type:itemType,
                    divId:customListGroup.divId+"-Sortable",
                    uploadImgBtnId: 'customListGroup-ItemHeaderBtn-fileupload-'+autoNum,
                    progressBarId: 'customListGroup-ItemHeaderBtn-fileupload-progress-'+autoNum,
                    onFinishCallBack: customListGroup.UploadItemImgOnFinish
                };

    if(itemType === "Textarea")
        customListGroup.DrawTextareaItem(params);
    else if(itemType === "ImageUrl"){
        customListGroup.DrawImageItem(params);
        customListGroup.SetupFileUpload(params);
    }
    else if(itemType === "InputGroup"){
        customListGroup.DrawInputGroupItem(params);
    }
}

customListGroup.DrawItemList = function() {
    var divId = customListGroup.divId+"-GroupList";
    var html =  '<div class="row">'+
                    '<ul class="list-group" id="'+customListGroup.divId+'-Sortable">'+
                    '</ul>'+
                '</div>';
    $("#"+divId).append(html);
    
    $.each(customListGroup.data.GroupList, function(index, item) {
        customListGroup.lastAutoNum = index;
        var autoNum = customListGroup.lastAutoNum,
            params = {
                autoNum:autoNum,
                value:item.value,
                sort:item.sortOrder,
                title:autoNum,
                type:item.type,
                divId:customListGroup.divId+"-Sortable",
                uploadImgBtnId: 'customListGroup-ItemHeaderBtn-fileupload-'+autoNum,
                progressBarId: 'customListGroup-ItemHeaderBtn-fileupload-progress-'+autoNum,
                onFinishCallBack: customListGroup.UploadItemImgOnFinish
            };
        customListGroup.NewItem(item.type,params);
    });
}

customListGroup.DrawTextareaItem = function(params) {
    var html = '<li class="list-group-item" style="padding:0px;">'+
                    '<div class="container-fluid item-main">'+
                        '<div class="row bg-primary item-header">'+
                            '<div class="pull-left" style="padding:5px 10px;">'+params.title+'</div>'+
                            customListGroup.DrawItemHeaderButton(params)+
                        '</div>'+
                        '<div class="row item-body" style="height:65px; display:none;">'+
                            params.value+
                        '</div>'+
                    '</div>'+
                '</li>';
    $("#"+params.divId).append(html);
}

customListGroup.DrawImageItem = function(params) {
    var html = '<li class="list-group-item" style="padding:0px;">'+
                    '<div class="container-fluid item-main">'+
                        '<div class="row bg-primary item-header">'+
                            '<div class="pull-left" style="padding:5px 10px;">'+params.title+'</div>'+
                                customListGroup.DrawItemHeaderButton(params)+                            
                        '</div>'+
                        '<div class="row item-body" style="height:165px; display:none;">'+
                            '<img class="img-rounded  center-block" src="'+params.value+'" height="160">'+
                            '<div class="progress" id="'+params.progressBarId+'" style="display:none;">'+
                                '<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
                                    '<span></span>'+
                                '</div>'+
                            '</div>';
                        '</div>'+
                    '</div>'+
                '</li>';
    $("#"+params.divId).append(html);
    $('#'+params.progressBarId).parent().children('img').aeImageResize({ height:160 , width:250  });
}

customListGroup.DrawInputGroupItem = function(params) {
    if(!params.value) params.value = [{inputName:"编码",inputVal:""},{inputName:"地址",inputVal:""}];
    var formGroupId = customListGroup.divId+"-inputGroup-formGroup-"+params.autoNum;
    var html = '<li class="list-group-item" style="padding:0px;">'+
                    '<div class="container-fluid item-main">'+
                        '<div class="row bg-primary item-header">'+
                            '<div class="pull-left" style="padding:5px 10px;">'+params.title+'</div>'+
                            customListGroup.DrawItemHeaderButton(params)+
                        '</div>'+
                        '<div class="row item-body" style="height:65px; display:none;">'+
                            '<form class="form-horizontal" id="'+formGroupId+'" style="padding:5px;" >'+
                            '</form>'+
                        '</div>'+
                    '</div>'+
                '</li>';
    $("#"+params.divId).append(html);
    $.each(params.value, function(inputIndex,inputInfo){
        console.log("customListGroup.DrawTitle",inputInfo.inputName,inputInfo.inputVal);
        var customTextWidgetParams = {
            divId:formGroupId,
            title:inputInfo.inputName,
            idPerfix: customListGroup.divId+"-inputGroup-item-"+params.autoNum+"-"+inputIndex,
            value:inputInfo.inputVal
        }
        customTextWidget.Draw(customTextWidgetParams);
    });
}

customListGroup.DrawItemHeaderButton = function(params) {
    var showOrhideEvent = 'onClick="customListGroup.ShowOrHideItemOnClick(this)"';
    var delEvent = 'onClick="customListGroup.DelItemOnClick(this)"';
    var editTxtEvent = 'onClick="customListGroup.EditTxtOnClick(this)"';
    var saveEvent = 'onClick="customListGroup.SaveItemOnClick(this)"';
    
    var imgBtn ='<label class="btn btn-primary imgBtn">'+
                    '<span class="fileinput-button" style="padding:0px;">'+
                        '<i class="fa fa-upload"></i>'+
                        '<span/>'+
                        '<input id="'+params.uploadImgBtnId+'" type="file" name="files" accept="image/gif,image/jpeg,image/png" />'+
                    '</span>'+
                '</label>';
                
    var txtBtn ='<label class="btn btn-primary editBtn" '+editTxtEvent+'>'+
                    '<span class="fa fa-edit"></span>'+
                '</label>'+                
                '<label class="btn btn-primary saveBtn" '+saveEvent+'>'+
                    '<span class="fa fa-save"></span>'+
                '</label>';
        
    var delBtn ='<label class="btn btn-primary delBtn" '+delEvent+'>'+
                    '<span class="fa fa-trash-o"></span>'+
                '</label>';
        
    var showBtn ='<label class="btn btn-primary showBtn" '+showOrhideEvent+'>'+
                    '<span class="fa fa-angle-double-left"></span>'+
                '</label>';
        
    var html = '<div class="btn-group pull-right">';//data-toggle="buttons" 
    
    if(params.type === "Textarea")
        html += txtBtn;
    if(params.type === "ImageUrl")
        html += imgBtn;
    if(params.type === "InputGroup")
        html += "";
    
    html += delBtn + showBtn;
    html +='</div>';
    
    return html;
}

customListGroup.SetupFileUpload = function(setupParams) {
    $('#'+setupParams.uploadImgBtnId).fileupload({
        url: '../../ws/UploadFile.php',
        dataType: 'json',
        done: function (e, data) {
            setupParams.onFinishCallBack(data,setupParams);
        },
        progressall: function (e, data) {
            //以下四行为显示进度
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#'+setupParams.progressBarId+' .progress-bar').css('width',progress + '%');
            $('#'+setupParams.progressBarId+' .progress-bar').attr('aria-valuenow',progress);
            $('#'+setupParams.progressBarId+' .progress-bar span').text(progress+'%');
        },
        start: function(e, data) {
            $('#'+setupParams.progressBarId).show();
            $('#'+setupParams.progressBarId).parent().children("img").hide();
            //以下两行为禁用上传按钮
            //$("#"+setupParams.fileUploadGroupId+" .fileinput-button").addClass("disabled");
            //$("#"+setupParams.fileUploadId).attr("disabled","disabled");
            //以下三行为重置进度条长度
            $('#'+setupParams.progressBarId+' .progress-bar').css('width','0%');
            $('#'+setupParams.progressBarId+' .progress-bar').attr('aria-valuenow','0');
            $('#'+setupParams.progressBarId+' .progress-bar span').text('0%');
        }
    });
}

customListGroup.UploadItemImgOnFinish = function(data,setupParams) {
    //加载上传成功的图片
    var elementObj = $('#'+setupParams.progressBarId).parent().children("img");
    $.each(data.result.files, function (index, file) {
        var fileSize = file.size;
        elementObj.attr("src",file.url);
        elementObj.aeImageResize({ height: 165, width: 250 });
        //$('#bootstrap-dialog-footer-buttons > button').removeAttr("disabled");
        //$('#bootstrap-dialog-footer-buttons > button').removeAttr("disabled");
    });
    //以下两行为启用上传按钮
    //$("#"+setupParams.fileUploadGroupId+" .fileinput-button").removeClass("disabled");
    //$("#"+setupParams.fileUploadId).removeAttr("disabled","disabled");
    //隐藏进度条
    $('#'+setupParams.progressBarId).hide();
    //显示图片
    elementObj.show();    
    //改变header ico
    var itemBodyObj = $('#'+setupParams.uploadImgBtnId).parent().parent().parent().parent().parent().children(".item-body");
    if( $(itemBodyObj).is(":hidden") ) {
        $(itemBodyObj).slideDown("slow");
        $('#'+setupParams.uploadImgBtnId).parent().parent().parent().children(".showBtn").children("span").attr("class","fa fa-angle-double-down");
    }
}

customListGroup.DrawUploadImgProgressBar = function(params) {
    if( customListGroup.debug ) console.log("DrawUploadImgProgressBar",params);
    if(params){
        var progressBarId = params.progressBarId,
            parentId = params.parentId,
            content='<div class="progress" id="'+progressBarId+'">'+
                        '<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
                            '<span></span>'+
                        '</div>'+
                    '</div>';
        $("#"+parentId).append(content);
    }
}

customListGroup.SaveItemOnClick = function(clickObj) {
    var itemBodyObj = $(clickObj).parent().parent().parent().children(".item-body");
    if( $(itemBodyObj).children().is("textarea") ) {
        var value = $(itemBodyObj).children("textarea").val();
        $(itemBodyObj).empty();
        $(itemBodyObj).append(value);
    }
}

customListGroup.EditTxtOnClick = function(clickObj) {
    var itemBodyObj = $(clickObj).parent().parent().parent().children(".item-body");
    if( $(itemBodyObj).is(":hidden") ) {
        $(itemBodyObj).slideDown("slow");
        $(clickObj).parent().children(".showBtn").children("span").attr("class","fa fa-angle-double-down");
    }
    
    if( !$(itemBodyObj).children().is("textarea") ) {
        var value = $(itemBodyObj).html();
        var textareHtml = '<textarea rows="3" class="form-control" placeholder="请输入文字内容，不超过50个字符">'+value+'</textarea>';
        $(itemBodyObj).empty();
        $(itemBodyObj).append(textareHtml);
    }
}

customListGroup.DelItemOnClick = function(clickObj) {
    $(clickObj).parent().parent().parent().parent().remove();    
}

customListGroup.ShowOrHideItemOnClick = function(clickObj) {
    var itemBodyObj = $(clickObj).parent().parent().parent().children(".item-body");
    if( $(itemBodyObj).is(":hidden") ) {
        $(itemBodyObj).slideDown("slow");
        $(clickObj).children("span").attr("class","fa fa-angle-double-down");
    }
    else{
        $(itemBodyObj).slideUp("slow");
        $(clickObj).children("span").attr("class","fa fa-angle-double-left");
    }    
}

customListGroup.SetSortable = function() {
    $( "#"+customListGroup.divId+"-Sortable" ).sortable();
    $( "#"+customListGroup.divId+"-Sortable" ).disableSelection();
}
