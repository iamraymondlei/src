/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Promicrovideo = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Promicrovideo.getData = function() {
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
                    {type:"",title:"添加",class:"glyphicon glyphicon-plus",onClick:"Promicrovideo.AddOnClick()"},
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Promicrovideo.EditOnClick("+item.VideoListId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Promicrovideo.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=Promicrovideo";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Promicrovideo.pageSize;
        url+= "&pi="+Promicrovideo.pageIndex;
        url+= "&ob="+Promicrovideo.orderBy;
        url+= "&sb="+Promicrovideo.sortBy;
        url+= "&ci="+Promicrovideo.catId;
        url+= "&id="+Promicrovideo.newsId;
        url+= (params)?params:"";
    location.href = url;
};

Promicrovideo.PageSizeOnClick = function(size) {
    Promicrovideo.pageSize = size;
    Promicrovideo.RefreshGridTable();
};

Promicrovideo.PageIndexOnClick = function(event,selectedPage) {
    Promicrovideo.pageIndex = selectedPage;
    Promicrovideo.RefreshGridTable();
};

Promicrovideo.EditOnClick = function(id) {
    var params = "&vid="+id+"&id="+Promicrovideo.newsId;
    Promicrovideo.RefreshGridTable(params,"edit");
};

Promicrovideo.AddOnClick = function() {
    var params = "&id="+Promicrovideo.newsId;
    Promicrovideo.RefreshGridTable(params,"add");
};

Promicrovideo.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Promicrovideo.orderBy = "LastUpdate";
        Promicrovideo.sortBy = "asc";
    }
    else{
        Promicrovideo.orderBy = "LastUpdate";
        Promicrovideo.sortBy = "desc";
    }
    Promicrovideo.RefreshGridTable();
};

$(function () {
    Promicrovideo.tableWidget = customTableGroup.Create();
    var sortIconClass = (Promicrovideo.sortBy === "desc")?"-alt":"";
    var sortCnName = (Promicrovideo.sortBy === "desc")?"降序":"升序";
    Promicrovideo.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Promicrovideo.getData(),
        columns:[
            {name:"标题",dataKey:"NewsTitle",class:""},
            {name:"代表图",dataKey:"PreviewImageUrl",class:"text-center"},
            {name:"视频",dataKey:"VideoUrl",class:"text-center"},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Promicrovideo.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Promicrovideo.pageSize,
            pageIndex:Promicrovideo.pageIndex,
            pageSizeOnClick:"Promicrovideo.PageSizeOnClick",
            pageIndexOnClick:Promicrovideo.PageIndexOnClick
        }
    };
    Promicrovideo.tableWidget.Draw(Promicrovideo.customTableParams);
});