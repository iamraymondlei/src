var customTextWidget = {
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

customTextWidget.Draw = function(option) {
    customTextWidget.divId = option.divId;
    customTextWidget.idPerfix = option.idPerfix;
    customTextWidget.title = (option.title)?option.title:'标题';
    customTextWidget.placeholder = (option.placeholder)?option.placeholder:'不超過20個字符';
    customTextWidget.value = option.value;  
    customTextWidget.type = (option.type)?option.type:"text";  
    customTextWidget.readonly = (option.readonly)?option.readonly:"";  
    customTextWidget.errorMsg = (option.errorMsg)?option.errorMsg:""; 
    customTextWidget.required = (option.required)?"required":"";
    customTextWidget.maxLength = (option.maxLength)?'maxlength="'+option.maxLength+'"':'';
    customTextWidget.name = (option.name)?option.name:"";
    
    var perfix = customTextWidget.idPerfix;
    var params = {
        title:customTextWidget.title,
        groupId: perfix+'-textWidget-group',
        inputId: perfix+'-inputText',
        placeholder: customTextWidget.placeholder,
        readonly: (customTextWidget.readonly)?customTextWidget.readonly:"",
        value: (customTextWidget.value)?customTextWidget.value:"",
        type: (customTextWidget.type)?customTextWidget.type:"text",
        errorMsg: (customTextWidget.errorMsg)?customTextWidget.errorMsg:"",
        required: (customTextWidget.required)?customTextWidget.required:"",
        maxLength: (customTextWidget.maxLength)?customTextWidget.maxLength:"",
        name: (customTextWidget.name)?customTextWidget.name:""
    }; 
    customTextWidget.DrawInputGroup(params);
};

customTextWidget.DrawInputGroup = function(params) {
    if( customTextWidget.debug ) console.log("DrawInputGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            placeholder = params.placeholder,
            value = params.value,
            readonly = params.readonly,
            type = params.type,
            errorMsg = params.errorMsg,
            required = params.required,
            name = params.name,
            maxLength = params.maxLength;
        var content = "<input type='"+type+"' name='"+name+"' class='form-control' id='"+inputId+"'  placeholder='"+placeholder+"' value='"+value+"' "+readonly+" "+maxLength+" "+required+" >";
        customTextWidget.DrawFormGroup(title,content,groupId,errorMsg);
        customTextWidget.Clear();
    }
};

customTextWidget.DrawFormGroup = function(title,content,divId,errorMsg) {
    if( customTextWidget.debug ) console.log("DrawFormGroup",customTextWidget.divId);
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="control-label" for="'+divId+'">'+title+'</label>'+
            content+
            '<div class="help-block with-errors">'+errorMsg+'</div>'+
        '</div>';
    $("#"+customTextWidget.divId).append(html);
};

customTextWidget.Clear = function() {
    customTextWidget.divId = "";
    customTextWidget.idPerfix = "";
    customTextWidget.value = "";
    customTextWidget.type = "";
    customTextWidget.name = "";
};