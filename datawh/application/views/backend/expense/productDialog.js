/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var productDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    productId:null,
    productName:"",
    productUnit:""
};

productDialog.Clear = function() {
    productDialog.html = "";
    productDialog.action = null;
    productDialog.dialogObj = null;
    productDialog.productId = null;
    productDialog.productName = "";
    productDialog.productUnit = "";
};

productDialog.SetHtml = function() {
    productDialog.html ='<div class="container-fluid" id="">'+
                            '<form class="form-horizontal" id="productDialog-main-group">'+
                            '</form>'+
                        '</div>';
};

productDialog.AddProduct = function() {
    productDialog.Clear();
    productDialog.SetHtml();
    productDialog.Init("添加");
    productDialog.action = "add";
};

productDialog.EditProduct = function() {
    productDialog.Clear();
    productDialog.SetHtml();
    productDialog.productId = $('#'+setExpense.idPerfix+"-name-combobox").val();
    if(productDialog.productId){
        var productObj = {};
        $.each(setExpense.productList,function(index,product) {
           if(product.ProductId === productDialog.productId) {
               productObj = product;
               return true;
           }
        });

        productDialog.productName = productObj.ProductName;
        productDialog.productUnit = productObj.Unit;
        productDialog.Init("修改");
        productDialog.action = "update";
    }
    else{
        BootstrapDialog.alert("请先选择消费品。");
    }
};

productDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: productDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            productDialog.dialogObj = dialog;
            productDialog.DrawProductName();
            productDialog.DrawProductUnit();
            productDialog.DrawSaveBtn();
            $('#productDialog-main-group').validator();
        }
    }); 
};

productDialog.DrawProductName = function() {
    var params = {
        divId:"productDialog-main-group",
        idPerfix: "productDialog-name",
        value:productDialog.productName,
        title:"商品名称：",
        placeholder:'请输入商品名称. e.g. 利口福全麦馒头(800g)',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

productDialog.DrawProductUnit = function() {
    var params = {
        divId:"productDialog-main-group",
        idPerfix: "productDialog-unit",
        value:productDialog.productUnit,
        title:"单位：",
        placeholder:'请输入单位. e.g. 包/斤',
        type:"text",
        readonly:"",
        required:"required"
    };
    customTextWidget.Draw(params);
};

productDialog.DrawSaveBtn = function() {
    var params = {
        divId:"productDialog-main-group",
        btnId: "productDialog-submit-btn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };
    
    var html = '<div class="form-group">'+
                    '<div class="col-lg-offset-2 col-lg-10">'+
                        '<button type="submit" class="btn btn-success pull-right" id="'+params.btnId+'" >'+params.title+'</button>'+
                    '</div>'+
                '</div>';
    $("#"+params.divId).append(html);
        
    $("#productDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            productDialog.Save();
        }
    });
};

productDialog.Save = function() {
    var saveData = productDialog.GetSaveData();
    $.ajax({
        url: 'ws/SetProduct.php',
        type: 'POST',
        data: saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            var saveResult = json;
            if(saveResult && saveResult.WebService.ResultCode === 200){
                setExpense.RequestProductList();
                setExpense.DrawName(saveResult.WebService.Result);
                productDialog.dialogObj.close();
            }
            else{
                BootstrapDialog.alert(saveResult.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetProduct服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

productDialog.GetSaveData = function() {
    var saveData = {
        format:'json',
        action:productDialog.action,
        name:$("#productDialog-name-inputText").val(),
        unit:$("#productDialog-unit-inputText").val()
    };
    if(productDialog.action === "update"){
        saveData.id = productDialog.productId;
    }
    return saveData;
};