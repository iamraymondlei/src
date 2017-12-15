var customTextareaWidget = {
    debug:false,
    divId:"",
    idPerfix:"",
    value:""
}

customTextareaWidget.Draw = function(option) {
    customTextareaWidget.divId = option.divId;
    customTextareaWidget.idPerfix = option.idPerfix;
    customTextareaWidget.title = (option.title)?option.title:'描述';
    customTextareaWidget.placeholder = (option.placeholder)?option.placeholder:'请输入内容，不超过50个字符';
    customTextareaWidget.value = option.value;
    
    var perfix = customTextareaWidget.idPerfix;
    var params = {
        title:customTextareaWidget.title,
        groupId: perfix+'-textareaWidget-group',
        textareaId: perfix+'-inputTextarea',
        placeholder: customTextareaWidget.placeholder,
        value: (customTextareaWidget.value)?customTextareaWidget.value:""
    }; 
    customTextareaWidget.DrawTextareaGroup(params);
}

customTextareaWidget.DrawTextareaGroup = function(params) {
    if( customTextareaWidget.debug ) console.log("DrawTextareaGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            textareaId = params.textareaId,
            placeholder = params.placeholder,
            value = params.value
        var content = '<textarea class="form-control" id="'+textareaId+'" rows="3" placeholder="'+placeholder+'">'+value+'</textarea>';
        customTextareaWidget.DrawFormGroup(title,content,groupId);
        customTextareaWidget.Clear();
    }
}

customTextareaWidget.DrawFormGroup = function(title,content,divId) {
    if( customTextareaWidget.debug ) console.log("DrawFormGroup",customTextareaWidget.divId);
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="control-label" for="'+divId+'">'+title+'</label>'+
            content+
        '</div>';
    $("#"+customTextareaWidget.divId).append(html);
}

customTextareaWidget.Clear = function() {
    customTextareaWidget.divId = "";
    customTextareaWidget.idPerfix = "";
    customTextareaWidget.value = "";
    customTextareaWidget.type = "";
}