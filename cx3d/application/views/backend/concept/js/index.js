/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var Concept = {
    dataCount:"0",
    pageSize:"50",
    pageIndex:"1",
    orderBy:"LastUpdate",
    sortBy:"desc",
    catId:""
};

Concept.getData = function() {
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
                {type:"IMAGE",value:item.ArticleRepresentImageUrl,dataKey:"ArticleRepresentImageUrl",onClick:"Concept.MediaOnClick(this,'IMAGE')"},
                {type:"TEXT",value:item.ArticleTitle,dataKey:"ArticleTitle"},
                {type:"TEXT",value:item.ArticleContent,dataKey:"ArticleContent"},
                {type:"LINK",value:itemVideoUrl,dataKey:"VideoUrl"},
                {type:"TEXT",value:item.LastUpdate,dataKey:"LastUpdate"},
                {type:"BTNGROUP",value:item.NewsId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"Concept.EditOnClick("+item.NewsId+")"}
                ]}
            ]
        };
        data.push(columnData);
    });
    return data;
};

Concept.RefreshGridTable = function(params,action) {
    var url = "index.php?p=backend&c=concept";
        url+= "&a="+((action)?action:"index");
        url+= "&ps="+Concept.pageSize;
        url+= "&pi="+Concept.pageIndex;
        url+= "&ob="+Concept.orderBy;
        url+= "&sb="+Concept.sortBy;
        url+= "&ci="+Concept.catId;
        url+= (params)?params:"";
    location.href = url;
};

Concept.PageSizeOnClick = function(size) {
    Concept.pageSize = size;
    Concept.RefreshGridTable();
};

Concept.PageIndexOnClick = function(event,selectedPage) {
    Concept.pageIndex = selectedPage;
    Concept.RefreshGridTable();
};

Concept.MediaOnClick = function(obj, type) {
    var url = $(obj).attr("src");
    if(type === "IMAGE" || type === "PERVIEWIMAGE"){
        BootstrapDialog.show({
            title: '图片',
            message: '<img src="'+url+'" />'
        });
    }
};

Concept.EditOnClick = function(id) {
    var params = "&id="+id;
    Concept.RefreshGridTable(params,"edit");
};

Concept.SortOnClick = function(obj) {
    var className = $(obj).attr("class");
    if( className === "glyphicon glyphicon-sort-by-attributes-alt" ){
        Concept.orderBy = "LastUpdate";
        Concept.sortBy = "asc";
    }
    else{
        Concept.orderBy = "LastUpdate";
        Concept.sortBy = "desc";
    }
    Concept.RefreshGridTable();
};

$(function () {
     Concept.tableWidget = customTableGroup.Create();
    var sortIconClass = (Concept.sortBy === "desc")?"-alt":"";
    var sortCnName = (Concept.sortBy === "desc")?"降序":"升序";
    Concept.customTableParams = {
        divId:"newsList-table",
        dataCount:10,
        tableData:Concept.getData(),
        columns:[
            {name:"代表图",dataKey:"ArticleRepresentImageUrl",class:"text-center col-lg-2"},
            {name:"标题",dataKey:"ArticleTitle",class:"text-center col-lg-2"},
            {name:"内容",dataKey:"ArticleContent",class:"text-center col-lg-4"},
            {name:"视频",dataKey:"VideoUrl"},
            {name:"发布时间",dataKey:"LastUpdate",icon:
                [
                    {title:sortCnName,onClick:"Concept.SortOnClick",class:"glyphicon glyphicon-sort-by-attributes"+sortIconClass}
                ]
            },
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[10,20,50,100],
            pageSize:Concept.pageSize,
            pageIndex:Concept.pageIndex,
            pageSizeOnClick:"Concept.PageSizeOnClick",
            pageIndexOnClick:Concept.PageIndexOnClick
        }
    };
    Concept.tableWidget.Draw(Concept.customTableParams);
});