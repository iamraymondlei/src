var customDatetimeWidget = {
    debug:false,
    divId:"",
    idPerfix:"",
    value:"",
    readonly:""//readonly
};

customDatetimeWidget.Draw = function(option) {
    customDatetimeWidget.divId = option.divId;
    customDatetimeWidget.idPerfix = option.idPerfix;
    customDatetimeWidget.title = (option.title)?option.title:'时间';
    customDatetimeWidget.placeholder = (option.placeholder)?option.placeholder:'输入时间';
    customDatetimeWidget.value = option.value;  
    customDatetimeWidget.readonly = (option.readonly)?option.readonly:"";  
    
    var perfix = customDatetimeWidget.idPerfix;
    var params = {
        title:customDatetimeWidget.title,
        groupId: perfix+'-datetimeWidget-group',
        inputId: perfix+'-datetimeText',
        placeholder: customDatetimeWidget.placeholder,
        readonly: (customDatetimeWidget.readonly)?customDatetimeWidget.readonly:"",
        value: (customDatetimeWidget.value)?customDatetimeWidget.value:""
    }; 
    customDatetimeWidget.DrawDatetimeGroup(params);
};

customDatetimeWidget.DrawDatetimeGroup = function(params) {
    var dateTimePickerOption = {
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd hh:ii:ss'
    };
    
    if(params){
        var title = params.title, 
            groupId = params.groupId,
            inputId = params.inputId,
            placeholder = params.placeholder,
            value = params.value,
            readonly = params.readonly;
        var content =   '<div id="'+inputId+'-datetimepicker" class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd hh:ii:ss" data-link-field="'+inputId+'-datetime-value" data-link-format="yyyy-mm-dd hh:ii:ss">'+
                            '<input  id="'+inputId+'" class="form-control" size="16" type="text" value="'+value+'" placeholder="'+placeholder+'" style="border:1px solid #ccc;" '+readonly+' required>'+
                            '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>'+
                        '</div>';
        customDatetimeWidget.DrawFormGroup(title,content,groupId);
//        $('#'+inputId+'-datetimepicker').datetimepicker(dateTimePickerOption).on('changeDate', function(ev){
//            customDatetimeWidget.Date = $("#"+inputId+"-datetime-value").val();
//            $('#datetimepicker').datetimepicker('setStartDate', customDatetimeWidget.Date);
//        });
        $('#'+inputId+'-datetimepicker').datetimepicker();
        customDatetimeWidget.Clear();
    }
};

customDatetimeWidget.DrawFormGroup = function(title,content,divId) {
    var html = 
        '<div class="form-group" id="'+divId+'">'+
            '<label class="control-label" for="'+divId+'">'+title+'</label>'+
            content+
            '<div class="help-block with-errors"></div>'+
        '</div>';
    $("#"+customDatetimeWidget.divId).append(html);
};

customDatetimeWidget.Clear = function() {
    customDatetimeWidget.divId = "";
    customDatetimeWidget.idPerfix = "";
    customDatetimeWidget.value = "";
};




