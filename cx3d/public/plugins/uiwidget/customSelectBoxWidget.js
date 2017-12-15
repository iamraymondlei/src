var customSelectBoxWidget = {
    debug:false,
    divId:"",
    idPerfix:"",
    value:"",
    type:"",//single,multiple
    style:"",//"",1,2,3
    multiselectData:""
};

customSelectBoxWidget.Draw = function(option) {
    customSelectBoxWidget.divId = option.divId;
    customSelectBoxWidget.idPerfix = option.idPerfix;
    customSelectBoxWidget.title = (option.title)?option.title:'标题';
    customSelectBoxWidget.value = option.value;  
    customSelectBoxWidget.type = (option.type)?option.type:"single";  
    customSelectBoxWidget.style = (option.style)?option.style:"";  
    
    var perfix = customSelectBoxWidget.idPerfix;
    var params = {
        title:customSelectBoxWidget.title,
        groupId: perfix+'-textWidget-group',
        inputId: perfix+'-selectBox',
        placeholder: customSelectBoxWidget.placeholder,
        value: (customSelectBoxWidget.value)?customSelectBoxWidget.value:"",
        type: (customSelectBoxWidget.type)?customSelectBoxWidget.type:"single",
        style: (customSelectBoxWidget.style)?customSelectBoxWidget.style:""
    }; 
    customSelectBoxWidget.DrawSelectBoxGroup(params);
    customSelectBoxWidget.SetSelectData(params);
    customSelectBoxWidget.Clear();
};

customSelectBoxWidget.DrawSelectBoxGroup = function(params) {
    if( customSelectBoxWidget.debug ) console.log("DrawSelectBoxGroup",params);
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            type = params.type,
            style = params.style,
            multiple = (type==="multiple")?'multiple="multiple"':'';
        var content = '<div class="select2-container">'+
                        '<select class="multiselect btn-primary" id="'+inputId+'" '+multiple+' ></select>'+
                      '</div>';
        customSelectBoxWidget.DrawFormGroup(title,content,groupId,style);
    }
};

customSelectBoxWidget.DrawFormGroup = function(title,content,divId,style) {
    if( customSelectBoxWidget.debug ) console.log("DrawFormGroup",customSelectBoxWidget.divId);
    var cssClass = (style)?"":"";
    if($('#'+divId).length && $('#'+divId).length>0){
        var html = '<label class="'+cssClass+' control-label" for="'+divId+'">'+title+'</label>'+ content;
        $("#"+divId).empty();
        $("#"+divId).append(html);
    }
    else{
        var html = '<div class="form-group form-group-select2" id="'+divId+'">'+
                        '<label class="'+cssClass+' control-label" for="'+divId+'">'+title+'</label>'+ content +
                    '</div>';
        $("#"+customSelectBoxWidget.divId).append(html);
    }
};

customSelectBoxWidget.SetSelectData = function(params) {
    if(params.type === "single")
        customSelectBoxWidget.SetSingleSelectData(params);
    else if(params.type === "multiple")
        customSelectBoxWidget.SetMultiSelectData(params);
};

customSelectBoxWidget.SetMultiSelectData = function(params) {
    var optionData = [];
    $.each(params.value, function(itemIndex, item){
        optionData.push({label: item.name, value: item.id, selected: item.selected });
    });
    customSelectBoxWidget.multiselectData = optionData;
    
    customSelectBoxWidget.SetupMultiSelect(params);
};

customSelectBoxWidget.SetSingleSelectData = function(params) {
    var multiselectObj = $('#'+params.inputId);
    multiselectObj.empty();
    $.each(params.value, function(index, item){
        if(item.selected){
            multiselectObj.prepend('<option value="'+item.id+'">'+item.name+'</option>');
        }
    });
    
    $.each(params.value, function(index, item){
        if(!item.selected){
            multiselectObj.append('<option value="'+item.id+'">'+item.name+'</option>');
        }
    });            
    
    multiselectObj.multiselect({
        buttonClass: 'btn btn-primary',
        selectedClass: '',
        maxHeight: 300,
        onChange: function(option, checked) {
            
        }
    });
    
    multiselectObj.multiselect('rebuild');
};

customSelectBoxWidget.SetupMultiSelect = function(params) {
    var multiselectObj = $('#'+params.inputId);
    multiselectObj.multiselect({
        buttonClass: 'btn btn-primary',
        numberDisplayed: 6,
        selectedClass: '',
        maxHeight: 300,
        buttonText: function(options) {
            if (options.length == 0) {
                return '请选择';
            }
            else if (options.length > 6) {
                return options.length + ' 已选择';
            }
            else {
                var selected = '';
                options.each(function(index, option) {
                    selected += $(option).attr("label") + ', ';
                });
                return selected.substr(0, selected.length -2) + '';
            }
        },
        onChange: function(option, checked) {}
    });
    //console.log(customSelectBoxWidget.multiselectData);
    multiselectObj.multiselect('dataprovider', customSelectBoxWidget.multiselectData);
};

customSelectBoxWidget.Clear = function() {
    customTextWidget.divId = "";
    customTextWidget.idPerfix = "";
    customTextWidget.value = "";
    customTextWidget.type = "";
    customTextWidget.multiselectData = null;
};