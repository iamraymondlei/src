/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var History = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

History.getData = function() {
    var data = [];
    $.each(tableData, function(i,item) {
        var columnData = {
            id:item.NewsId,
            columns:[
                {type:"TEXT",value:item.Title,dataKey:"NewsTitle"},
                {type:"TEXT",value:item.ImageCount,dataKey:"ImageCount"},
                {type:"TEXT",value:item.ArticleContent,dataKey:"ArticleContent"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.NewsId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"History.EditOnClick("+item.NewsId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

History.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=history";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+History.pageSize;
        url+= "&pi="+History.pageIndex;
        url+= "&ob="+History.orderBy;
        url+= "&sb="+History.sortBy;
        url+= "&ci="+History.catId;
        url+= (params)?params:"";
    location.href = url;
};

History.PageSizeOnClick = function(size) {
    History.pageSize = size;
    History.RefreshGridTable();
};

History.PageIndexOnClick = function(event,selectedPage) {
    History.pageIndex = selectedPage;
    History.RefreshGridTable();
};

History.EditOnClick = function(id) {
    var params = "&id="+id;
    History.RefreshGridTable(params,"edit");
};

History.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        History.orderBy = "LastUpdate";
        History.sortBy = "asc";
    }
    else{
        History.orderBy = "LastUpdate";
        History.sortBy = "desc";
    }
    History.RefreshGridTable();
};

$(function () {
    History.tableWidget = customTableGroup.Create();
    var sortIconClass = (History.sortBy === "desc")?"-alt":"";
    var sortCnName = (History.sortBy === "desc")?"降序":"升序";
    History.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:History.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"图片数量",dataKey:"ImageCount",class:""},
            {name:"内容",dataKey:"ArticleContent",class:"col-lg-4"},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"History.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:History.pageSize,
            pageIndex:History.pageIndex,
            pageSizeOnClick:"History.PageSizeOnClick",
            pageIndexOnClick:History.PageIndexOnClick
        }
    };
    History.tableWidget.Draw(History.customTableParams);
});