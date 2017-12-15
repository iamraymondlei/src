/*==========================
 * Nvp Start
 ===========================*/
var customNvpWidget = {
    debug:false,
    divId:"drawNvpUI-form-group",
    idPerfix: "drawNvpUI",
    imageWidth: 250,
    imageHeight: 300,
    nvpTypeList:[],//{Id:"2",DataFormat:"TEXT",DisplayName:"尺寸",TypeId:"11", readonly:true, TypeName:"Size",ProductCdxmlUrl:"",Value:{autoUpdate:0,unit:"",value:""}}
    imageTagSetting:{},//{imageTag:productDetail.saveData.ImageTag,imageUrl:productDetail.saveData.ImageUrl,imageW:productDetail.saveData.ImageW,imageH:productDetail.saveData.ImageH,goupId:"productImageTag-form-group",divId:"productImageTag-album-image"}
    polygonDrawingSetting:{}//{coordinate:"",groupId:"",pdObj:null}
};

customNvpWidget.DrawNvp = function(params) {
    customNvpWidget.divId = params.divId;
    customNvpWidget.idPerfix = params.idPerfix;
    customNvpWidget.imageWidth = params.imageWidth;
    customNvpWidget.imageHeight = params.imageHeight;
    customNvpWidget.nvpTypeList = params.nvpTypeList;
    customNvpWidget.imageTagSetting = params.imageTagSetting;
    customNvpWidget.polygonDrawingSetting = params.polygonDrawingSetting;
    
    if(customNvpWidget.debug) console.log("ItemNvpType:",customNvpWidget.nvpTypeList);
    $.each(customNvpWidget.nvpTypeList, function(nvpIndex,nvp){
        var format = nvp.DataFormat;
        if(!nvp.Value) nvp.Value = {value:'',unit:'',autoUpdate:0};
        switch(format){
            case "TEXT":        (nvp.TypeName == "LinkUrl")?customNvpWidget.DrawSelProductNvp(nvp):customNvpWidget.DrawTextNvp(nvp);     break;
            case "LONGTEXT":    customNvpWidget.DrawLongTextNvp(nvp); break;
            case "DATE":        customNvpWidget.DrawDateNvp(nvp);     break;
            case "BOOLEAN":     customNvpWidget.DrawBooleanNvp(nvp);  break;
            case "JSON":        customNvpWidget.DrawJSONNvp(nvp);     break;
            case "PRICE":       customNvpWidget.DrawPriceNvp(nvp);    break;
            case "IMAGE_URL":   customNvpWidget.DrawImageNvp(nvp);    break;
            default:
        }
    });
    if(customNvpWidget.debug) console.log("customNvpWidget.DrawItemNvp nvpTypeList:",customNvpWidget.nvpTypeList);
};

customNvpWidget.DrawTextNvp = function(nvp) {
    var idPerfix = customNvpWidget.idPerfix;
    var name = nvp.TypeName;
    var value = nvp.Value.value;
    var params = {
        title: nvp.DisplayName,
        groupId: idPerfix+'-'+name+'-group',
        inputId: idPerfix+'-'+name,
        placeholder: '不超過20個字符',
        readonly: nvp.Readonly,
        value: value
    };    
    customNvpWidget.DrawInputGroup(params);
};

customNvpWidget.DrawSelProductNvp = function(nvp) {
    var idPerfix = customNvpWidget.idPerfix;
    var name = nvp.TypeName;
    var value = nvp.Value.value;
    var params = {
        nvpId: nvp.Id,
        title: nvp.DisplayName,
        groupId: idPerfix+'-'+name+'-group',
        inputId: idPerfix+'-'+name,
        placeholder: '不超過20個字符',
        value: value
    };    
    customNvpWidget.DrawSelProductInputGroup(params);
}

customNvpWidget.DrawLongTextNvp = function(nvp) {
    var idPerfix = customNvpWidget.idPerfix;
    var name = nvp.TypeName;
    var value = nvp.Value.value;
    var params = {
        title: nvp.DisplayName,
        groupId: idPerfix+'-'+name+'-group',
        textareaId: idPerfix+'-'+name,
        placeholder: '请输入文字内容，不超过50个字符',
        value: value
    };    
    customNvpWidget.DrawTextareaGroup(params);
};

customNvpWidget.DrawDateNvp = function(nvp) {
    
};

customNvpWidget.DrawBooleanNvp = function(nvp) {
    var idPerfix = customNvpWidget.idPerfix;
    var name = nvp.TypeName;
    var value = nvp.Value.value;
    var params = {
        title: nvp.DisplayName,
        groupId: idPerfix+'-'+name+'-group',
        selectId: idPerfix+'-'+name,
        value: value
    };    
    customNvpWidget.DrawSelectGroup(params);
};

customNvpWidget.DrawJSONNvp = function(nvp) {
    if( customNvpWidget.debug ) console.log("DrawJSONNvp",nvp);
    var name = nvp.TypeName;
    switch(name){
        case "IntroList":
            customNvpWidget.DrawIntroListNvp(nvp);
            break;
        case "DetailArticle":
            customNvpWidget.DrawDetailArticleNvp(nvp);
            break;
        case "ImageTag":
            customNvpWidget.DrawImageTagNvp(nvp);
            break;
        case "WebFloorPlan":
            customNvpWidget.DrawPolygonDrawing(nvp);
            break;
        case "OrderLink":
            customNvpWidget.DrawOrderLinkNvp(nvp);
            break;
        default:
    }
};

customNvpWidget.DrawDetailArticleNvp = function(nvp) {
    if( customNvpWidget.debug ) console.log("DrawDetailArticleNvp",nvp);
    
    if(nvp && customListGroup){
        var id = nvp.Id,
            title = nvp.DisplayName, 
            idPerfix = customNvpWidget.idPerfix,
            groupId = idPerfix+'-'+nvp.TypeName+'-group';

        var content = '<div class="col-sm-10">'+
                        '<a onclick="customNvpWidget.SetupDetailArticleDialog(\''+id+'\')" class="btn btn-primary">'+
                        '<i class="fa fa-plus-circle fa-lg"></i> 编缉'+
                       '</a>'+
                       '</div>';
        customNvpWidget.DrawFormGroup(title,content,groupId);
    }
};

customNvpWidget.DrawOrderLinkNvp = function(nvp) {
    if( customNvpWidget.debug ) console.log("DrawOrderLinkNvp",nvp);
    
    if(nvp && customListGroup){
        var id = nvp.Id,
            title = nvp.DisplayName, 
            idPerfix = customNvpWidget.idPerfix,
            groupId = idPerfix+'-'+nvp.TypeName+'-group';

        var content = '<div class="col-sm-10">'+
                        '<a onclick="customNvpWidget.SetupOrderLinkDialog(\''+id+'\')" class="btn btn-primary">'+
                        '<i class="fa fa-plus-circle fa-lg"></i> 编缉'+
                       '</a>'+
                       '</div>';
        customNvpWidget.DrawFormGroup(title,content,groupId);
    }
};

customNvpWidget.DrawIntroListNvp = function(nvp) {
    
};

customNvpWidget.DrawImageTagNvp = function(nvp) {
    if( customNvpWidget.debug ) console.log("DrawImageTagNvp",nvp);
    if( customNvpWidget.debug ) console.log("ImageTag Upload",customNvpWidget.imageTagSetting.imageTag);
    
    $('li[name="productImageTag"]').show();
    var data = {imgUrl:"",imgW:"",imgH:"",ImageTag:[]};
        data.ImageTag = (customNvpWidget.imageTagSetting.imageTag)?customNvpWidget.imageTagSetting.imageTag.value.Tag:[];
        data.imgUrl = (customNvpWidget.imageTagSetting.imageUrl)?customNvpWidget.imageTagSetting.imageUrl:"";
        data.imgW = customNvpWidget.imageTagSetting.imageW;
        data.imgH = customNvpWidget.imageTagSetting.imageH;
    var setting = {
        goupId:customNvpWidget.imageTagSetting.goupId,
        divId: customNvpWidget.imageTagSetting.divId,
        pageName: customNvpWidget.idPerfix
    };
    if(customNvpWidget.imageTagSetting.imageUrl){
        productImageTag.GetData(data,setting);
        productImageTag.Draw();
    }
};

customNvpWidget.DrawPolygonDrawing = function(nvp) {
    if( customNvpWidget.debug ) console.log("DrawPolygonDrawing",nvp);
    $('li[name="polygonDrawingTag"]').show();
    var data = {webCoordinate:""};
        data.webCoordinate = (customNvpWidget.polygonDrawingSetting.coordinate)?customNvpWidget.polygonDrawingSetting.coordinate:"";
    var setting = {
        groupId:customNvpWidget.polygonDrawingSetting.groupId,
        name:customNvpWidget.polygonDrawingSetting.name,
        pageName: customNvpWidget.idPerfix
    };
    customNvpWidget.polygonDrawingSetting.pdObj = polygonDrawing.Create();
    customNvpWidget.polygonDrawingSetting.pdObj.Init(data,setting);
};

customNvpWidget.DrawPriceNvp = function(nvp) {
    var idPerfix = customNvpWidget.idPerfix;
    var name = nvp.TypeName;
    var value = nvp.Value.value;
    var params = {
        title: nvp.DisplayName,
        groupId: idPerfix+'-'+name+'-group',
        inputId: idPerfix+'-'+name,
        placeholder: '请输入价格',
        value: value.replace(/￥/g,"")
    };    
    customNvpWidget.DrawInputGroup(params);
};

customNvpWidget.DrawImageNvp = function(nvp) {
    var idPerfix = customNvpWidget.idPerfix;
    var name = nvp.TypeName;
    var value = nvp.Value.value;
    var params = {
        title: nvp.DisplayName,
        groupId: idPerfix+'-'+name+'-group',
        inputId: idPerfix+'-'+name,
        placeholder: '',
        value: value
    };    
    customNvpWidget.DrawImageGroup(params);
};

customNvpWidget.DrawInputGroup = function(params) {
    if( customNvpWidget.debug ) console.log("DrawInputGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            placeholder = params.placeholder,
            readonly = (params.readonly)?"readonly":"",
            value = params.value;
        var content = '<div class="col-sm-10">'+
                      '<input type="text" class="form-control pull-left" id="'+inputId+'" placeholder="'+placeholder+'" '+readonly+' value="'+value+'">'+
                      '</div>';
        customNvpWidget.DrawFormGroup(title,content,groupId);
    }
};

customNvpWidget.DrawSelProductInputGroup = function(params) {
    if( customNvpWidget.debug ) console.log("DrawInputGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            placeholder = params.placeholder,
            value = params.value;
        var content = '<div class="col-sm-10">'+
                      '<input type="text" style="width:80%" disabled class="form-control pull-left" id="'+inputId+'" placeholder="'+placeholder+'" value="'+value+'">'+
                      '<button type="button" style="width:10%" onclick="customNvpWidget.SetupSelProductDialog(\''+params.nvpId+'\')" class="btn btn-default pull-left">选择</button>'+
                      '<button type="button" style="width:10%" onclick="customNvpWidget.DelSelProduct(\''+inputId+'\')" class="btn btn-default pull-left">删除</button>'+
                      '</div>';
        customNvpWidget.DrawFormGroup(title,content,groupId);
    }
};

customNvpWidget.DrawTextareaGroup = function(params) {
    if( customNvpWidget.debug ) console.log("DrawTextareaGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            textareaId = params.textareaId,
            placeholder = params.placeholder,
            value = params.value;
        var content = '<div class="col-sm-10">'+
                        '<textarea class="form-control" id="'+textareaId+'" rows="3" placeholder="'+placeholder+'">'+value+'</textarea>'+
                      '</div>';
        customNvpWidget.DrawFormGroup(title,content,groupId);
    }
};

customNvpWidget.DrawSelectGroup = function(params) {
    if( customNvpWidget.debug ) console.log("DrawSelectGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            selectId = params.selectId,
            value = parseInt(params.value);
        var content = '<div class="col-sm-10">'+
                        '<select class="form-control" id="'+selectId+'">';
            if(value == "1")
                content+='<option value="1">是</option>'+'<option value="0">否</option>';
            else
                content+='<option value="0">否</option>'+'<option value="1">是</option>';
            
            content+=   '</select>'+
                      '</div>';
        customNvpWidget.DrawFormGroup(title,content,groupId);
    }
};

customNvpWidget.DrawImageGroup = function(params) {
    if( customNvpWidget.debug ) console.log("DrawImageGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            fileUploadGroupId = inputId+"-fileupload-group",
            value = (params.value == null)?"":params.value,
            removeBtn = '<div class="well-sm grid-item-mask-btn-1" title="删除" onclick="customNvpWidget.RemoveImage(\''+inputId+'\')">'+
                            '<span class="glyphicon glyphicon-trash"></span>'+
                        '</div>',
            content = '<div class="col-sm-10 img-group">'+
                        '<div class="media">'+
                            '<div class="media-left" style="vertical-align:middle;position:relative;" onmouseover="customNvpWidget.SetImgBtnVisibility(\'show\',this)" onmouseout="customNvpWidget.SetImgBtnVisibility(\'hide\',this)">'+
                                '<div class="grid-item-mask">'+ removeBtn + '</div>'+
                                '<img class="img-rounded" id="'+inputId+'" src="'+value+'" width="250" />'+
                            '</div>'+
                            '<div class="media-body" id="'+fileUploadGroupId+'">'+
                            '</div>'+
                        '</div>'+
                      '</div>';                
        customNvpWidget.DrawFormGroup(title,content,groupId);

        var fileUploadUISetting = {
            title:"选择本地图片", 
            fileUploadId:inputId+"-fileupload",
            progressBarId:inputId+"-progress",
            helpText:"照片尺寸不少于300x500，每张图片大小不超过1M",
            parentId:fileUploadGroupId,
            isMultiple:false
        }
        customNvpWidget.DrawFileUploadBtn(fileUploadUISetting);

        var setupFileUploadParams  = {
            fileUploadGroupId : fileUploadGroupId,
            fileUploadId : inputId+"-fileupload",
            progressBarId : inputId+"-progress",
            imgId: inputId,
            onFinishCallBack: customNvpWidget.ImageNvpUploadOnFinish
        }
        customNvpWidget.SetupFileUpload(setupFileUploadParams);
    }
};

customNvpWidget.ImageNvpUploadOnFinish = function(data,setupParams) {
    //加载上传成功的图片
    $.each(data.result.files, function (index, file) {
        $('#'+setupParams.imgId).attr("src",file.url);
        $("#"+setupParams.imgId).aeImageResize({ height: customNvpWidget.imageWidth, width: customNvpWidget.imageHeight });
    });
    //以下两行为启用上传按钮
    $("#"+setupParams.fileUploadGroupId+" .fileinput-button").removeClass("disabled");
    $("#"+setupParams.fileUploadId).removeAttr("disabled","disabled");
};

customNvpWidget.SetImgBtnVisibility = function(action,elementObj) {
    var maskHight = $(elementObj).children("img").height(),
        maskWidth = $(elementObj).children("img").width();
    if(action === "show"){
        $(elementObj).children(".grid-item-mask").css("width",maskWidth);
        $(elementObj).children(".grid-item-mask").css("height",maskHight);
        $(elementObj).children(".grid-item-mask").show();
    }
    else if(action === "hide")
        $(elementObj).children(".grid-item-mask").hide();
};

customNvpWidget.RemoveImage = function(elementId) {
    $("#"+elementId).attr("src","");
};

customNvpWidget.DrawFileUploadBtn = function(params) {
    if( customNvpWidget.debug ) console.log("DrawFileUploadBtnGroup",params);
    if(params){
        var title = params.title, 
            fileUploadId = params.fileUploadId,
            progressBarId = params.progressBarId,
            helpText = params.helpText,
            parentId = params.parentId,
            multiple = params.isMultiple?"multiple":"",
            name = params.isMultiple?"files[]":"files",
            content = 
            '<span class="btn btn-primary fileinput-button">'+
                '<i class="glyphicon glyphicon-plus"></i>'+
                '<span>'+title+'</span>'+
                '<input id="'+fileUploadId+'" type="file" name="'+name+'" '+multiple+' accept="image/gif,image/jpeg,image/png"/>'+
            '</span>'+
            '<p class="help-block">'+helpText+'</p>'+
            '<div class="progress" id="'+progressBarId+'">'+
                '<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+
                    '<span></span>'+
                '</div>'+
            '</div>';
        $("#"+parentId).append(content);
    }
};

customNvpWidget.SetupFileUpload = function(setupParams) {    
    $('#'+setupParams.fileUploadId).fileupload({
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
            //以下两行为禁用上传按钮
            $("#"+setupParams.fileUploadGroupId+" .fileinput-button").addClass("disabled");
            $("#"+setupParams.fileUploadId).attr("disabled","disabled");
            //以下三行为重置进度条长度
            $('#'+setupParams.progressBarId+' .progress-bar').css('width','0%');
            $('#'+setupParams.progressBarId+' .progress-bar').attr('aria-valuenow','0');
            $('#'+setupParams.progressBarId+' .progress-bar span').text('0%');
        }
    });
};

customNvpWidget.SetupDetailArticleDialog = function(nvpId) {
    if( nvpId ){
        var idPerfix = customNvpWidget.idPerfix,
            data = customNvpWidget.nvpTypeList[nvpId],
            typeName = data.TypeName,
            title = data.DisplayName,
            divId = idPerfix+'-'+typeName+'-dialog';
    
        BootstrapDialog.show({
            title: title,
            message: '<div id="'+divId+'"></div>',
            draggable: true,
            data:{},
            onshown: function() { 
                var params = {
                    data:{GroupList:[]},
                    typeList:["Textarea","ImageUrl"],
                    divId:divId
                }                
                $.each(data.Value.value.Paragraph, function(index, item) {
                    var itemData = {value:item.DetailContent,type:item.DetailContentTypeName,sortOrder:item.SortOrder};
                    params.data.GroupList.push(itemData);
                });
                customListGroup.Init(params);
            },
            buttons: [{
                label: '确定',
                cssClass: 'btn-primary',
                hotkey: '', // Enter.
                action: function(dialog) {
                    var customListGroupData = customListGroup.GetSaveData();
                    var detailArticleData = {Paragraph:[]};
                    $.each(customListGroupData.GroupList, function(index, item) {
                        var itemData = {DetailContent:item.value,DetailContentTypeName:item.type,SortOrder:item.sortOrder};
                        detailArticleData.Paragraph.push(itemData);
                    });
                    
                    customNvpWidget.nvpTypeList[nvpId].Value.value = detailArticleData;
                    
                    if( customNvpWidget.debug ) console.log("DetailArticle Save Data",customNvpWidget.nvpTypeList[nvpId]);
                    dialog.close();
                }
            },
            {
                label: '取消',
                cssClass: 'btn-primary',
                hotkey: 27, // ESC.
                action: function(dialog) { dialog.close(); }
            }]
        }); 
    }
};

customNvpWidget.SetupOrderLinkDialog = function(nvpId) {
    if( nvpId ){
        var idPerfix = customNvpWidget.idPerfix,
            data = customNvpWidget.nvpTypeList[nvpId],
            typeName = data.TypeName,
            title = data.DisplayName,
            divId = idPerfix+'-'+typeName+'-dialog';
    
        BootstrapDialog.show({
            title: title,
            message: '<div id="'+divId+'"></div>',
            draggable: true,
            data:{},
            onshown: function() { 
                var params = {
                    data:{GroupList:[]},
                    typeList:["InputGroup"],
                    divId:divId
                }
                $.each(data.Value.value.Link, function(index, item) {
                    var itemData = {value:[{inputName:"编码",inputVal:item.Id},{inputName:"地址",inputVal:item.Url}],type:"InputGroup",sortOrder:item.SortOrder};
                    params.data.GroupList.push(itemData);
                });
                customListGroup.Init(params);
            },
            buttons: [{
                label: '确定',
                cssClass: 'btn-primary',
                hotkey: '', // Enter.
                action: function(dialog) {
                    var customListGroupData = customListGroup.GetSaveData();
                    var orderLinkData = {Link:[]};
                    $.each(customListGroupData.GroupList, function(index, item) {
                        var itemData = {Id:"",Url:""};
                        $.each(item.value, function(iinputIndex,inputItem){
                            if( inputItem.inputName === "编码" ) 
                                itemData.Id = inputItem.inputVal;
                            else if( inputItem.inputName === "地址" ) 
                                itemData.Url = inputItem.inputVal;
                        });
                        orderLinkData.Link.push(itemData);
                    });
                    
                    customNvpWidget.nvpTypeList[nvpId].Value.value = orderLinkData;
                    
                    if( customNvpWidget.debug ) console.log("OrderLink Save Data",customNvpWidget.nvpTypeList[nvpId]);
                    dialog.close();
                }
            },
            {
                label: '取消',
                cssClass: 'btn-primary',
                hotkey: 27, // ESC.
                action: function(dialog) { dialog.close(); }
            }]
        }); 
    }
};

customNvpWidget.SetupSelProductDialog = function(nvpId) {
    var itemId = "",
        shopId = $.urlParams("get", "shopId"),
        mallId = $.urlParams("get", "mallId"),
        callback = customNvpWidget.SelectProductItemOnFinish,
        pageName = 'productDialog',
        params = {itemId:itemId,shopId:shopId,mallId:mallId,pageName:pageName,onFinishCallBack:callback,customData:{nvpId:nvpId}};
    productDialog.OpenDialog(params);
};

customNvpWidget.SelectProductItemOnFinish = function(itemData,customData) {
    if( customNvpWidget.debug ) console.log(itemData);
    if(itemData){
        var itemId = itemData.ItemId,
            avatarId = (itemData.AvatarId)?itemData.AvatarId:"",
            webXMLURL = (itemData.WebXMLURL)?itemData.WebXMLURL:"",
            nvpData = customNvpWidget.nvpTypeList[customData.nvpId],
            idPerfix = customNvpWidget.idPerfix,
            productCdxmlUrl = nvpData.ProductCdxmlUrl,
            name = nvpData.TypeName;
        if(nvpData.TypeName == "LinkUrl" && productCdxmlUrl && avatarId){
            webXMLURL = productCdxmlUrl.replace(/varProductId=/g,"varProductId="+itemId).replace(/varShowObjId=/g,"varShowObjId="+avatarId);
        }
    
        $("#"+idPerfix+'-ItemId').val(itemId);
        $("#"+idPerfix+'-'+name).val(webXMLURL);
    }
};

customNvpWidget.DelSelProduct = function(elementId) {
    var idPerfix = customNvpWidget.idPerfix;
    $("#"+idPerfix+'-ItemId').val("");
    $("#"+elementId).val("");
};

customNvpWidget.DrawFormGroup = function(title,content,divId) {
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="col-sm-2 control-label" for="'+divId+'">'+title+'： <small></small></label>'+
            content+
        '</div>';
    $("#"+customNvpWidget.divId).append(html);
};

customNvpWidget.GetImageTagData = function() {
    if(customNvpWidget.debug) console.log("customNvpWidget.GetImageTagData",productImageTag.data.ImageTag);
    var tagData = [];
    $.each(productImageTag.data.ImageTag, function(tagIndex,tag){
        tagData[tagIndex] = {Color:tag.Color,X:tag.X,Y:tag.Y,ItemId:tag.ItemId,SpotPos:tag.SpotPos};
    });
    return {value:{Tag:tagData},autoUpdate:0}; 
};

customNvpWidget.GetPolygonDrawingData = function() {
    var pdObj = customNvpWidget.polygonDrawingSetting.pdObj;
    if(customNvpWidget.debug) console.log("customNvpWidget.GetPolygonDrawingData",pdObj.coordinateString);
    return {value:pdObj.coordinateString,autoUpdate:0}; 
};

customNvpWidget.GetNvpData = function() {
    $.each(customNvpWidget.nvpTypeList, function(nvpIndex,nvp){
        var idPerfix = customNvpWidget.idPerfix,
            typeName = nvp.TypeName,
            elementId = "#"+idPerfix+"-"+typeName,
            value = customNvpWidget.nvpTypeList[nvpIndex].Value,
            format = nvp.DataFormat;
        
        if( format === "JSON" && typeName === "ImageTag" ) {
            value = customNvpWidget.GetImageTagData();
        }
        else if( format === "JSON" && typeName === "WebFloorPlan" ) {
            value = customNvpWidget.GetPolygonDrawingData();
        }
        else{
            switch(format){            
                case "TEXT":        value = {value:$(elementId).val(),autoUpdate:0};    break;
                case "LONGTEXT":    value = {value:$(elementId).val(),autoUpdate:0};    break;
                case "DATE":        value = {value:$(elementId).val(),autoUpdate:0};    break;
                case "BOOLEAN":     value = {value:$(elementId).find("option:selected").val(),autoUpdate:0};      break;
                case "JSON":        break;
                case "PRICE":       value = {value:$(elementId).val(),unit:"￥",autoUpdate:0};                    break;
                case "IMAGE_URL":   value = {value:$(elementId).attr("src"),autoUpdate:0};                        break;
                default:
            }
        }
        customNvpWidget.nvpTypeList[nvpIndex].Value = value;
    });
    if(customNvpWidget.debug) console.log("customNvpWidget.GetNvpData",customNvpWidget.nvpTypeList);
    return customNvpWidget.nvpTypeList;
};