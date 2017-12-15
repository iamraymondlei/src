/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Culture = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Culture.getData = function() {
    var data = [];
    $.each(tableData, function(i,item) {
        var columnData = {
            id:item.NewsId,
            columns:[
                {type:"TEXT",value:item.Title,dataKey:"NewsTitle"},
                {type:"TEXT",value:item.ImageCount,dataKey:"ImageCount"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.NewsId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Culture.EditOnClick("+item.NewsId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Culture.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=culture";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Culture.pageSize;
        url+= "&pi="+Culture.pageIndex;
        url+= "&ob="+Culture.orderBy;
        url+= "&sb="+Culture.sortBy;
        url+= "&ci="+Culture.catId;
        url+= (params)?params:"";
    location.href = url;
};

Culture.PageSizeOnClick = function(size) {
    Culture.pageSize = size;
    Culture.RefreshGridTable();
};

Culture.PageIndexOnClick = function(event,selectedPage) {
    Culture.pageIndex = selectedPage;
    Culture.RefreshGridTable();
};

Culture.EditOnClick = function(id) {
    var params = "&id="+id;
    Culture.RefreshGridTable(params,"edit");
};

Culture.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Culture.orderBy = "LastUpdate";
        Culture.sortBy = "asc";
    }
    else{
        Culture.orderBy = "LastUpdate";
        Culture.sortBy = "desc";
    }
    Culture.RefreshGridTable();
};

$(function () {
    Culture.tableWidget = customTableGroup.Create();
    var sortIconClass = (Culture.sortBy === "desc")?"-alt":"";
    var sortCnName = (Culture.sortBy === "desc")?"降序":"升序";
    Culture.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Culture.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"图片数量",dataKey:"ImageCount",class:""},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Culture.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Culture.pageSize,
            pageIndex:Culture.pageIndex,
            pageSizeOnClick:"Culture.PageSizeOnClick",
            pageIndexOnClick:Culture.PageIndexOnClick
        }
    };
    Culture.tableWidget.Draw(Culture.customTableParams);
});