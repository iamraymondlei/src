var setCat = {
    debug:false,
    id:"setCat",
    type:"file",
    catId:null,
    catList:null,
    requestCatUrl:"",
    requestSetUrl:""
};

setCat.RequestCatList = function() {
    $.ajax({
        url: setCat.requestCatUrl,
        type: 'POST',
        data:{
            format: 'json',
            catId: setCat.catId
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.FileCatList && json.WebService.FileCatList.FileCat.length === 1){
                if(json.WebService.FileCatList.FileCat[0].FileCatList){
                    setCat.catList = json.WebService.FileCatList.FileCat[0].FileCatList.FileCat;
                }
                else{
                    setCat.catList = json.WebService.FileCatList.FileCat;
                    setCat.catId = 0;
                }
                $("#setCat-header").text(json.WebService.FileCatList.FileCat[0].FileCatName);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetCatList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

$(document).ready(function () {
    setCat.catId = $.urlParams("get", "catId");
    setCat.SetRequestUrl();
    setCat.RequestCatList();
    setCat.InitNestable();
    setCat.SetMenuFunction();
});

setCat.SetRequestUrl = function() {
	setCat.requestCatUrl = "ws/GetFileCatList.php";
	setCat.requestSetUrl = "ws/SetFileCat.php";
};

setCat.InitNestable = function() {
    var catList = setCat.DrawNestable(setCat.catList);
    $("#setCat-nestable").empty();
    $("#setCat-nestable").append(catList);
    $('#setCat-nestable').nestable({group:1,maxDepth:12}).on('change', function(e){
        var list   = e.length ? e : $(e.target),
        output = list.data('output');
    
        if (window.JSON) {
            var data = window.JSON.stringify(list.nestable('serialize'));
            setCat.ReSort(data);
            output.val(data);//, null, 2));
        }
    });
    // output initial serialised data
    setCat.OnChange($('#setCat-nestable').data('output', $('#nestable-output')));
};

setCat.OnChange = function(e) {
    var list   = e.length ? e : $(e.target),
        output = list.data('output');
    
    if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
    } 
    else {
        output.val('JSON browser support required for this demo.');
    }
};

setCat.DrawNestable = function(catList) {
    var ol = $('<ol class="dd-list">');
    $.each(catList, function(index,cat) {
        var name = cat.FileCatName;
        var id = cat.FileCatNodeId;
        var pId = cat.ParentCatId;
        var child = "";
        if(cat.FileCatList){
            child = setCat.DrawNestable(cat.FileCatList.FileCat);
        }
        
        var li = $('<li class="dd-item  dd3-item" data-id="'+id+'">');
        var drag = $('<div class="dd-handle dd3-handle">Drag</div>');
        var content = $('<div class="dd3-content">');
        var btnGroup = $('<div class="nested-links pull-right">');
        var addBtn = $('<a onClick="setCatDialog.AddFileCat('+id+')" class="nested-link" ><i title="添加" class="fa fa-plus"></i></a>');
        var editBtn = $('<a onClick="setCatDialog.EditFileCat('+id+',\''+name+'\',\''+pId+'\')" class="nested-link"><i title="修改" class="fa fa-pencil"></i></a>');

        $(btnGroup).append(addBtn);
        $(btnGroup).append(editBtn);
        
        $(content).append(name);
        $(content).append(btnGroup);
        
        $(li).append(drag);
        $(li).append(content);
        $(li).append(child);
        $(ol).append(li);
    });
    return ol;
};

setCat.RefreshNestable = function() {
    setCat.RequestCatList();
    setCat.InitNestable();
};

setCat.SetMenuFunction = function() {
    $('#setCat-nestable-menu').on('click', function(e){
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });
};

setCat.ReSort = function(data) {
    $.ajax({
        url: setCat.requestSetUrl,
        type: 'POST',
        data: {
            format:'json',
            action:"sort",
            id:setCat.catId,
            data:data
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            var saveResult = json;
            if(saveResult && saveResult.WebService.ResultCode === 200){
                
            }
            else{
                BootstrapDialog.alert(saveResult.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetCat服务异常!" + XMLHttpRequest.responseText);
        }
    });
};