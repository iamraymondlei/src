/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Profession = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Profession.getData = function() {
    var data = [];
    $.each(tableData, function(i,item) {
        var columnData = {
            id:item.NewsId,
            columns:[
                {type:"TEXT",value:item.Title,dataKey:"NewsTitle"},
                {type:"TEXT",value:item.ImageCount,dataKey:"ImageCount"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.NewsId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Profession.EditOnClick("+item.NewsId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Profession.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=profession";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Profession.pageSize;
        url+= "&pi="+Profession.pageIndex;
        url+= "&ob="+Profession.orderBy;
        url+= "&sb="+Profession.sortBy;
        url+= "&ci="+Profession.catId;
        url+= (params)?params:"";
    location.href = url;
};

Profession.PageSizeOnClick = function(size) {
    Profession.pageSize = size;
    Profession.RefreshGridTable();
};

Profession.PageIndexOnClick = function(event,selectedPage) {
    Profession.pageIndex = selectedPage;
    Profession.RefreshGridTable();
};

Profession.EditOnClick = function(id) {
    var params = "&id="+id;
    Profession.RefreshGridTable(params,"edit");
};

Profession.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Profession.orderBy = "LastUpdate";
        Profession.sortBy = "asc";
    }
    else{
        Profession.orderBy = "LastUpdate";
        Profession.sortBy = "desc";
    }
    Profession.RefreshGridTable();
};

$(function () {
    Profession.tableWidget = customTableGroup.Create();
    var sortIconClass = (Profession.sortBy === "desc")?"-alt":"";
    var sortCnName = (Profession.sortBy === "desc")?"降序":"升序";
    Profession.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Profession.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"图片数量",dataKey:"ImageCount",class:""},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Profession.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Profession.pageSize,
            pageIndex:Profession.pageIndex,
            pageSizeOnClick:"Profession.PageSizeOnClick",
            pageIndexOnClick:Profession.PageIndexOnClick
        }
    };
    Profession.tableWidget.Draw(Profession.customTableParams);
});