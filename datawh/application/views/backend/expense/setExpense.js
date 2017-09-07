var setExpense = {
    debug:false,
    idPerfix:"setExpense",
    productList:null,
    paymentList:null,
    storeList:null,
    saveData:{
        dateTime : null,
        storeId : null,
        productId: null,
        price : null,
        quantity : null,
        paymentId : null,
        description : null,
        action : 'add',
        format : 'json'
    }
};

setExpense.RequestProductList = function() {
    $.ajax({
        url: 'ws/GetProductList.php',
        type: 'POST',
        data:{
            format: 'json',
            pageSize: 999
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            setExpense.productList = json.WebService.ProductList.Product;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetProductList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setExpense.RequestPaymentList = function() {
    $.ajax({
        url: 'ws/GetPaymentList.php',
        type: 'POST',
        data:{
            format: 'json'
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            setExpense.paymentList = json.WebService.PaymentList.Payment;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetPaymentList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setExpense.RequestStoreList = function() {
    $.ajax({
        url: 'ws/GetStoreList.php',
        type: 'POST',
        data:{
            format: 'json'
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            setExpense.storeList = json.WebService.StoreList.Store;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetStoreList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setExpense.DrawDateTimePicker = function() {
    var datetime = (new Date()).Format("yyyy-MM-dd hh:mm");
    if(setExpense.saveData.dateTime)
        datetime = setExpense.saveData.dateTime;
    else if(localStorage.dateTime)
        datetime = localStorage.dateTime;
        
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-dateTime",
        placeholder: " ",
        value:datetime,
        title:"日期：",
        readonly:"readonly"//readonly
    };
    customDatetimeWidget.Draw(params);
};

setExpense.DrawDateTime = function() {
    if(setExpense.debug) console.log("setExpense.DrawDateTime",setExpense.saveData);
    var vlaue = (new Date()).Format("yyyy-MM-dd hh:mm");
    if(setExpense.saveData.dateTime)
        vlaue = setExpense.saveData.dateTime;
    else if(localStorage.dateTime)
        vlaue = localStorage.dateTime;
    
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-dateTime",
        value:value,
        title:"日期：",
        placeholder:' ',
        type:"text",
        readonly:"readonly",
        required:"required"
    };
    customTextWidget.Draw(params);
};

setExpense.DrawNameGroup = function() {
    var html = '<div class="form-group" id="'+setExpense.idPerfix+"-name"+'">'+'</div>';
    $("#main-group").append(html);
};

setExpense.DrawName = function(productId) {
    if(setExpense.debug) console.log("setExpense.DrawName",setExpense.saveData);
    
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-name",
        comboboxId: setExpense.idPerfix+"-name-combobox",
        value:"",
        title:"消费品：<a href=\"#\" onClick=\"productDialog.AddProduct()\">[添加]</a> <a href=\"#\" onClick=\"productDialog.EditProduct()\">[修改]</a>",
        placeholder:'请选择消费品',
        type:"text",
        readonly:""//readonly
    };
    
    var option = "";
    $.each(setExpense.productList, function(index,product) {
        var active = (productId && productId === product.ProductId)?'selected="selected"':"";
        option += '<option value="'+product.ProductId+'" '+active+'>'+product.ProductName+"/"+product.Unit+'</option>';
    });
    
    var html = 
            '<label class="control-label" for="'+params.idPerfix+'">'+params.title+'</label>'+
            '<select id="'+params.comboboxId+'" class="combobox input-large form-control" name="normal">'+
                '<option value="" selected="selected">'+params.placeholder+'</option>'+
                option +    
            '</select>'+
            '<div class="help-block with-errors"></div>';
    
    $("#"+params.idPerfix).empty();
    $("#"+params.idPerfix).append(html);
    $('#'+params.comboboxId).combobox();
};

setExpense.DrawStore = function() {
    if(setExpense.debug) console.log("setExpense.DrawStore",setExpense.saveData);
    var option = [];
    var storeId = (setExpense.saveData.storeId)?setExpense.saveData.storeId:"";
    if(localStorage.storeId && storeId === ""){
        storeId = localStorage.storeId;
    }
        
    $.each(setExpense.storeList, function(index,store) {
        if(storeId === store.StoreId){
            option.push({name: store.StoreName, id:store.StoreId, selected: 1 });
        }
        else{
            option.push({name: store.StoreName, id:store.StoreId, selected: 0 });
        }
    });
    
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-store",
        placeholder: "",
        value:option,
        title:"商店：<a href=\"#\" onClick=\"storeDialog.AddStore()\">[添加]</a> <a href=\"#\" onClick=\"storeDialog.EditStore()\">[修改]</a>"
    };   
    customSelectBoxWidget.Draw(params);
};

setExpense.DrawPrice = function() {
    if(setExpense.debug) console.log("setExpense.DrawPrice",setExpense.saveData);
    var value = (setExpense.saveData.price)?setExpense.saveData.price:"";
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-price",
        value:value,
        title:"单价：",
        placeholder:'请输入单价. e.g. 12.90',
        type:"text",
        readonly:"",
        required:"required"
    };
    customTextWidget.Draw(params);
    
    $('#'+setExpense.idPerfix+"-price-inputText").change(function() {
        setExpense.ComputAmount();
    });
};

setExpense.DrawQuantity = function() {
    if(setExpense.debug) console.log("setExpense.DrawQuantity",setExpense.saveData);
    var value = (setExpense.saveData.quantity)?setExpense.saveData.quantity:"";
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-quantity",
        value:value,
        title:"数量：",
        placeholder:'请输入数量. e.g. 2/1/1.5',
        type:"text",
        readonly:"",
        required:"required"
    };
    customTextWidget.Draw(params);
    
    $('#'+setExpense.idPerfix+"-quantity-inputText").change(function() {
        setExpense.ComputAmount();
    });
};

setExpense.DrawAmount = function() {
    if(setExpense.debug) console.log("setExpense.DrawAmount",setExpense.saveData);
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-amount",
        value:"",
        title:"总价：",
        placeholder:'0',
        type:"text",
        readonly:"readonly"
    };
    customTextWidget.Draw(params);
};

setExpense.ComputAmount = function() {
    var price = $('#'+setExpense.idPerfix+"-price-inputText").val();
    var quantity = $('#'+setExpense.idPerfix+"-quantity-inputText").val();
    var amount = price * quantity;
    $('#'+setExpense.idPerfix+"-amount-inputText").val(amount);
};

setExpense.DrawPayment = function() {
    if(setExpense.debug) console.log("setExpense.DrawPayment",setExpense.saveData);
    var paymentId = (setExpense.saveData.paymentId)?setExpense.saveData.paymentId:"";
    if(localStorage.paymentId && paymentId === ""){
        paymentId = localStorage.paymentId;
    }
    
    var option = [];
    $.each(setExpense.paymentList, function(index,payment) {
        if(payment.PaymentId === paymentId){
            option.push({name: payment.PaymentMethod, id:payment.PaymentId, selected: 1 });
        }
        else{
            option.push({name: payment.PaymentMethod, id:payment.PaymentId, selected: 0 });
        }
    });
    
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-payment",
        placeholder: "",
        value:option,
        title:"支付方式：<a href=\"#\" onClick=\"paymentDialog.AddPayment()\">[添加]</a> <a href=\"#\" onClick=\"paymentDialog.EditPayment()\">[修改]</a>"
    };   
    customSelectBoxWidget.Draw(params);
};

setExpense.DrawDescription = function() {
    var value = (setExpense.saveData.description)?setExpense.saveData.description:"";
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-description",
        title:"备注：",
        placeholder:'请输入备注. e.g. 万宁88减44',
        value:value
    };
    customTextareaWidget.Draw(params);
};

setExpense.DrawSaveBtn = function() {
    var params = {
        divId:"main-group",
        idPerfix: setExpense.idPerfix+"-saveBtn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };
    
    var html = '<div class="form-group">'+
                    '<div class="col-lg-offset-2 col-lg-10">'+
                        '<button type="submit" class="btn btn-success pull-right" id="setExpense-submit-btn" >'+params.title+'</button>'+
                    '</div>'+
                '</div>';
    $("#"+params.divId).append(html);
    
    $("#main-group").submit(function(e){
        e.preventDefault();
        var productId = $('#'+setExpense.idPerfix+"-name-combobox").val();
        var disabled = $("#setExpense-submit-btn").hasClass('disabled');
        if(!disabled){
            if(productId > 0)
                setExpense.Save();
            else
                BootstrapDialog.alert("请选择消费品");
        }
    });
};

setExpense.Save = function() {
    var saveData = {};
    saveData.dateTime = $('#'+setExpense.idPerfix+"-dateTime-datetimeText").val();
    saveData.storeId = $('#'+setExpense.idPerfix+"-store-selectBox").val();
    saveData.productId = $('#'+setExpense.idPerfix+"-name-combobox").val();
    saveData.price = $('#'+setExpense.idPerfix+"-price-inputText").val();
    saveData.quantity = $('#'+setExpense.idPerfix+"-quantity-inputText").val();
    saveData.paymentId = $('#'+setExpense.idPerfix+"-payment-selectBox").val();
    saveData.description = $('#'+setExpense.idPerfix+"-description-inputTextarea").val();
    saveData.action = setExpense.saveData.action;
    saveData.format = 'json';
    
    if(saveData.action === "update") saveData.id = setExpense.saveData.id;
            
    setExpense.saveData = saveData;
    if(setExpense.debug) console.log("setExpense.Save",setExpense.saveData);
    
    $.ajax({
        url: 'ws/SetExpense.php',
        type: 'POST',
        data:saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            var result = json.WebService.Result;
            if(result > 0 && json.WebService.ResultCode === 200){
                BootstrapDialog.alert("保存成功");
                setExpense.SetLocalSortage(saveData);
                if(setExpense.saveData.action === "add")
                    location.href="index.php?p=backend&c=Expense&a=add";
                else if(setExpense.saveData.action === "update")
                    location.href="index.php?p=backend&c=Expense&a=list";
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetExpense服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

$(document).ready(function () {
    setExpense.Init();
    setExpense.RequestProductList();
    setExpense.RequestPaymentList();
    setExpense.RequestStoreList();
    
    setExpense.DrawDateTimePicker();
    setExpense.DrawStore();
    setExpense.DrawNameGroup();
    setExpense.DrawName(setExpense.saveData.productId);
    setExpense.DrawPrice();
    setExpense.DrawQuantity();
    setExpense.DrawAmount();
    setExpense.ComputAmount();
    setExpense.DrawPayment();
    setExpense.DrawDescription();
    setExpense.DrawSaveBtn();
    
    $('#main-group').validator();
});

setExpense.Init = function() {
    var action = $.urlParams("get", "a");
    var uid = $.urlParams("get", "uid");
    
    if(action === "add"){
        $("#setExpense-title").text("添加消费项");
    }
    else if(action === "update"){
        $("#setExpense-title").text("修改消费项");
        setExpense.RequestExpenseList(uid);
    }
};

setExpense.RequestExpenseList = function(uid) {
    $.ajax({
        url: 'ws/GetExpenseList.php',
        type: 'POST',
        data: {
            format:'json',
            id:uid
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            var data = (json.WebService.ExpenseList.Expense)[0];
            setExpense.saveData = {};
            setExpense.saveData.id = uid;
            setExpense.saveData.dateTime = data.ExpenseTime;
            setExpense.saveData.storeId = data.StoreId;
            setExpense.saveData.productId = data.ProductId;
            setExpense.saveData.price = data.Price;
            setExpense.saveData.quantity = data.Quantity;
            setExpense.saveData.paymentId = data.PaymentId;
            setExpense.saveData.description = data.Description;
            setExpense.saveData.action = 'update';
            setExpense.saveData.format = 'json';
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetExpenseList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

setExpense.SetLocalSortage = function(data) {
    localStorage.clear();
    localStorage.dateTime=data.dateTime;
    localStorage.storeId=data.storeId;
    localStorage.paymentId=data.paymentId;
};