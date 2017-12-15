/* 
 * 控件： customGridView
 * 说明:  画图片列表，每个包含图片和标题，遮罩层， 带分页选择按钮和页码列表
 * 版本： 1.0
 * 日期： 2017-02-09
 * 作者： Raymond.Lui
 * 
 * configSetting说明：
 * configSetting = {
        divId:"",                                   //必需，图片列表的父标签Id
        gridEffect:"3",                             //可选，图片列表动画效果，值为(1-9)或false
        dataCount:0,                                //必需，图片总数
        gridData:[{                                 //必需，图片列表数据，多条
            id:"",                                  //必需，item Id
            imageUrl:"",                            //必需，item image url
            title:"",                               //必需，item title
            mask:{                                  //可选，item mask， 没有时，不画遮罩层
                btn:[                               //必需，遮罩层上的按钮组
                    {icon:"", name:"", onClick:""}, //必需，按钮图标，按钮名称，单击事件
                    {icon:"", name:"", onClick:""}, //必需，按钮图标，按钮名称，单击事件
                ]
            }
        }],
        page:{
            pagination:true,                        //必需，是否开启分页
            pSizeOption:[{pageSize:[10,20,50,100]}],//必需，设定可选分页大小选项
            pIndex:1,                               //可选，页码
            pSize:10,                               //可选，每页大小
            pSizeOnClick:"",                        //必需，选择每页大小后的单击事件
            pIndexOnClick:null                      //必需，页码单击事件
        },
        sort: {
            sortable:false,                         //可选，图片列表是否可手动，值true或false， 默认false， 当gridEffect为false且sortable为true才有效
            onStop:null                             //可选，拖动后的响应事件
        }
    }

 * 调用例子：
 * var customGridView = {};//值结构与customGridView.configSetting一样
 * var cgv = customGridView.Create();
 *     cgv.Draw(configSetting);
 */

var customGridView = {
    debug:true,
    configSetting:{
        divId:"",
        gridId:"",
        gridEffect:"3",
        gridData:[],//{id:"", imageUrl:"", title:"", mask:{btn:[{icon:"", name:"", onClick:""},{icon:"", name:"", onClick:""}]}}
        dataCount:0,
        page:{
            pagination:true,
            pSizeOption:[],//{pageSize:[10,20,50,100]}
            pIndex:1,
            pSize:10,
            pSizeOnClick:"",
            pIndexOnClick:null
        },
        sort:{
            sortable:false,
            onStop:null
        }
    },
    
    /**
    * 方法说明： 创建新的customGridView对象
    * @method Create
    * @return {object} 返回customGridView对象
    */
    Create: function() {
        var self = Object.create(this);
        return self;
    },

    /**
    * 方法说明： 根据参数params的设定画Grid
    * @method Draw
    * @param {object} params 如： customGridView.configSetting
    */
    Draw: function(params) {
        if(params){
            this.Clear();
            this.SetConfig(params);
            this.DrawGrid();
            this.DrawGridItem();
            this.DrawSelPageSizeBtn();
            this.DrawPagination();
            this.SetAnimation();
            this.SetSortable();
        }
    },

    /**
    * 方法说明： 设定customGridView.configSetting的值
    * @method SetConfig
    * @param {object} params 如： customGridView.configSetting
    */
    SetConfig: function(params) {
        if(params){
            this.configSetting.divId = params.divId;
            this.configSetting.gridId = this.configSetting.divId + "-gridView-" + this.GetRandomNum() + "-group";
            this.configSetting.gridEffect = params.gridEffect;
            this.configSetting.sort = (params.sort)?params.sort:this.configSetting.sort;
            this.configSetting.gridData = params.gridData;
            this.configSetting.dataCount = params.dataCount;
            this.configSetting.page = (params.page)?params.page:this.configSetting.page;
            $("#"+this.configSetting.divId).data(params);
        }
    },

    /**
    * 方法说明： 删除Grid
    * @method RemoveGrid
    */
    RemoveGrid: function() {
        var customGridViewObj = this;
        $("#"+customGridViewObj.configSetting.gridId).remove();
    },
    
    /**
    * 方法说明： 画Grid(即列表元素的父标签ul)
    * @method DrawGrid
    */
    DrawGrid: function() {
        var customGridViewObj = this,
            divId = customGridViewObj.configSetting.divId,
            gridId = customGridViewObj.configSetting.gridId,
            gridEffect = customGridViewObj.configSetting.gridEffect,
            gridEffectClass = (customGridViewObj.configSetting.gridEffect)?'effect-'+gridEffect:"",
            grid = $('<ul id="'+gridId+'" class="grid '+gridEffectClass+'">');
            $("#"+divId).prepend(grid);
    },

    /**
    * 方法说明： 画GridItem(即列表元素的li)
    * @method DrawGridItem
    */
    DrawGridItem: function() {
        var customGridViewObj = this,
            gridId = customGridViewObj.configSetting.gridId,
            gridData = customGridViewObj.configSetting.gridData;
            
        $.each(gridData, function(itemIndex, item){
            var gridItemId = gridId + "-item-" + item.id,
                itemGroup = $('<li id="'+gridItemId+'" itemId="'+item.id+'">'),
                containerDiv = $('<div class="container-fluid">'),
                imgGroup = $('<div class="row text-center" style="margin:0 -15px 0 -15px;" >'),
                itemMask = customGridViewObj.DrawGridItemImgMask(item),
                itemImage = customGridViewObj.DrawGridItemImg(item),
                itemTitle = customGridViewObj.DrawGridItemTitle(item);
            
            imgGroup.append(itemMask);
            imgGroup.append(itemImage);
            
            containerDiv.append(imgGroup);
            containerDiv.append(itemTitle);
            
            itemGroup.append(containerDiv);
            $("#"+gridId).append(itemGroup);
            
            customGridViewObj.BindGridItemEvent(gridItemId);
        });
        
        if(customGridViewObj.configSetting.gridData.length === 0){
            var erroMsg = $('<h1 class="text-center gray">没有任何记录</h1>');
            $("#"+gridId).append(erroMsg);
        }
    },
    
    /**
    * 方法说明： 画GridItem内的图片元素
    * @method DrawGridItemImg
    * @param {object} itemData 如：{id:"", imageUrl:"", title:"", mask:{btn:[{icon:"", name:"", onClick:""},{icon:"", name:"", onClick:""}]}}
    * @return {object} 返回对象，如：$('<a target=""><img src="xx.jpg"></a>');
    */
    DrawGridItemImg: function(itemData) {
        var image = $('<a target=""><img src="'+itemData.imageUrl+'"></a>');
        return image;
    },
    
    /**
    * 方法说明： 画GridItem内的的遮罩层及遮罩层上的可点击按钮
    * @method DrawGridItemImgMask
    * @param {object} itemData 如：{id:"", imageUrl:"", title:"", mask:{btn:[{icon:"", name:"", onClick:""},{icon:"", name:"", onClick:""}]}}
    * @return {object} 返回遮罩层Div对象 如：<div class="grid-item-mask"><div class="well-sm grid-item-mask-btn-1" title="编缉" onClick="" ><span class=''></span></div></div>
    */
    DrawGridItemImgMask: function(itemData) {
        var maskDiv = null;
        if(itemData.mask){
            maskDiv = $('<div class="grid-item-mask">');
            $.each(itemData.mask.btn,function(btnIndex,btn){
                var btnOnClickEvent = "";
                if(btn.onClick) btnOnClickEvent = 'onclick="'+btn.onClick+'('+itemData.id+',this)"';
                var maskBtnDiv = $('<div class="grid-item-mask-btn-'+(btnIndex+1)+'" title="'+btn.name+'" '+btnOnClickEvent+' >');
                var maskBtnA = $('<a class="btn btn-primary btn-lg active" role="button"></a>');
                var maskBtnI = $('<i class="'+btn.icon+'"></i>');
                var maskBtnSpan = $('<span></span>');
                
                maskBtnA.append(maskBtnI);
                maskBtnA.append(maskBtnSpan);
                maskBtnDiv.append(maskBtnA);
                maskDiv.append(maskBtnDiv);
            });
        }
        return maskDiv;
    },
    
    /**
    * 方法说明： 画GridItem内的标题元素
    * @method DrawGridItemTitle
    * @param {object} itemData 如：{id:"", imageUrl:"", title:"", mask:{btn:[{icon:"", name:"", onClick:""},{icon:"", name:"", onClick:""}]}}
    * @return {object} 返回对象，如：<div class="row"><h5 class="text-left"><Strong>标题</Strong></h5></div>;
    */
    DrawGridItemTitle: function(itemData) {
        if(itemData.title){
            var divGroup = $('<div class="row">');
            var titleGroup = $('<h5 class="text-left"><Strong>'+itemData.title+'</Strong></h5>');
            //var desciptionGroup = $('<p class="text-left">'+itemData.description+'</p>');
            return divGroup.append(titleGroup);
        }
        else
            return "";
    },
    
    /**
    * 方法说明： 绑定GridItem事件，包括click,mouseover,mouseout
    * @method BindGridItemEvent
    * @param {int} gridItemId
    */
    BindGridItemEvent: function(gridItemId) {
        $('#'+gridItemId).unbind();
        $('#'+gridItemId).bind({
            click: function() {
                //console.log('click'+gridItemId);
            },
            mouseover: function() {
                var maskWidth = $("#"+gridItemId+" .container-fluid").width();
                var maskHight = $("#"+gridItemId+" .container-fluid > div > a > img").height();
                $("#"+gridItemId+" .grid-item-mask").css("width",maskWidth+30);
                $("#"+gridItemId+" .grid-item-mask").css("height",maskHight);
                $("#"+gridItemId+" .grid-item-mask").show();
            },
            mouseout: function() {
                $("#"+gridItemId+" .grid-item-mask").hide();
            }
        });
    },
    
    /**
    * 方法说明： 画选择每页大小的下拉按钮
    * @method DrawSelPageSizeBtn
    */
    DrawSelPageSizeBtn: function() {
        var customGridViewObj = this;
        if(customGridViewObj.configSetting.page.pagination){
                var divId = customGridViewObj.configSetting.divId,
                pSize = customGridViewObj.configSetting.page.pSize,
                divPageSizeGroupId = divId+'-customGridView-selPageSizeBtnGroup',
                divPageSizeOptionGroupId = divId+'-customGridView-selPageSizeBtn',
                divPageSizeGroup =  '<div class="col-md-3 pull-left" style="padding-top:20px;" id="'+divPageSizeGroupId+'">'+
                                        '<div class="pull-left" style="line-height:35px;">每页显示</div>'+
                                        '<div class="dropdown pull-left" style="padding:0 5px;" id="'+divPageSizeOptionGroupId+'"></div>'+
                                        '<div class="pull-left" style="line-height:35px;">项</div>'+
                                    '</div>',
                dropdownBtn = $('<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" value="'+pSize+'" >');
                dropdownBtn.append( pSize+' <span class="caret"></span>');
                
            var dropdownUl = $('<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">');
            $.each(customGridViewObj.configSetting.page.pSizeOption, function(itemIndex,item) {
                dropdownUl.append('<li><a onclick="'+customGridViewObj.configSetting.page.pSizeOnClick+'('+item+')">'+item+'</a></li>');
            });

            $("#"+divPageSizeGroupId).remove();
            $("#"+divId).append(divPageSizeGroup);
            $("#"+divPageSizeOptionGroupId).append(dropdownBtn);
            $("#"+divPageSizeOptionGroupId).append(dropdownUl);
        }
    },
    
    /**
    * 方法说明： 画页码列表
    * @method DrawPagination
    */
    DrawPagination: function() {
        var customGridViewObj = this,
            divId = customGridViewObj.configSetting.divId,
            itemCount = customGridViewObj.configSetting.dataCount,
            pageSize = $("#"+divId+'-customGridView-selPageSizeBtn').children("button").attr("value"),
            totalPages = Math.ceil(itemCount/pageSize);
    
        if(customGridViewObj.configSetting.page.pagination && totalPages > 0){
            var paginationGroupId = divId+'-customGridView-pagination-group',
                paginationUlId = divId+'-customGridView-pagination',
                divPaginationGroup =  $('<div class="col-md-8 pull-right" id="'+paginationGroupId+'">'),
                ulPaginationGroup = $('<ul class="pagination pull-right" id="'+paginationUlId+'">');
                
            $('#'+paginationGroupId).remove();
            $(divPaginationGroup).append(ulPaginationGroup);
            $("#"+divId).append(divPaginationGroup);

            $('#'+paginationUlId).twbsPagination({
                startPage:customGridViewObj.configSetting.page.pIndex,
                totalPages: totalPages,
                visiblePages: 10,
                first: '首页',
                prev: '上一页',
                next: '下一页',
                last: '最后一页',
                onPageClick: function (event, selectedPage) {
                    if(selectedPage !== customGridViewObj.configSetting.page.pIndex){
                        customGridViewObj.configSetting.page.pIndex = selectedPage;
                        customGridViewObj.configSetting.page.pIndexOnClick(event,selectedPage);
                    }
                }
            });
        }
    },

    /**
    * 方法说明： 重画Grid
    * @method Refresh
    * @param {object} params 如： customGridView.configSetting
    */
    Refresh: function(params) {
        var customGridViewObj = this;
        customGridViewObj.RemoveGrid();
        customGridViewObj.SetConfig(params);
        customGridViewObj.DrawGrid();
        customGridViewObj.DrawGridItem();
        customGridViewObj.DrawSelPageSizeBtn();
        customGridViewObj.DrawPagination();
        customGridViewObj.SetAnimation();
        customGridViewObj.SetSortable();
    },

    /**
    * 方法说明： 还原configSetting为默认
    * @method Clear
    */
    Clear: function() {
        this.configSetting = {
            divId:"",
            gridId:"",
            gridEffect:"3",
            gridData:[],
            dataCount:0,
            page:{
                pagination:true,
                pSizeOption:[],
                pIndex:1,
                pSize:10,
                pSizeOnClick:null,
                pIndexOnClick:null
            },
            sort:{
                sortable:false,
                onStop:null
            }
        };
    },
    
    /**
    * 方法说明： 获取0-1000000000000000内的随机数
    * @method GetRandomNum
    * @return {int}
    */
    GetRandomNum: function() {
        var num = Math.random()*1000000000000000;
        return Math.floor(num);
    },
    
    /**
    * 方法说明： 设定Grid使用AnimOnScroll控件的动画效果
    * @method SetAnimation
    */
    SetAnimation: function() {
        try{
            var customGridViewObj = this;
            if(customGridViewObj.configSetting.sort.sortable === false && customGridViewObj.configSetting.gridData.length > 0) {//customGridView.configSetting.gridEffect && typeof customGridView === 'object' &&
                var customGridViewObj = this;
                new AnimOnScroll( document.getElementById( customGridViewObj.configSetting.gridId ),{
                    minDuration : 0.4,
                    maxDuration : 0.7,
                    viewportFactor : 0.2}
                );
            }
        }
        catch(e){}
    },
        
    /**
    * 方法说明： 设定GridItem可拖动
    * @method SetSortable
    */
    SetSortable: function() {
        var customGridViewObj = this;
        if(customGridViewObj.configSetting.sort.sortable && customGridViewObj.configSetting.gridData.length > 0) {
            $( "#"+customGridViewObj.configSetting.gridId ).sortable({
                opacity:0.5,
                stop: customGridViewObj.configSetting.sort.onStop
            });
            $( "#"+customGridViewObj.configSetting.gridId ).disableSelection();
        }
    },
    
    /**
    * 方法说明： 获取拖动后的排序
    * 是否对外： 是
    * @return {array} itemSortList
    */
    GetGridItemSort: function() {
        var customGridViewObj = this,
            itemSortList = [];
        $.each($( "#"+customGridViewObj.configSetting.gridId ).children("li"),function(index,item){
            var itemId = $(item).attr("itemId");
            itemSortList[index] = itemId;
        });
        return itemSortList;
    }
};