/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var CustomAlbum = {
    debug:true,
    isSort: false,
    gridEffect:"3",
    albumTypeData:[],
    data:[],
    divId:"",
    goupId:"",
    fileUploadId:"",
    btnGroupId:"",
    sortBtnId:"",
    selectAlbumTypeBtnId:"",
    uploadBtnId:"",
    progressBarId:"",
    albumListId:"",
    customGridViewParams:null,
    customGridViewObj:null,
    uploadWS:"",
    title:"",
    helpText:"",
    name:""
};

CustomAlbum.Draw = function(params) {
    CustomAlbum.SetConfig(params);
    CustomAlbum.DrawContainerGroup();
    CustomAlbum.DrawFileUploadGroup();
    CustomAlbum.DrawBtnGroup();
    CustomAlbum.DrawSelectAlbumTypeBtn();
    CustomAlbum.SetupSelectAlbumTypeBtn();
    CustomAlbum.DrawSortBtn(true);
    CustomAlbum.DrawAlbumListGroup();
    CustomAlbum.DrawFileUploadBtn();
    CustomAlbum.SetupFileUpload();
    CustomAlbum.DrawAlbumList();
};

CustomAlbum.Refresh = function() {
    CustomAlbum.SetCustomGridViewParams();
    CustomAlbum.customGridViewObj.Refresh(CustomAlbum.customGridViewParams);
    $("#"+CustomAlbum.uploadBtnId+"-input").val(JSON.stringify(CustomAlbum.data))
};

CustomAlbum.SetConfig = function(params) {
    CustomAlbum.divId = params.divId;
    CustomAlbum.goupId = params.divId+'-containerGroup';
    
    CustomAlbum.fileUploadId = params.divId+'-fileuploadGroup';
    CustomAlbum.uploadBtnId = CustomAlbum.fileUploadId+'-uploadBtn';
    CustomAlbum.progressBarId = CustomAlbum.fileUploadId+'-progressBarGroup';
    
    CustomAlbum.btnGroupId = params.divId+'-btnGroup';
    CustomAlbum.sortBtnId = CustomAlbum.btnGroupId+'-sortAlbumBtn';
    CustomAlbum.selectAlbumTypeBtnId = CustomAlbum.btnGroupId+'-selectAlbumTypeBtn';
    
    CustomAlbum.albumListId = params.divId+'-albumListGroup';
    CustomAlbum.data = params.data;
    CustomAlbum.albumTypeData = params.albumTypeData;

    CustomAlbum.title = params.title;
    CustomAlbum.helpText = params.helpText;
    CustomAlbum.uploadWS = params.uploadWS;
    CustomAlbum.name = params.name;
};

CustomAlbum.DrawContainerGroup = function() {
    $("#"+CustomAlbum.divId).empty();
    var html =  '<label class="control-label" for="EditCulture-album-textWidget-group">'+CustomAlbum.title+'</label>'+
                '<div id="'+CustomAlbum.goupId+'" class="container-fluid">'+
                    '<div class="row">'+
                        "&nbsp;"+
                    '</div>'+
                '</div>';
    $("#"+CustomAlbum.divId).append(html);
};

CustomAlbum.DrawFileUploadGroup = function() {
    var div = $('<div class="row" id="'+CustomAlbum.fileUploadId+'">');
    $("#"+CustomAlbum.goupId).append(div);
};

CustomAlbum.DrawBtnGroup = function() {
    var div = $('<div class="row" id="'+CustomAlbum.btnGroupId+'">');  
    $("#"+CustomAlbum.goupId).append(div);
};

CustomAlbum.DrawSortBtn = function(isSort) {
    if(CustomAlbum.albumTypeData && CustomAlbum.albumTypeData.length > 0){
        $("#"+CustomAlbum.sortBtnId).remove();
        var sortBtn = "";
        if(isSort)
            sortBtn = $('<div class="pull-left"><a id="'+CustomAlbum.sortBtnId+'" onclick="CustomAlbum.SortBtnOnClick(true)" class="btn btn-primary"><i class="fa fa-sort fa-lg"></i> 排序</div>');
        else
            sortBtn = $('<div class="pull-left"><a id="'+CustomAlbum.sortBtnId+'" onclick="CustomAlbum.SortBtnOnClick(false)" class="btn btn-primary"><i class="fa fa-sort fa-lg"></i> 关闭排序</div>');
        $("#"+CustomAlbum.btnGroupId).append(sortBtn);
    }
};

CustomAlbum.DrawSelectAlbumTypeBtn = function() {
    if(CustomAlbum.albumTypeData && CustomAlbum.albumTypeData.length > 0){
        var selectBtnGroup = $('<div class="pull-left">');
        var selectBtn = $('<select class="form-control" id="'+CustomAlbum.selectAlbumTypeBtnId+'">');
        $(selectBtnGroup).append(selectBtn);    
        $("#"+CustomAlbum.btnGroupId).append(selectBtnGroup);
    }
};

CustomAlbum.SetupSelectAlbumTypeBtn = function() {
    if(CustomAlbum.albumTypeData && CustomAlbum.albumTypeData.length > 0){
        var multiselectObj = $('#'+CustomAlbum.selectAlbumTypeBtnId);
        multiselectObj.empty();
        $.each(CustomAlbum.albumTypeData, function(index, item){
            var optinHtml = '<option value="'+item.Id+'">'+item.Name+'</option>';
            multiselectObj.append(optinHtml);
        });

        multiselectObj.multiselect({
            buttonClass: 'btn btn-primary',
            selectedClass: '',
            maxHeight: 300,
            onChange: function(option, checked) {
                CustomAlbum.SetCustomGridViewParams();
                CustomAlbum.Refresh();
            }
        });

        multiselectObj.multiselect('rebuild');
    }
};

CustomAlbum.DrawAlbumListGroup = function() {
    var div = $('<div class="row" id="'+CustomAlbum.albumListId+'">');
    $("#"+CustomAlbum.goupId).append(div);
};

CustomAlbum.DrawFileUploadBtn = function() {
    var title = "选择本地图片", 
        helpText = CustomAlbum.helpText,//照片尺寸不少于300x500，每张图片大小不超过1M
        multiple = "multiple",
        name = CustomAlbum.name,
        content = 
        '<span class="btn btn-primary fileinput-button">'+
            '<i class="glyphicon glyphicon-plus"></i>'+
            '<span>'+title+'</span>'+
            '<input id="'+CustomAlbum.uploadBtnId+'" type="file" name="files[]" '+multiple+' accept="image/gif,image/jpeg,image/png"/>'+
            '<input id="'+CustomAlbum.uploadBtnId+'-input" name="'+name+'" value=\''+JSON.stringify(CustomAlbum.data)+'\' hidden />'+//'+CustomAlbum.data+'
        '</span>'+
        '<p class="help-block">'+helpText+'</p>'+
        '<div class="progress" style="height:20px;" id="'+CustomAlbum.progressBarId+'">'+
            '<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
                '<span></span>'+
            '</div>'+
        '</div>';
    $("#"+CustomAlbum.fileUploadId).append(content);
};

CustomAlbum.SetupFileUpload = function() {    
    $('#'+CustomAlbum.uploadBtnId).fileupload({
        url: CustomAlbum.uploadWS,
        dataType: 'json',
        done: function (e, data) {
            CustomAlbum.UploadAlbumImageOnFinish(data);
        },
        progressall: function (e, data) {
            //以下四行为显示进度
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#'+CustomAlbum.progressBarId+' .progress-bar').css('width',progress + '%');
            $('#'+CustomAlbum.progressBarId+' .progress-bar').attr('aria-valuenow',progress);
            $('#'+CustomAlbum.progressBarId+' .progress-bar span').text(progress+'%');
        },
        start: function(e, data) {
            //以下两行为禁用上传按钮
            $("#"+CustomAlbum.fileUploadId+" .fileinput-button").addClass("disabled");
            $("#"+CustomAlbum.fileUploadId).attr("disabled","disabled");
            //以下三行为重置进度条长度
            $('#'+CustomAlbum.progressBarId+' .progress-bar').css('width','0%');
            $('#'+CustomAlbum.progressBarId+' .progress-bar').attr('aria-valuenow','0');
            $('#'+CustomAlbum.progressBarId+' .progress-bar span').text('0%');
        }
    });
};

CustomAlbum.DrawAlbumList = function() {
    CustomAlbum.SetCustomGridViewParams();
    CustomAlbum.customGridViewObj = customGridView.Create();
    CustomAlbum.customGridViewObj.Draw(CustomAlbum.customGridViewParams);
};

CustomAlbum.SetCustomGridViewParams = function() {
    var gridId = (CustomAlbum.customGridViewObj)?CustomAlbum.customGridViewObj.configSetting.gridId:"";
    CustomAlbum.customGridViewParams = {
        divId:CustomAlbum.albumListId,
        gridId:gridId,
        gridEffect:(CustomAlbum.isSort)?false:CustomAlbum.gridEffect,
        dataCount:0,
        gridData:[],
        page:{
            pagination:false,
            pSizeOption:[12,24,60,120],
            pIndex:1,
            pSize:999
        },
        sort:{
            sortable:CustomAlbum.isSort,
            onStop:CustomAlbum.SortOnStop
        }
    };
    
    if(CustomAlbum.data.length > 0) {
        CustomAlbum.customGridViewParams.dataCount = CustomAlbum.data.length;
        var albumTypeId = (CustomAlbum.albumTypeData && CustomAlbum.albumTypeData.length > 0)?$('#'+CustomAlbum.selectAlbumTypeBtnId).val():"";
        $.each(CustomAlbum.data,function(itemIndex,item) {
            if( albumTypeId === "" || item.AlbumTypeId == albumTypeId ){
                var gridItem = {id:itemIndex, imageUrl:item.ImageUrl, title:""};
                if(!CustomAlbum.isSort){
                    gridItem.mask = {btn:[{icon:"glyphicon glyphicon-trash", name:"删除", onClick:"CustomAlbum.RemoveAlbumOnClick"}]};
                }
                CustomAlbum.customGridViewParams.gridData.push(gridItem);
            }
        });
    }
};

/* 
 * Event
 */
CustomAlbum.UploadAlbumImageOnFinish = function(data) {
    var albumData = CustomAlbum.data;
    $.each(data.result.files, function (index, file) {
        var albumTypeId = (CustomAlbum.albumTypeData && CustomAlbum.albumTypeData.length > 0)?$('#'+CustomAlbum.selectAlbumTypeBtnId).val():"";
        var data = {
            ImageListId:"",
            ImageUrl:file.url,
            ThumbImageUrl:file.thumbnailUrl,
            Description:"",
            StickyPost:""
        };
        albumData.push(data);
    });
    CustomAlbum.data = albumData;
    //以下两行为启用上传按钮
    $("#"+CustomAlbum.fileUploadId+" .fileinput-button").removeClass("disabled");
    $("#"+CustomAlbum.fileUploadId).removeAttr("disabled");
    if( CustomAlbum.debug ) console.log( "UploadAlbumImageOnFinish:", CustomAlbum.data );
    CustomAlbum.Refresh();
};

CustomAlbum.RemoveAlbumOnClick = function(itemIndexId) {
    var albumData = CustomAlbum.data;
    if(albumData.length > 0 && itemIndexId > -1){
        albumData.splice(itemIndexId,1);
    }
    CustomAlbum.data = albumData;
    CustomAlbum.Refresh();
};

CustomAlbum.SortBtnOnClick = function(isSort) {
    if(isSort){
        CustomAlbum.DrawSortBtn(false);
        $("#"+CustomAlbum.fileUploadId).slideUp('slow');
    }
    else{
        CustomAlbum.DrawSortBtn(true);
        $("#"+CustomAlbum.fileUploadId).slideDown('slow');
    }
    CustomAlbum.isSort = isSort;
    CustomAlbum.SetCustomGridViewParams();
    CustomAlbum.customGridViewObj.Refresh(CustomAlbum.customGridViewParams);
};

CustomAlbum.SortOnStop = function() {
    var sortList = CustomAlbum.customGridViewObj.GetGridItemSort();
    $.each(sortList, function(index,sortNum) {
        CustomAlbum.data[sortNum].SortOrder = index;
    });
    console.log(CustomAlbum.data);
};