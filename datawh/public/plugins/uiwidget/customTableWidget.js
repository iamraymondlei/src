/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//delItemOnClick:null,
//editItemOnClick:null,
//viewItemOnClick:null,
//downloadItemOnClick:null,

var customTableGroup = {
    debug:true,
    divId:"",
    option:{
        coloums:[],//{name:"城市",dataKey:"label"}
        page:{
            pagination:true,
            pageSizeGroup:[],//{pageSize:[10,20,50,100]}
            pageIndex:1,
            pageSize:10,
            pageSizeOnClick:null,
            pageIndexOnClick:null
        },
        dataCount:0,
    },
    tableData:[],//{id:itemIndex,coloums:[{type:"TEXT",value:"",dataKey:"",onClick:""}]}
    
    Create: function() {
        var self = Object.create(this);
        return self;
    },

    Draw: function(params) {
        this.Clear();
        this.SetValue(params);
        this.DrawTable();
        this.DrawSelPageSizeBtn();
        this.DrawPagination();
        this.BindPagination();
        this.SetUpMultiselect();
    },

    SetValue: function(params) {
        this.divId = params.divId;
        this.option.coloums = params.coloums;
        this.option.page = params.page;
        this.option.dataCount = params.dataCount;
        this.tableData = params.tableData;
        $("#"+this.divId).data(params);
    },

    DrawTable: function() {
        var divGroupId = this.divId + "-tableWidget-group",
            divGroup = $('<div id="'+divGroupId+'" class="table-responsive">'),
            tableGroup = $('<table class="table table-hover">'),
            thead = $('<thead>'),
            tbody = $('<tbody>'),
            customTableWidgetObj = this;

        var theadData = "<tr>";
        $.each(customTableWidgetObj.option.coloums, function(coloumIndex, coloum){ 
            theadData += '<th>'+coloum.name+'</th>';
        });
        theadData += '</tr>';

        var tbodyData = "";
        if(customTableWidgetObj.tableData.length > 0){
            var customTableWidgetObj = this;
            $.each(customTableWidgetObj.tableData, function(itemIndex, item){ 
               var id = customTableWidgetObj.divId + "-tableWidget-" + item.id;
               tbodyData += "<tr id='"+id+"'>";
               $.each(item.coloums, function(coloumIndex, coloum){ 
                   tbodyData += customTableWidgetObj.DrawTableItem(itemIndex, coloum);
               });
               tbodyData += "</tr>";
            });
        }
        else
            tbodyData = '<h1 class="text-center gray">没有任何记录</h1>';
        
        $("#"+divGroupId).remove();
        tbody.append(tbodyData);
        thead.append(theadData);
        tableGroup.append(thead);
        tableGroup.append(tbody);
        divGroup.append(tableGroup);
        $("#"+this.divId).prepend(divGroup);
    },

    DrawTableItem: function(index, item) {
        var result = '';
        if(!item.visibility || (item.visibility && item.visibility === "show")){
			customAttr = '';
			$.each(item.customAttrList,function(attrKey,attrVal){
				customAttr+= attrKey+'="'+attrVal+'" ';
			});
			result = '<td '+customAttr+' dataKey="'+item.dataKey+'"></td>';
            var customTableWidgetObj = this;
            if(item.type === "TEXT"){
				var itemValue = item.value;
                if(item.onClick)
                    result = '<td '+customAttr+' onclick="'+item.onClick+'(this)" style="cursor:hand" dataKey="'+item.dataKey+'">'+itemValue+'</td>';
                else
                    result = '<td '+customAttr+' dataKey="'+item.dataKey+'">'+itemValue+'</td>';
            }
            else if(item.type === "CHECKBOX"){
                var checked = (item.check)?"checked":"";
                result = '<td '+customAttr+' dataKey="'+item.dataKey+'" dataId="'+item.value+'">'+'<input name="'+this.divId+'-tableItem-checkbox" type="checkbox" value="'+item.value+'" '+checked+' >'+'</td>';
            }
            else if(item.type === "BTNGROUP"){
                result = '<td '+customAttr+' dataKey="'+item.dataKey+'" dataId="'+item.value+'">';
                $.each(item.btnList, function(btnIndex,btn){ 
                    result+= '<span onclick="'+btn.onClick+'" title="'+btn.title+'" style="cursor:pointer" class="'+btn.class+'"></span>&nbsp;&nbsp;';
                });
                result+= '</td>';
            }
            else if(item.type === "SELECTBOX"){
                result = '<td '+customAttr+' dataKey="'+item.dataKey+'">';
                if(item.value.length > 0){
                    result+= '<select name="' + customTableWidgetObj.divId + '-tableWidget-multiselect" class="multiselect" multiple="multiple">';
                    //添加SELECTBOX控件
                    $.each(item.value, function(selOptionIndex, selOption){
                        var selected = (selOption.check == 0)?"":"selected";
                        result+='<option value="'+selOption.id+'" '+selected+'>'+selOption.title+'</option>';
                    });
                    result+= '</select>';
                }
                result+= '</td>';
            }
        }
        return result;
    },

    DrawSelPageSizeBtn: function() {
        if(this.option.page.pagination){
            var divGroupId = this.divId+'-customTable-selPageSizeBtnGroup';
            var btnGroupId = this.divId+'-customTable-selPageSizeBtn';
            var divGroup =  '<div id="'+divGroupId+'" class="col-md-3 pull-left" style="padding-top:20px;">'+
                                '<div class="pull-left" style="line-height:35px;">每页显示</div>'+
                                '<div class="dropdown pull-left" style="padding:0 5px;" id="'+btnGroupId+'"></div>'+
                                '<div class="pull-left" style="line-height:35px;">项</div>'+
                            '</div>';
            var btn = $('<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" >');
            btn.attr("value",this.option.page.pageSize);
            if(this.option.page.pageSizeGroup.length > 1) 
                btn.append( this.option.page.pageSize+' <span class="caret"></span>');
            else 
                btn.append(this.option.page.pageSize);
            var ul = $('<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">');
            var pageSizeOnClick = this.option.page.pageSizeOnClick;
            $.each(this.option.page.pageSizeGroup, function(itemIndex,item) {
                ul.append('<li><a onclick="'+pageSizeOnClick+'('+item+')">'+item+'</a></li>');
            });

            $("#"+divGroupId).remove();
            $("#"+this.divId).append(divGroup);
            $("#"+btnGroupId).append(btn);
            $("#"+btnGroupId).append(ul);
        }
    },

    DrawPagination: function() {
        if(this.option.page.pagination){
            var divGroupId = this.divId+'-customTable-pagination-group';
            var divGroup =  '<div class="col-md-8 pull-right" id="'+divGroupId+'">'+
                                '<ul id="'+this.divId+'-customTable-pagination" class="pagination">'+
                                '</ul>'+
                            '</div>';
            $("#"+divGroupId).remove();
            $("#"+this.divId).append(divGroup);
        }
    },

    BindPagination: function() {
        var customTableWidgetObj = this;
        if(customTableWidgetObj.option.page.pagination){
            var groupId = customTableWidgetObj.divId+'-customTable-pagination-group',
                ulId = customTableWidgetObj.divId+'-customTable-pagination',
                btnGroupId = customTableWidgetObj.divId+'-customTable-selPageSizeBtn',
                itemCount = customTableWidgetObj.option.dataCount,
                pageSize = $("#"+btnGroupId).children("button").attr("value"),
                totalPages = Math.ceil(itemCount/pageSize);
            if(totalPages > 0){
                $('#'+ulId).remove();
                $('<ul id="'+ulId+'" class="pagination pull-right"></ul>').appendTo('#'+groupId);
                $('#'+ulId).twbsPagination({
                    totalPages: totalPages,
                    visiblePages: 10,
                    first: '首页',
                    prev: '上一页',
                    next: '下一页',
                    last: '最后一页',
                    onPageClick: function (event, selectedPage) {
                        //console.log(selectedPage+"!=="+customTableWidgetObj.option.page.pageIndex);
                        if(selectedPage != customTableWidgetObj.option.page.pageIndex){
                            customTableWidgetObj.option.page.pageIndex = selectedPage;
                            customTableWidgetObj.option.page.pageIndexOnClick(event,selectedPage);
                        }
                    }
                });
            }
        }
    },

    RefreshTabel: function(params) {
        this.SetValue(params);
        this.DrawTable();
        this.DrawSelPageSizeBtn();
    },

    Clear: function() {
        this.divId = "";
        this.tableData = [];
        this.option.dataCount = 0;
        this.option.coloums = [];
        this.option.page = {
            pagination: true,
            pageSizeGroup:[],
            pageIndex: 1,
            pageSize: 10,
            pageSizeOnClick: null,
            pageIndexOnClick: null
        };
    },

    GetAllSelectedItems: function() {
        var itemAry = new Array();
        var customTableWidgetObj = this;
        $.each(customTableWidgetObj.tableData,function(tableIndex,tableItem){
            var coloumData = tableItem.coloums;
            $('#'+customTableWidgetObj.divId+"-tableWidget-"+tableIndex).each(function(trIndex,trElement){
                $(trElement).children("td").each(function(tdIndex,tdElement){
                    var dataKey = $(tdElement).attr("dataKey");
                    if(coloumData[tdIndex].dataKey === dataKey){
                        if(coloumData[tdIndex].type === "CHECKBOX"){
                            if($(tdElement).children("input").prop('checked')){
                                coloumData[tdIndex].check = true;
                            }
                            else{
                                coloumData[tdIndex].check = false;
                            }
                            coloumData[tdIndex].value = $(tdElement).children("input").val();
                        }
                        else if(coloumData[tdIndex].type === "TEXT"){
                            coloumData[tdIndex].value = $(tdElement).text();
                        }
                        else if(coloumData[tdIndex].type === "SELECTBOX"){
                            var seledVal = $(tdElement).children(('select[name="'+customTableWidgetObj.divId+'-tableWidget-multiselect"]')).val();
                            $.each(coloumData[tdIndex].value,function(selIndex,selOption){
                                coloumData[tdIndex].value[selIndex].check = (customTableWidgetObj.In_array(selOption.id,seledVal) != -1)?1:0;
                            });
                        }
                    }
                })
 
            });
            if(coloumData[0].type === "CHECKBOX" && coloumData[0].check)
                itemAry.push(coloumData);
            else if(coloumData[0].type !== "CHECKBOX")
                itemAry.push(coloumData);
        });
        return itemAry;
    },

    SelectAllItems: function() {
        $('input[name="'+this.divId+'-tableItem-checkbox'+'"]').each(function(index,item){
            $(item).prop("checked",true);
        });
    },

    SelectInverseItems: function() {
        $('input[name="'+this.divId+'-tableItem-checkbox'+'"]').each(function(index,item){
            if($(item).prop('checked')){
                $(item).prop("checked",false);
            }
            else{
                $(item).prop("checked",true);
            }
        });
    },
    
    SetUpMultiselect: function() {
        var customTableWidgetObj = this;
        $('select[name="'+customTableWidgetObj.divId+'-tableWidget-multiselect"]').multiselect({
            enableFiltering: true,
            includeSelectAllOption: true,
            maxHeight: 200,
            dropUp: false
        });
    },
    
    In_array: function(needle, haystack) {
        var found = 0;
        if(haystack){
            for (var i=0, len=haystack.length;i<len;i++) {
                if (haystack[i] == needle) return i;
                    found++;
            }
        }
        return -1;
    }
}