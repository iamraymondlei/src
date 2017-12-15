/* 
 * 控件： customCatViewWidget
 * 说明:  画图分类列表
 * 版本： 1.0
 * 日期： 2017-02-10
 * 作者： Raymond.Lui
 * 
 * configSetting说明：
 * configSetting = {
        divId:"",                                           //必需，父标签Id
        catViewId:"",                                       //可选，列表Id
        selectedCatOnClick:"",                              //必需，点击分类后的单击事件
        isMultiselect:false,                                //可选，是否多选，true或false
        selectedCatId:[],                                   //必需，已选的分类集合
        rootCat:[],                                         //可选，顶层分类Id列表。如：[20200,20300]
        catData:[{                                          //必需，列表数据，多条
            ItemCatId:"",                                   //必需，item Cat Id
            ItemCatName:"",                                 //必需，item Cat Name
            ItemCatNodeList:[                               //可选，子分类
                {ItemCatId:"",ItemCatName:"", ItemCatNodeList:""}
            ]
        }]
    }

 * 调用例子：
 * var customCatViewObj = {};//customCatViewWidget.configSetting一样
 * var cvw = customCatViewWidget.Create();
 *     cvw.Draw(configSetting);
 */

var customCatViewWidget = {
    debug:true,
    configSetting:{
        divId:"",
        catViewId:"",
        selectedCatOnClick:null,
        isMultiselect:false,
        rootCat:[],
        catData:[],
        selectedCatId:[],
        isFirstCatAsDefault:false
    },
    
    /**
    * 方法说明： 创建新的customCatViewWidget对象
    * @method Create
    * @return {object} 返回customCatViewWidget对象
    */
    Create: function() {
        var self = Object.create(this);
        return self;
    },

    /**
    * 方法说明： 根据参数params的设定画CatList
    * @method Draw
    * @param {object} params 如： customCatViewWidget.configSetting
    */
    Draw: function(params) {
        if(params){
            this.Clear();
            this.SetConfig(params);
            this.Remove();
            this.DrawCatList();
            this.DrawCatItem();
            this.SetDefaultCat();
            
        }
    },
    
    /**
    * 方法说明： 设定customCatViewWidget.configSetting的值
    * @method SetConfig
    * @param {object} params 如： customCatViewWidget.configSetting
    */
    SetConfig: function(params) {
        if(params){
            this.configSetting.divId = params.divId;
            this.configSetting.catViewId = params.divId + "-catView-" + this.GetRandomNum() + "-group";
            this.configSetting.selectedCatOnClick = params.selectedCatOnClick;
            this.configSetting.isMultiselect = params.isMultiselect;
            this.configSetting.rootCat = (params.rootCat && params.rootCat.length > 0)?params.rootCat.split(","):[];
            this.configSetting.catData = params.catData;
            this.configSetting.selectedCatId = params.selectedCatId;
            this.configSetting.isFirstCatAsDefault = params.isFirstCatAsDefault;
        }
    },
    
    /**
    * 方法说明： 删除CatView
    * @method Remove
    */
    Remove: function() {
        var customCatViewObj = this;
        $("#"+customCatViewObj.configSetting.catViewId).remove();
    },
 
    /**
    * 方法说明： 画CatList(即列表元素的父标签ul)
    * @method DrawCatList
    */
    DrawCatList: function() {
        var customCatViewObj = this;
        $.each(customCatViewObj.configSetting.catData, function(catIndex, cat){
            if( customCatViewObj.configSetting.rootCat.length === 0 || $.inArray(cat.ItemCatId, customCatViewObj.configSetting.rootCat) > -1 ){
                var ul = $('<ul class="product-cat" id="'+customCatViewObj.configSetting.catViewId+'">'),
                    li = $("<li>"),
                    label = $('<label class="col-md-2" catId="'+cat.ItemCatId+'">'+cat.ItemCatName+':</label>'),
                    childItem = (cat.ItemCatNodeList)?customCatViewObj.DrawCatItem(cat.ItemCatNodeList.ItemCat):null;

                li.append(label);
                li.append(childItem);
                ul.append(li);
                $("#"+customCatViewObj.configSetting.divId).append(ul);
            }
        });
    },
    
    /**
    * 方法说明： 画DrawCatItem
    * @method DrawCatItem
    */
    DrawCatItem: function(catData) {
        var customCatViewObj = this,
            element = $('<p class="col-md-9 clearfix">');
        $.each(catData, function(catIndex, cat){
            var a = $('<a catId="'+cat.ItemCatId+'">'+cat.ItemCatName+'</a>');
            if(customCatViewObj.configSetting.selectedCatId.length === 0 && catIndex === 0 && customCatViewObj.configSetting.isFirstCatAsDefault) customCatViewObj.configSetting.selectedCatId = [cat.ItemCatId];
            customCatViewObj.BindCatItemEvent(a);
            element.append(a);
        });
        return element;
    },
    
    /**
    * 方法说明： 绑定单击分类的事件，包括click
    * @method BindCatItemEvent
    * @param {object} element
    */
    BindCatItemEvent: function(element) {
        var customCatViewObj = this;
        var catId = $(element).attr("catId");
        $(element).unbind();
        $(element).bind({
            click: function() {
                if(!customCatViewObj.configSetting.isFirstCatAsDefault || customCatViewObj.configSetting.selectedCatId.length !== 1 || customCatViewObj.configSetting.selectedCatId[0] !== catId){
                    customCatViewObj.SetSelectedCatStyle(catId);
                    customCatViewObj.SetSelectedCatIds();
                    customCatViewObj.configSetting.selectedCatOnClick();
                }
            }
        });
    },
    
    /**
    * 方法说明： 修改选择分类后的显示样式和是否多选
    * @method SetSelectedCatStyle
    * @param {int} catId
    */
    SetSelectedCatStyle: function(catId) {
        var customCatViewObj = this;
        if( $('#'+customCatViewObj.configSetting.catViewId+' > li > p > a[catId="'+catId+'"]').hasClass('selectedCat') ){
            $('#'+customCatViewObj.configSetting.catViewId+' > li > p > a[catId="'+catId+'"]').removeClass("selectedCat");
        }
        else{
            if(customCatViewObj.configSetting.isMultiselect){
                $('#'+customCatViewObj.configSetting.catViewId+' > li > p > a[catId="'+catId+'"]').addClass("selectedCat");
            }
            else{
                $('#'+customCatViewObj.configSetting.catViewId+' > li > p > a').removeClass("selectedCat");
                $('#'+customCatViewObj.configSetting.catViewId+' > li > p > a[catId="'+catId+'"]').addClass("selectedCat");
            }
        }
    },

    /**
    * 方法说明： 设定选择分类后configSetting.selectedCatId的值
    * @method SetSelectedCatIds
    */
    SetSelectedCatIds: function() {
        var customCatViewObj = this;
        var selectObj = $("#"+customCatViewObj.configSetting.catViewId+" > li > p > .selectedCat");
        customCatViewObj.configSetting.selectedCatId = (selectObj.length>0)?[]:"";
        $.each(selectObj, function(selectIndex, item){
            var catId = $(item).attr("catId");
            customCatViewObj.configSetting.selectedCatId.push(catId);
        });
    },
    
    /**
    * 方法说明： 还原configSetting为默认
    * @method Clear
    */
    Clear: function() {
        this.configSetting = {
            divId:"",
            catViewId:"",
            selectedCatOnClick:null,
            isMultiselect:false,
            catData:[],
            selectedCatId:[]
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
    * 方法说明： 设定默认分类选项
    * @method SetDefaultCat
    * @return {int}
    */
    SetDefaultCat: function() {
       var customCatViewObj = this;
       if(customCatViewObj.configSetting.isFirstCatAsDefault){
            customCatViewObj.SetSelectedCatStyle(customCatViewObj.configSetting.selectedCatId[0]);
            customCatViewObj.SetSelectedCatIds();
            customCatViewObj.configSetting.selectedCatOnClick();
        }
   }
};