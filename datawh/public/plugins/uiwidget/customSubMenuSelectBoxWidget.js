/* 
 * 控件： customSubMenuSelectBoxWidget
 * 说明:  画多级分类按钮
 * 版本： 1.0
 * 日期： 2017-05-02
 * 作者： Raymond.Lui
 * 
 * configSetting说明：
 * configSetting = {
        divId:"",                                                   //必需，父标签Id
        viewId:"",                                                 
        title:"",                                                   //必需，标题
        selectedItemsId:[],                                         //可选，勾选项。如：[20200,20300]
        isMultipleSelect:""                                         //可选，是否多选。如：true, false
        selectData:{"ItemList":[
                {"ItemId":"1","ItemName":"1","SubItem":[
                        {"ItemId":"11","ItemName":"1-1","Checked":"true"},
                        {"ItemId":"12","ItemName":"1-2","Checked":"false"}
                ]
            },
                {"ItemId":"2","ItemName":"2","SubCat":[
                        {"ItemId":"21","ItemName":"2-1","Checked":"false"},
                        {"ItemId":"22","ItemName":"2-2","Checked":"true"}
                ]
            }]
        }
    }

 * 调用例子：
 * var configSetting = {};
 * var csmsbw = customSubMenuSelectBoxWidget.Create();
 *     csmsbw.Draw(configSetting);
 */

var customSubMenuSelectBoxWidget = {
    debug:false,
    configSetting:{
        title:"",
        divId:null,
        viewId:null,
        selectedItemsId:[],
        selectData:null,
        isMultipleSelect:true
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
            this.DrawSelectBoxGroup("false");
            this.SetSelectedData();
            this.SelectOnChange();
        }
    },
    
    /**
    * 方法说明： 更新CatList
    * @method ReDraw
    * @param {object} params 如： customCatViewWidget.configSetting
    */
    ReDraw: function(params) {
        if(params){
            var customSubMenuObj = this;
            customSubMenuObj.configSetting.selectData = params.selectData;
            customSubMenuObj.configSetting.selectedItemsId = params.selectedItemsId;
            customSubMenuObj.configSetting.isMultipleSelect = (params.isMultipleSelect)?true:params.isMultipleSelect;
            this.DrawSelectBoxGroup("true");
            this.SetSelectedData();
            this.SelectOnChange();
        }
    },
    
    /**
    * 方法说明： 设定customCatViewWidget.configSetting的值
    * @method SetConfig
    * @param {object} params 如： customCatViewWidget.configSetting
    */
    SetConfig: function(params) {
        if(params){
            this.configSetting.title = (params.title)?params.title:'';
            this.configSetting.divId = params.divId;
            this.configSetting.viewId = params.divId + "-submenuSelectBox-" + this.GetRandomNum() + "-group";
            this.configSetting.selectData = params.selectData;
            this.configSetting.selectedItemsId = params.selectedItemsId;
            this.configSetting.isMultipleSelect = (params.isMultipleSelect)?true:params.isMultipleSelect;
        }
    },
    
    /**
    * 方法说明： 画CatList(即列表元素的父标签ul)
    * @method DrawCatList
    */
    DrawSelectBoxGroup: function(isReDraw) {
        var customSubMenuObj = this;
        
        if(!isReDraw || isReDraw === "false"){
            var group = '<div class="form-group" id="'+customSubMenuObj.configSetting.viewId+'">'+'</div>';
            $("#"+customSubMenuObj.configSetting.divId).append(group);
        }
        else if(isReDraw === "true"){
            $("#"+customSubMenuObj.configSetting.viewId).empty();
        }
        
        var content = "";                    
        if(customSubMenuObj.configSetting.title !== ""){
            content+= '<label class="control-label" for="'+customSubMenuObj.configSetting.viewId+'">'+customSubMenuObj.configSetting.title+'：<small></small></label>';
        }
        content+='<div class="dropdown">' + this.DrawSelectBox() + '</div>';
        
        $("#"+customSubMenuObj.configSetting.viewId).append(content);
        $('#'+customSubMenuObj.configSetting.divId+"-submenu").submenupicker();
        if(customSubMenuObj.configSetting.selectedItemsId.length === 1 && customSubMenuObj.configSetting.selectedItemsId[0] == 0){
            $("#"+customSubMenuObj.configSetting.viewId).hide();
        }
    },
    
    SelectOnChange: function() {
        var customSubMenuObj = this;
        $("#"+customSubMenuObj.configSetting.viewId+" li > input").change(function(event) {
            customSubMenuObj.SetMultipleSelected();
            customSubMenuObj.SetSelectedData();
        });
    },
    
    /**
    * 方法说明： 勾选改变时显示更新后的值
    * @method SetSelectedData
    */
    SetSelectedData: function() {  
        var customSubMenuObj = this;
        var data = customSubMenuObj.GetSelectedItem();
        var val = "";
        if(data.length > 0){
            $.each(data,function(index,item) {
                val+= item.name+",";
            });
        }
        else{
            val = "未选择";
        }
        $('#'+customSubMenuObj.configSetting.divId+"-submenu").text(val.substr(0, val.length - 1));
    },
    
    /**
    * 方法说明： 勾选改变时显示更新后的值
    * @method SetSelectedData
    */
    SetMultipleSelected: function() { 
        var customSubMenuObj = this;
        if(customSubMenuObj.configSetting.isMultipleSelect === false){
            $("#"+customSubMenuObj.configSetting.viewId+" li > input").prop("checked",false);
            var itemId = $($(event)[0].target).attr("itemId");
            $("#"+customSubMenuObj.configSetting.viewId+" li > input[itemid="+itemId+"]").prop("checked",true);
        }
    },

    /**
    * 方法说明： 画CatList(即列表元素的父标签ul)
    * @method DrawCatList
    */
    DrawSelectBox: function() {
        var customSubMenuObj = this;
        var data = (customSubMenuObj.configSetting.selectData)?customSubMenuObj.configSetting.selectData.ItemList:[];
        var selecteddata = customSubMenuObj.configSetting.selectedItemsId;
        var html = '<button id="'+customSubMenuObj.configSetting.divId+'-submenu" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" data-submenu>未选择 <span class="caret"></span></button>';
        var ul = customSubMenuObj.DrawSelectItem(data,selecteddata);
        return html+ul;
    },
    
    /**
    * 方法说明： 删除CatView
    * @method Remove
    */
    DrawSelectItem: function(data,selecteddata) {
        var customSubMenuObj = this;
        var li = "";
        $.each(data,function(index, item) {
            var checked = "";
            if(item.Checked === "true"){
                checked = "checked";
            }
            else if(selecteddata && selecteddata.length > 0){
                $.each(selecteddata, function(index,selectedCatId) {
                    if(selectedCatId === item.ItemId){
                        checked = "checked";
                    }
                });
            }
            
            if(item.SubItem){
                li+= '<li class="dropdown-submenu">';
                li+= '<input style="position:absolute;margin:7px 0 0 15px" itemId="'+item.ItemId+'" name="'+item.ItemName+'" parentId="'+item.ParentId+'" type="checkbox" '+checked+'>';
                li+= '<a tabindex="'+index+'">'+item.ItemName+'</a>';
                li+= customSubMenuObj.DrawSelectItem(item.SubItem,selecteddata);
                li += '</li>';
            }
            else{
                li+= '<li>';
                li+= '<input style="position:absolute;margin:7px 0 0 15px" itemId="'+item.ItemId+'" name="'+item.ItemName+'" parentId="'+item.ParentId+'" type="checkbox" '+checked+'>';
                li+= '<a tabindex="'+index+'">'+item.ItemName+'</a>';
                li += '</li>';
            }
        });
        
        return '<ul class="dropdown-menu">' +li+ '</ul>';
    },
    
    /**
    * 方法说明： 获取选中的项
    * @method GetSelectedItem
    */
    GetSelectedItem: function() {
        var customSubMenuObj = this;
        var checkList = $("#"+customSubMenuObj.configSetting.viewId+" li > input");
        var selectedItem = [];
        $.each(checkList,function(index,item) {
            var selected = $(item).prop("checked");
            if(selected){
                var itemId = $(item).attr("itemId");
                var name = $(item).attr("name");
                var parentId = $(item).attr("parentId");
                selectedItem.push({id:itemId,name:name,parentId:parentId});
            }
        });
        customSubMenuObj.configSetting.selectedItemsId = selectedItem;
        return selectedItem;
    },
    
    /**
    * 方法说明： 获取选中的项的Id
    * @method GetSelectedItemId
    */
    GetSelectedItemId: function() {
        var customSubMenuObj = this;
        var checkList = $("#"+customSubMenuObj.configSetting.viewId+" li > input");
        var selectedItem = "";
        $.each(checkList,function(index,item) {
            var selected = $(item).prop("checked");
            if(selected){
                var itemId = $(item).attr("itemId");
                selectedItem+= itemId+",";
            }
        });
        return selectedItem.substr(0, selectedItem.length - 1);
    },
    /**
    * 方法说明： 删除CatView
    * @method Remove
    */
    Remove: function() {
        var customSubMenuObj = this;
        $("#"+customSubMenuObj.configSetting.viewId).remove();
    },
    
    /**
    * 方法说明： 还原configSetting为默认
    * @method Clear
    */
    Clear: function() {
        this.configSetting = {
            divId:"",
            viewId:"",
            selectData:[],
            isMultipleSelect:true
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
    }
};