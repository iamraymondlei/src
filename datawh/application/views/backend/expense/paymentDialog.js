/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var paymentDialog = {
    debug:false,
    html:"",
    action:null,
    dialogObj:null,
    paymentId:null,
    paymentMethod:""
};

paymentDialog.Clear = function() {
    paymentDialog.html = "";
    paymentDialog.action = null;
    paymentDialog.dialogObj = null;
    paymentDialog.paymentId = null;
    paymentDialog.paymentMethod = "";
};

paymentDialog.SetHtml = function() {
    paymentDialog.html ='<div class="container-fluid" id="">'+
                            '<form class="form-horizontal" id="paymentDialog-main-group">'+
                            '</form>'+
                        '</div>';
};

paymentDialog.AddPayment = function() {
    paymentDialog.Clear();
    paymentDialog.SetHtml();
    paymentDialog.Init("添加");
    paymentDialog.action = "add";
};

paymentDialog.EditPayment = function() {
    paymentDialog.Clear();
    paymentDialog.SetHtml();
    paymentDialog.paymentId = $('#'+setExpense.idPerfix+"-payment-selectBox").val();
    if(paymentDialog.paymentId){
        var paymentObj = {};
        $.each(setExpense.paymentList,function(index,payment) {
           if(payment.PaymentId === paymentDialog.paymentId) {
               paymentObj = payment;
               return true;
           }
        });

        paymentDialog.paymentMethod = paymentObj.PaymentMethod;
        paymentDialog.Init("修改");
        paymentDialog.action = "update";
    }
    else{
        BootstrapDialog.alert("请先选择店铺。");
    }
};

paymentDialog.Init = function(title) {
    BootstrapDialog.show({
        title: title,
        message: paymentDialog.html,
        draggable: true,
        size: BootstrapDialog.SIZE_WIDE,
        onshown: function(dialog) {
            paymentDialog.dialogObj = dialog;
            paymentDialog.DrawPaymentName();
            paymentDialog.DrawSaveBtn();
            $('#paymentDialog-main-group').validator();
        }
    }); 
};

paymentDialog.DrawPaymentName = function() {
    var params = {
        divId:"paymentDialog-main-group",
        idPerfix: "paymentDialog-name",
        value:paymentDialog.paymentMethod,
        title:"支付方式：",
        placeholder:'请输入支付方式. e.g. 招行银联(5128)',
        type:"text",
        readonly:"",
        required:"required",
        maxLength:100
    };
    customTextWidget.Draw(params);
};

paymentDialog.DrawSaveBtn = function() {
    var params = {
        divId:"paymentDialog-main-group",
        btnId: "paymentDialog-submit-btn",
        value:"",
        title:"保存",
        readonly:""//readonly
    };
    
    var html =  '<div class="form-group">'+
                    '<div class="col-lg-offset-2 col-lg-10">'+
                        '<button type="submit" class="btn btn-success pull-right" id="'+params.btnId+'" >'+params.title+'</button>'+
                    '</div>'+
                '</div>';
    $("#"+params.divId).append(html);
        
    $("#paymentDialog-main-group").submit(function(e){
        e.preventDefault();
        var disabled = $("#"+params.btnId).hasClass('disabled');
        if(!disabled){
            paymentDialog.Save();
        }
    });
};

paymentDialog.Save = function() {
    var saveData = paymentDialog.GetSaveData();
    $.ajax({
        url: 'ws/SetPayment.php',
        type: 'POST',
        data: saveData,
        dataType: 'json',
        async: false,
        success: function(json) {
            var saveResult = json;
            if(saveResult && saveResult.WebService.ResultCode === 200){
                setExpense.RequestPaymentList();
                setExpense.saveData.paymentId = saveResult.WebService.Result;
                setExpense.DrawPayment();
                paymentDialog.dialogObj.close();
            }
            else{
                BootstrapDialog.alert(saveResult.WebService.ResultMessage);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问SetPayment服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

paymentDialog.GetSaveData = function() {
    var saveData = {
        format:'json',
        action:paymentDialog.action,
        name:$("#paymentDialog-name-inputText").val()
    };
    if(paymentDialog.action === "update"){
        saveData.id = paymentDialog.paymentId;
    }
    return saveData;
};