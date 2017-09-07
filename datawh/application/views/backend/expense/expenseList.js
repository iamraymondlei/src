/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var expenseList = {
    pageSize:100,
    pageIndex:1,
    storeList:null,
    expenseList:null,
    expenseTableWidget:null,
    startDatetime:"2017-01-01 00:00:00",
    endDatetime:"2021-12-31 23:59:59"
};

$(function () {
    expenseList.DrawDateTimeRangeBox();
    
    expenseList.RequestStoreList();
    expenseList.SetupStoreListMultiselect();
    expenseList.expenseTableWidget = customTableGroup.Create();
    expenseList.DrawVisitTable();    
});

expenseList.RequestStoreList = function() {
    $.ajax({
        url: 'ws/GetStoreList.php',
        type: 'POST',
        data:{
            format: 'json'
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            expenseList.storeList = json.WebService.StoreList.Store;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetStoreList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

expenseList.RequestExpenseList = function(requestParams) {
    $.ajax({
        url: 'ws/GetExpenseList.php',
        type: 'POST',
        data: requestParams,
        dataType: 'json',
        async: false,
        success: function(json) {
            expenseList.expenseList = json.WebService;
            $("#expenseList-expense-header-info").text("￥"+json.WebService.Amount);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetExpenseList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

expenseList.SetupStoreListMultiselect = function(){
    var storeList = expenseList.storeList;
    var multiselectObj = $('#expenseList-store-multiselect');
    multiselectObj.empty();
    
    $.each(storeList, function(index, item){
        var sid = item.StoreId;
        var optinHtml = '<option value="'+sid+'">'+item.StoreName+'</option>';
        
        multiselectObj.append(optinHtml);
    });
    //multiselectObj.prepend('<option value="all" selected>全部店铺</option>');
    multiselectObj.multiselect('rebuild');
};

expenseList.DrawDateTimeRangeBox = function() {
    $('#expenseList-datetime').daterangepicker({
        startDate: moment().subtract(29,'days'),
        endDate: moment(),
        minDate: '01/01/2015',
        maxDate: '12/31/2021',
        dateLimit: { days: 60 },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker24Hour: true,
        ranges: {
                '今天': [moment(), moment()],
                '昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '过去7天': [moment().subtract(6, 'days'), moment()],
                '过去30天': [moment().subtract(29, 'days'), moment()],
                '过去半年': [moment().subtract(6,'month').startOf('month'), moment()],
                '当月': [moment().startOf('month'), moment().endOf('month')],
                '过去1月': [moment().subtract(1,'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				'过去2月': [moment().subtract(2,'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
				'过去3月': [moment().subtract(3,'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
				'过去4月': [moment().subtract(4,'month').startOf('month'), moment().subtract(4, 'month').endOf('month')],
				'过去5月': [moment().subtract(5,'month').startOf('month'), moment().subtract(5, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
                applyLabel: '提交',
                cancelLabel: '取消',
                fromLabel: '开始',
                toLabel: '结束',
                customRangeLabel: '自定义时段',
                daysOfWeek: ['日', '一', '二', '三', '四', '五','六'],
                monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
                firstDay: 1
        }
    },
    function(start, end) {
        $('#expenseList-datetime span').html(start.format('YYYY年MM月DD日') + ' - ' + end.format('YYYY年MM月DD日'));
        expenseList.startDatetime = start.format('YYYY-MM-DD HH:mm:ss');
        expenseList.endDatetime = end.format('YYYY-MM-DD HH:mm:ss');
    }
   );
   //Set the initial state of the picker label
   $('#expenseList-datetime span').html(moment().subtract(29,'days').format('YYYY年MM月DD日') + ' - ' + moment().format('YYYY年MM月DD日'));
   expenseList.startDatetime = moment().subtract(29,'days').format('YYYY-MM-DD HH:mm:ss');
   expenseList.endDatetime = moment().format('YYYY-MM-DD HH:mm:ss');
};

expenseList.DrawVisitTable = function() {    
    var beginTime = expenseList.startDatetime;
    var endTime = expenseList.endDatetime;
    var storeId = $('#expenseList-store-multiselect').val();
    
    var requestParams = {
        format:'json',
        page:1,
        pageSize:expenseList.pageSize,
        storeId:(storeId)?storeId.toString():"",
        keyword:$("#expenseList-product-searchBox").val(),
        dateRange:beginTime+","+endTime
    };
    expenseList.RequestExpenseList(requestParams);
    var customTableParams = expenseList.GetCustomTableParams();
    expenseList.expenseTableWidget.Draw(customTableParams);
};

expenseList.GetCustomTableParams = function() {
    var customTableParams = {
        divId:"expenseList-expense-table",
        dataCount:expenseList.expenseList.Count,
        tableData:[],
        coloums:[
            {name:"日期",dataKey:"ExpenseTime"},
            {name:"名称",dataKey:"ProductName"},
            {name:"价格",dataKey:"Price"},
            {name:"数量",dataKey:"Quantity"},
            {name:"单位",dataKey:"Unit"},
            {name:"备注",dataKey:"Description"},
            {name:"操作",dataKey:""}
        ],
        page:{
            pagination:true,
            pageSizeGroup:[20,50,100,200],
            pageSize:expenseList.pageSize,
            pageIndex:expenseList.pageIndex,
            pageSizeOnClick:"expenseList.pageSizeOnClick",
            pageIndexOnClick:expenseList.pageIndexOnClick
        }
    };
        
    $.each(expenseList.expenseList.ExpenseList.Expense, function(itemIndex,item) {
        var coloumData = {
            id:itemIndex,
            coloums:[
                {type:"TEXT",value:item.ExpenseTime,dataKey:"ExpenseTime"},
                {type:"TEXT",value:item.ProductName,dataKey:"ProductName"},
                {type:"TEXT",value:item.Price,dataKey:"Price"},
                {type:"TEXT",value:item.Quantity,dataKey:"Quantity"},
                {type:"TEXT",value:item.Unit,dataKey:"Unit"},
                {type:"TEXT",value:item.Description,dataKey:"Description"},
                {type:"BTNGROUP",value:item.redPacketTimeSlotId,btnList:[
                    {type:"",title:"修改",class:"glyphicon glyphicon-pencil",onClick:"expenseList.editItemOnClick("+item.ExpenseId+")"},
                    {type:"",title:"价格图表",class:"glyphicon glyphicon-signal",onClick:"expenseList.viewAnalysisOnClick("+item.ProductId+")"}
                ]}
            ]
        };
        customTableParams.tableData.push(coloumData);
    });
    
    return customTableParams;
};

expenseList.pageSizeOnClick = function(size) {
    expenseList.pageSize = size;
    var beginTime = $("#expenseList-startDateTime-datetimeText").val();
    var endTime = $("#expenseList-endDateTime-datetimeText").val();
    var storeId = $('#expenseList-store-multiselect').val();
    
    var requestParams = {
        format:'json',
        page:1,
        pageSize:expenseList.pageSize,
        storeId:(storeId)?storeId.toString():"",
        keyword:$("#expenseList-product-searchBox").val(),
        dateRange:beginTime+","+endTime
    };
    expenseList.RequestExpenseList(requestParams);
    var customTableParams = expenseList.GetCustomTableParams();
    expenseList.expenseTableWidget.Draw(customTableParams);
};

expenseList.pageIndexOnClick = function(event,selectedPage) {
    expenseList.pageIndex = selectedPage;
    var beginTime = $("#expenseList-startDateTime-datetimeText").val();
    var endTime = $("#expenseList-endDateTime-datetimeText").val();
    var storeId = $('#expenseList-store-multiselect').val();
    
    var requestParams = {
        format:'json',
        page:expenseList.pageIndex,
        pageSize:expenseList.pageSize,
        storeId:(storeId)?storeId.toString():"",
        keyword:$("#expenseList-product-searchBox").val(),
        dateRange:beginTime+","+endTime
    };
    expenseList.RequestExpenseList(requestParams);
    var customTableParams = expenseList.GetCustomTableParams();
    expenseList.expenseTableWidget.RefreshTabel(customTableParams);
};

expenseList.editItemOnClick = function(itemId) {
    location.href="index.php?p=backend&c=Expense&a=update&uid="+itemId;
};

expenseList.viewAnalysisOnClick = function(itemId) {
    location.href="index.php?p=backend&c=Analysis&a=productPrice&uid="+itemId;
};

expenseList.searchExpenseOnKeypress = function(event) {
    if(event.keyCode === 13){
        var beginTime = $("#expenseList-startDateTime-datetimeText").val();
        var endTime = $("#expenseList-endDateTime-datetimeText").val();
        var storeId = $('#expenseList-store-multiselect').val();
        
        var requestParams = {
            format:'json',
            page:1,
            pageSize:expenseList.pageSize,
            storeId:(storeId)?storeId.toString():"",
            keyword:$("#expenseList-product-searchBox").val(),
            dateRange:beginTime+","+endTime
        };
        expenseList.RequestExpenseList(requestParams);
        var customTableParams = expenseList.GetCustomTableParams();
        expenseList.expenseTableWidget.Draw(customTableParams);
    }
};  