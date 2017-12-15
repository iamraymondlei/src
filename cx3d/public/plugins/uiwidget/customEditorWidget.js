var customEditorWidget = {
    debug:false,
    divId:"",
    idPerfix:"",
    value:"",
    readonly:"",//readonly
    type:"",//text,password
    errorMsg:"",
    required:"",
    name:""
};

customEditorWidget.Draw = function(option) {
    customEditorWidget.divId = option.divId;
    customEditorWidget.idPerfix = option.idPerfix;
    customEditorWidget.title = (option.title)?option.title:'标题';
    customEditorWidget.value = option.value;
    customEditorWidget.readonly = (option.readonly)?option.readonly:"";  
    customEditorWidget.errorMsg = (option.errorMsg)?option.errorMsg:""; 
    customEditorWidget.required = (option.required)?"required":"";
    customEditorWidget.maxLength = (option.maxLength)?'maxlength="'+option.maxLength+'"':'';
    customEditorWidget.name = (option.name)?option.name:"";
    
    var perfix = customEditorWidget.idPerfix;
    var params = {
        title:customEditorWidget.title,
        groupId: perfix+'-editorWidget-group',
        inputId: perfix+'Ckeditor',
        readonly: (customEditorWidget.readonly)?customEditorWidget.readonly:"",
        value: (customEditorWidget.value)?customEditorWidget.value:"",
        errorMsg: (customEditorWidget.errorMsg)?customEditorWidget.errorMsg:"",
        required: (customEditorWidget.required)?customEditorWidget.required:"",
        maxLength: (customEditorWidget.maxLength)?customEditorWidget.maxLength:"",
        name: (customEditorWidget.name)?customEditorWidget.name:""
    }; 
    customEditorWidget.DrawInputGroup(params);
    CKEDITOR.replace( params.inputId );
};

customEditorWidget.DrawInputGroup = function(params) {
    if( customEditorWidget.debug ) console.log("DrawInputGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            value = params.value,
            readonly = params.readonly,
            errorMsg = params.errorMsg,
            required = params.required,
            maxLength = params.maxLength,
            name = params.name;

        var content = '<textarea id="'+inputId+'" name="'+name+'" >'+value+'</textarea>';
        customEditorWidget.DrawFormGroup(title,content,groupId,errorMsg);
        customEditorWidget.Clear();
    }
};

customEditorWidget.DrawFormGroup = function(title,content,divId,errorMsg) {
    if( customEditorWidget.debug ) console.log("DrawFormGroup",customEditorWidget.divId);
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="control-label" for="'+divId+'">'+title+'</label>'+
            content+
            '<div class="help-block with-errors">'+errorMsg+'</div>'+
        '</div>';
    $("#"+customEditorWidget.divId).append(html);
};

customEditorWidget.Clear = function() {
    customEditorWidget.divId = "";
    customEditorWidget.idPerfix = "";
    customEditorWidget.value = "";
    customEditorWidget.type = "";
};