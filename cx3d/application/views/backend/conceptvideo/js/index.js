/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Conceptvideo = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Conceptvideo.getData = function() {
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
            id:item.VideoListId,
            columns:[
                {type:"TEXT",value:item.Title,dataKey:"NewsTitle"},
                {type:"IMAGE",value:item.PreviewImageUrl,dataKey:"PreviewImageUrl"},
                {type:"LINK",value:itemVideoUrl,dataKey:"VideoUrl"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.VideoListId,btnList:[
                    // {type:"",title:"添加",class:"glyphicon glyphicon-plus",onClick:"Conceptvideo.AddOnClick()"},
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Conceptvideo.EditOnClick("+item.VideoListId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Conceptvideo.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=conceptvideo";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Conceptvideo.pageSize;
        url+= "&pi="+Conceptvideo.pageIndex;
        url+= "&ob="+Conceptvideo.orderBy;
        url+= "&sb="+Conceptvideo.sortBy;
        url+= "&ci="+Conceptvideo.catId;
        url+= "&id="+Conceptvideo.newsId;
        url+= (params)?params:"";
    location.href = url;
};

Conceptvideo.PageSizeOnClick = function(size) {
    Conceptvideo.pageSize = size;
    Conceptvideo.RefreshGridTable();
};

Conceptvideo.PageIndexOnClick = function(event,selectedPage) {
    Conceptvideo.pageIndex = selectedPage;
    Conceptvideo.RefreshGridTable();
};

Conceptvideo.EditOnClick = function(id) {
    var params = "&vid="+id+"&id="+Conceptvideo.newsId;
    Conceptvideo.RefreshGridTable(params,"edit");
};

Conceptvideo.AddOnClick = function() {
    var params = "&id="+Conceptvideo.newsId;
    Conceptvideo.RefreshGridTable(params,"add");
};

Conceptvideo.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Conceptvideo.orderBy = "LastUpdate";
        Conceptvideo.sortBy = "asc";
    }
    else{
        Conceptvideo.orderBy = "LastUpdate";
        Conceptvideo.sortBy = "desc";
    }
    Conceptvideo.RefreshGridTable();
};

$(function () {
    Conceptvideo.tableWidget = customTableGroup.Create();
    var sortIconClass = (Conceptvideo.sortBy === "desc")?"-alt":"";
    var sortCnName = (Conceptvideo.sortBy === "desc")?"降序":"升序";
    Conceptvideo.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Conceptvideo.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"代表图",dataKey:"PreviewImageUrl",class:"text-center"},
            {name:"视频",dataKey:"VideoUrl",class:"text-center"},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Conceptvideo.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Conceptvideo.pageSize,
            pageIndex:Conceptvideo.pageIndex,
            pageSizeOnClick:"Conceptvideo.PageSizeOnClick",
            pageIndexOnClick:Conceptvideo.PageIndexOnClick
        }
    };
    Conceptvideo.tableWidget.Draw(Conceptvideo.customTableParams);
});