/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var customTagsGroup = {
    debug:false,
    configSetting:{
        title:"",
        divId:"",
        groupId:"",
        inputId:"",
        placeholder:"请输入标签名称",
        readonly:"",
        errorMsg:"",
        required:"required",
        maxLength:"",
        selectedData:null,
        url:""
    },
    
    Create: function() {
        var self = Object.create(this);
        return self;
    },

    Draw: function(params) {
        this.Clear();
        this.SetConfig(params);
        this.DrawTag("false");
        this.SetUpTags();
    },
    
    /**
    * 方法说明： 设定customCatViewWidget.configSetting的值
    * @method SetConfig
    * @param {object} params 如： customCatViewWidget.configSetting
    */
    SetConfig: function(params) {
        if(params){
            this.configSetting.title = (params.title)?params.title:'选择';
            this.configSetting.divId = params.divId;
            this.configSetting.groupId = params.divId + "-tags-" + this.GetRandomNum() + "-group";
            this.configSetting.inputId = params.divId + "-tags-" + this.GetRandomNum() + "-input";
            this.configSetting.placeholder = (params.placeholder)?params.placeholder:'';
            this.configSetting.readonly = (params.readonly)?params.readonly:'readonly';
            this.configSetting.errorMsg = (params.errorMsg)?params.errorMsg:'';
            this.configSetting.required = (params.required)?params.required:'required';
            this.configSetting.maxLength = (params.maxLength)?params.maxLength:'';
            this.configSetting.selectedData = (params.selectedData)?params.selectedData:'[]';
            this.configSetting.url = params.url;
        }
    },
    
    DrawTag: function(isRedraw) {
        var customTagsWidgetObj = this;
        if( customTagsWidgetObj.debug ) console.log("customTagsGroup",customTagsWidgetObj.configSetting);
        if(customTagsWidgetObj.configSetting){
            var title = customTagsWidgetObj.configSetting.title, 
                groupId = customTagsWidgetObj.configSetting.groupId,
                inputId = customTagsWidgetObj.configSetting.inputId,
                placeholder = customTagsWidgetObj.configSetting.placeholder,
                readonly = customTagsWidgetObj.configSetting.readonly,
                errorMsg = customTagsWidgetObj.configSetting.errorMsg,
                required = customTagsWidgetObj.configSetting.required,
                maxLength = customTagsWidgetObj.configSetting.maxLength,
                tagValue = "";
        
            $.each(customTagsWidgetObj.configSetting.selectedData, function(index,tag) {
                if(index === customTagsWidgetObj.configSetting.selectedData.length - 1){
                    tagValue+=tag.text;
                }
                else{
                    tagValue+=tag.text+",";
                }
            });
            
            var content = '<input type="text" class="form-control" id="'+inputId+'" value="'+tagValue+'" placeholder="'+placeholder+'" '+readonly+' '+maxLength+' '+required+' data-role="tagsinput" >';
            customTagsWidgetObj.DrawFormGroup(title,content,groupId,errorMsg,isRedraw);
        }
    },
    
    DrawFormGroup: function(title,content,groupId,errorMsg,isRedraw) {
        var customTagsWidgetObj = this;
        if( customTagsWidgetObj.debug ) console.log("DrawFormGroup",customTagsWidgetObj.configSetting.divId);
        
        var group = '<div class="form-group" id="'+groupId+'">'+'</div>';
        var html = '<label class="control-label" for="'+groupId+'">'+title+'</label>'+
                        content+
                    '<div class="help-block with-errors">'+errorMsg+'</div>';
        if(isRedraw === "true"){
            $("#"+groupId).empty();
        }
        else{
            $("#"+customTagsWidgetObj.configSetting.divId).append(group);
        }
        $("#"+groupId).append(html);
    },
    
    Add: function(data) {
        var customTagsWidgetObj = this;
        var elt = $('#'+customTagsWidgetObj.configSetting.inputId);
        elt.tagsinput('add', data);
    },

    RefreshTags: function() {
        var customTagsWidgetObj = this;
        this.configSetting.selectedData = this.GetSelectedDataObj();
        this.DrawTag("true");
        this.SetUpTags();
    },

    Clear: function() {
        var customTagsWidgetObj = this;
        $('#'+customTagsWidgetObj.configSetting.inputId).tagsinput('destroy');
        customTagsWidgetObj.configSetting ={
            title:"",
            divId:"",
            groupId:"",
            inputId:"",
            placeholder:"",
            readonly:"",
            errorMsg:"",
            required:"required",
            maxLength:"",
            selectedData:null,
            url:""
        };
    },
    
    SetUpTags: function() {
        var customTagsWidgetObj = this;    
        var elt = $('#'+customTagsWidgetObj.configSetting.inputId);
        var tagList = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: customTagsWidgetObj.configSetting.url,
                filter: function(list) {
                    var tagData = list.WebService.TagList.Tag;
                    return $.map(tagData, function(item) {
                        return { "name":item.TagName }; 
                    });
                }
            }
        });
        tagList.initialize();
        
        elt.tagsinput({
            typeaheadjs: {
                name: 'tagList',
                displayKey: 'name',
                valueKey: 'name',
                source: tagList.ttAdapter()
            }
        });
    },
    
    GetSelectedData: function() {
        var customTagsWidgetObj = this;
        var elt = $('#'+customTagsWidgetObj.configSetting.inputId);
        var data = elt.val();
        return data;
    },
     
    GetSelectedDataObj: function() {
        var customTagsWidgetObj = this;
        var elt = $('#'+customTagsWidgetObj.configSetting.inputId);
        var data = elt.tagsinput('items');
        return data;
    }, 
    /**
    * 方法说明： 获取0-1000000000000000内的随机数
    * @method GetRandomNum
    * @return {int}
    */
    GetRandomNum: function() {
        var num = Math.random()*1000000000000000;
        return Math.floor(num);
    }
};