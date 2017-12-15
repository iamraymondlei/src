/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Honor = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Honor.getData = function() {
    var data = [];
    $.each(tableData, function(i,item) {
        var columnData = {
            id:item.NewsId,
            columns:[
                {type:"TEXT",value:item.Title,dataKey:"NewsTitle"},
                {type:"TEXT",value:item.ImageCount,dataKey:"ImageCount"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.NewsId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Honor.EditOnClick("+item.NewsId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Honor.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=honor";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Honor.pageSize;
        url+= "&pi="+Honor.pageIndex;
        url+= "&ob="+Honor.orderBy;
        url+= "&sb="+Honor.sortBy;
        url+= "&ci="+Honor.catId;
        url+= (params)?params:"";
    location.href = url;
};

Honor.PageSizeOnClick = function(size) {
    Honor.pageSize = size;
    Honor.RefreshGridTable();
};

Honor.PageIndexOnClick = function(event,selectedPage) {
    Honor.pageIndex = selectedPage;
    Honor.RefreshGridTable();
};

Honor.EditOnClick = function(id) {
    var params = "&id="+id;
    Honor.RefreshGridTable(params,"edit");
};

Honor.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Honor.orderBy = "LastUpdate";
        Honor.sortBy = "asc";
    }
    else{
        Honor.orderBy = "LastUpdate";
        Honor.sortBy = "desc";
    }
    Honor.RefreshGridTable();
};

$(function () {
    Honor.tableWidget = customTableGroup.Create();
    var sortIconClass = (Honor.sortBy === "desc")?"-alt":"";
    var sortCnName = (Honor.sortBy === "desc")?"降序":"升序";
    Honor.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Honor.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"图片数量",dataKey:"ImageCount",class:""},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Honor.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Honor.pageSize,
            pageIndex:Honor.pageIndex,
            pageSizeOnClick:"Honor.PageSizeOnClick",
            pageIndexOnClick:Honor.PageIndexOnClick
        }
    };
    Honor.tableWidget.Draw(Honor.customTableParams);
});