/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Typical = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Typical.getData = function() {
    var data = [];
    $.each(tableData, function(i,item) {
        var itemVideoUrl = item.VideoUrl;
        try{
            if($(itemVideoUrl).length > 0){
                itemVideoUrl = $(item.VideoUrl).attr("src");
            }
        }
        catch(err){
            //console.log(err);
        }

        var columnData = {
            id:item.NewsId,
            columns:[
                {type:"TEXT",value:item.Title,dataKey:"NewsTitle"},
                {type:"TEXT",value:item.ImageCount,dataKey:"ImageCount"},
                {type:"TEXT",value:item.ArticleContent,dataKey:"ArticleContent"},
                {type:"LINK",value:itemVideoUrl,dataKey:"VideoUrl"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.NewsId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Typical.EditOnClick("+item.NewsId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Typical.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=typical";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Typical.pageSize;
        url+= "&pi="+Typical.pageIndex;
        url+= "&ob="+Typical.orderBy;
        url+= "&sb="+Typical.sortBy;
        url+= "&ci="+Typical.catId;
        url+= (params)?params:"";
    location.href = url;
};

Typical.PageSizeOnClick = function(size) {
    Typical.pageSize = size;
    Typical.RefreshGridTable();
};

Typical.PageIndexOnClick = function(event,selectedPage) {
    Typical.pageIndex = selectedPage;
    Typical.RefreshGridTable();
};

Typical.EditOnClick = function(id) {
    var params = "&id="+id;
    Typical.RefreshGridTable(params,"edit");
};

Typical.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Typical.orderBy = "LastUpdate";
        Typical.sortBy = "asc";
    }
    else{
        Typical.orderBy = "LastUpdate";
        Typical.sortBy = "desc";
    }
    Typical.RefreshGridTable();
};

$(function () {
    Typical.tableWidget = customTableGroup.Create();
    var sortIconClass = (Typical.sortBy === "desc")?"-alt":"";
    var sortCnName = (Typical.sortBy === "desc")?"降序":"升序";
    Typical.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Typical.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"图片数量",dataKey:"ImageCount",class:""},
            {name:"内容",dataKey:"ArticleContent",class:"col-lg-4"},
            {name:"视频",dataKey:"VideoUrl",class:"text-center"},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Typical.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Typical.pageSize,
            pageIndex:Typical.pageIndex,
            pageSizeOnClick:"Typical.PageSizeOnClick",
            pageIndexOnClick:Typical.PageIndexOnClick
        }
    };
    Typical.tableWidget.Draw(Typical.customTableParams);
});