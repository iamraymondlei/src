/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var EmployeeList = {
    dataCount:"0",
    pageSize:"10",
    pageIndex:"1",
    state:"",
    type:"",
    orderBy:"LastUpdate",
    sortBy:"desc"
};

EmployeeList.getData = function() {
    var data = [];
    $.each(tableData, function(i,item) {
        var state = "待审核";
        if(item.State === "3") {
            state = "审核通过";
        }
        else if(item.State === "2") {
            state = "审核不通过";
        }

        var stickyPostTitle = "置顶";
        var stickyPostClass = "glyphicon glyphicon-circle-arrow-up";
        if(item.StickyPost === "1"){
            stickyPostTitle = "取消置顶";
            stickyPostClass = "glyphicon glyphicon-circle-arrow-right";
        }

        var dataType = item.TypeName;
        var itemValue = item.Value;
        if(item.TypeName === "VIDEO"){
            dataType = "LINK";
            try{
                if($(itemValue).length > 0){
                    itemValue = $(itemValue).attr("src");
                }
            }
            catch(err){
                //console.log(err);
            }
        }
        var columnData = {
            id:item.EmployeeUploadDataId,
            columns:[
                {type:"TEXT",value:item.EmployeeName,dataKey:"EmployeeName"},
                {type:"TEXT",value:item.DisplayName,dataKey:"TypeId"},
                {type:"PERVIEWIMAGE",value:item.RepresentImageUrl,dataKey:"RepresentImageUrl"},
                {type: dataType,value:itemValue,dataKey:"Value",onClick:"EmployeeList.MediaOnClick(this,'"+item.TypeName+"')"},
                {type:"TEXT",value:state,dataKey:"State"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.EmployeeId,btnList:[
                    {type:"",title:"审核通过",class:"glyphicon glyphicon-ok",onClick:"EmployeeList.PassOnClick("+item.EmployeeUploadDataId+")"},
                    {type:"",title:"审核不通过",class:"glyphicon glyphicon-remove",onClick:"EmployeeList.RejectOnClick("+item.EmployeeUploadDataId+")"},
                    {type:"",title:stickyPostTitle,class:stickyPostClass,onClick:"EmployeeList.StickyPostOnClick(this,"+item.EmployeeUploadDataId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

EmployeeList.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=employee";
        url+= "&a="+((action)?action:"index");
        url+= "&k="+$("#employee-searchBox").val();
        url+= "&s="+$('#employee-state-multiSelect').val();
        url+= "&dt="+$('#employee-dataType-multiSelect').val();
        url+= "&ps="+EmployeeList.pageSize;
        url+= "&pi="+EmployeeList.pageIndex;
        url+= "&ob="+EmployeeList.orderBy;
        url+= "&sb="+EmployeeList.sortBy;
        url+= (params)?params:"";
    location.href = url;
};

EmployeeList.PageSizeOnClick = function(size) {
    EmployeeList.pageSize = size;
    EmployeeList.RefreshGridTable();
};

EmployeeList.PageIndexOnClick = function(event,selectedPage) {
    EmployeeList.pageIndex = selectedPage;
    EmployeeList.RefreshGridTable();
};

EmployeeList.SearchOnBtn = function() {
    EmployeeList.page = 1;
    EmployeeList.RefreshGridTable();
};

EmployeeList.SearchOnKeypress = function(event) {
    if(event.keyCode === 13){
        EmployeeList.page = 1;
        EmployeeList.RefreshGridTable();
    }
};

EmployeeList.MediaOnClick = function(obj, type) {
    var url = $(obj).attr("src");
    if(type === "IMAGE" || type === "PERVIEWIMAGE"){
        BootstrapDialog.show({
            title: '图片',
            message: '<img src="'+url+'" />'
        });
    }
};

EmployeeList.SelectBoxInit = function() {
    var dataTypeSel = $('#employee-dataType-multiSelect');
    dataTypeSel.multiselect('rebuild');

    var stateSel = $('#employee-state-multiSelect');
    stateSel.multiselect('rebuild');
};

EmployeeList.PassOnClick = function(id) {
    var params = "&us=3&id="+id;
    EmployeeList.RefreshGridTable(params,"update");
};

EmployeeList.RejectOnClick = function(id) {
    var params = "&us=2&id="+id;
    EmployeeList.RefreshGridTable(params,"update");
};

EmployeeList.StickyPostOnClick = function(obj,id) {
    var params = "&id="+id;
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-circle-arrow-up" ){
        params += "&usp=1";
    }
    else{
        params += "&usp=0";
    }
    EmployeeList.RefreshGridTable(params,"update");
};

EmployeeList.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        EmployeeList.orderBy = "LastUpdate";
        EmployeeList.sortBy = "asc";
    }
    else{
        EmployeeList.orderBy = "LastUpdate";
        EmployeeList.sortBy = "desc";
    }
    EmployeeList.RefreshGridTable();
};

$(function () {
    EmployeeList.SelectBoxInit();
    EmployeeList.tableWidget = customTableGroup.Create();
    var sortIconClass = (EmployeeList.sortBy === "desc")?"-alt":"";
    var sortCnName = (EmployeeList.sortBy === "desc")?"降序":"升序";
    EmployeeList.customTableParams = {
        divId:"employeeList-table",
        dataCount:10,
        tableData:EmployeeList.getData(),
        columns:[
            {name:"员工名称",dataKey:"EmployeeName"},
            {name:"信息类型",dataKey:"TypeId"},
            {name:"代表图",dataKey:"RepresentImageUrl",class:"text-center"},
            {name:"感悟内容",dataKey:"Value",class:"text-center col-lg-2"},
            {name:"审核状态",dataKey:"State"},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"EmployeeList.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:EmployeeList.pageSize,
            pageIndex:EmployeeList.pageIndex,
            pageSizeOnClick:"EmployeeList.PageSizeOnClick",
            pageIndexOnClick:EmployeeList.PageIndexOnClick
        }
    };
    EmployeeList.tableWidget.Draw(EmployeeList.customTableParams);
});