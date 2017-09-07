/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var productPrice = {
    productList:null,
    priceList:null,
    productId:null,
    idPerfix: "productPrice",
    startDatetime:"2015-01-01 00:00:00",
    endDatetime:"2021-12-31 23:59:59"
};

$(function () {
    productPrice.productId = $.urlParams("get", "uid");
    
    productPrice.RequestProductList();
    productPrice.DrawProductSelectBox();
    
    productPrice.DrawDateTimeRangeBox();
    
    productPrice.RequestExpenseList(); 
    productPrice.DrawMorrisGraph();
});

productPrice.ReDrawAnalytics = function() {
    $('#productPrice-graph-morris-points').empty();
    productPrice.RequestExpenseList(); 
    productPrice.DrawMorrisGraph();
};

productPrice.RequestExpenseList = function(requestParams) {    
    var beginTime = productPrice.startDatetime;
    var endTime = productPrice.endDatetime;
    var productId = $("#productPrice-productName-combobox").val();
    
    $.ajax({
        url: 'ws/GetExpenseList.php',
        type: 'POST',
        data: {
            format:'json',
            page:1,
            pageSize:9999,
            productId:productId,
            dateRange:beginTime+","+endTime
        },
        dataType: 'json',
        async: false,
        success: function(json) {
            productPrice.priceList = json.WebService.ExpenseList.Expense;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetExpenseList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

productPrice.DrawDateTimeRangeBox = function() {
    $('#productPrice-datetime').daterangepicker({
        startDate: moment().subtract(6,'month').startOf('month'),
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
                '当前月': [moment().startOf('month'), moment().endOf('month')],
                '上个月': [moment().subtract(1,'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' 至 ',
        locale: {
                applyLabel: '确定',
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
        $('#productPrice-datetime span').html(start.format('YYYY年MM月DD日') + ' - ' + end.format('YYYY年MM月DD日'));
        productPrice.startDatetime = start.format('YYYY-MM-DD HH:mm:ss');
        productPrice.endDatetime = end.format('YYYY-MM-DD HH:mm:ss');
    }
   );
   //Set the initial state of the picker label
   $('#productPrice-datetime span').html(moment().subtract(6,'month').format('YYYY年MM月DD日') + ' - ' + moment().format('YYYY年MM月DD日'));
   productPrice.startDatetime = moment().subtract(6,'month').format('YYYY-MM-DD HH:mm:ss');
   productPrice.endDatetime = moment().format('YYYY-MM-DD HH:mm:ss');
};

productPrice.RequestProductList = function() {
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
            productPrice.productList = json.WebService.ProductList.Product;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetProductList服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

productPrice.DrawProductSelectBox = function() {
    var productId = productPrice.productId;
    var params = {
        divId:"main-group",
        idPerfix: productPrice.idPerfix+"-productName",
        comboboxId: productPrice.idPerfix+"-productName-combobox",
        value:""
    };
    
    var option = "";
    $.each(productPrice.productList, function(index,product) {
        var active = (productId && productId === product.ProductId)?'selected="selected"':"";
        option += '<option value="'+product.ProductId+'" '+active+'>'+product.ProductName+"/"+product.Unit+'</option>';
    });
    
    var html =  '<select id="'+params.comboboxId+'" class="combobox input-large form-control" name="normal">'+
                    '<option value="" selected="selected">'+params.placeholder+'</option>'+
                    option +    
                '</select>';
    
    $("#productPrice-selectProduct-box").append(html);
    $('#'+params.comboboxId).combobox();
};

productPrice.DrawAnalytics = function() {
    // graph with points
    if ($('#productPrice-graph-flot-points').length) {
        var name = null;
        var points = [];
        $.each(productPrice.priceList,function(index,item) {
            var month = ((item.ExpenseTime).split(" "))[0].replace(/-/g, "");
            var price = item.Price;
                name = item.ProductName;
            points.push([month,price]);
        });
        
        var plot = $.plot($("#productPrice-graph-flot-points"),
                [{data: points, label: name}], {
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {colors: [{opacity: 0.3}, {opacity: 0.3}]}
                },
                points: {show: true,
                    lineWidth: 2
                },
                shadowSize: 0
            },
            grid: {hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 0
            },
            colors: ["#3498db"],
            xaxis: {ticks: 6, tickDecimals: 0},
            yaxis: {ticks: 3, tickDecimals: 0}
        });

        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                'background-color': '#dfeffc',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;
        $("#productPrice-graph-flot-points").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint !== item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    showTooltip(item.pageX, item.pageY, "商品：" + item.series.label + " <br>日期： " + parseInt(x) + " <br>价格： " + y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    }
};

productPrice.DrawMorrisGraph = function() {
    var graphData = [];
    var name = '';
    $.each(productPrice.priceList,function(index,item) {
        var day = item.ExpenseTime.split(" ");
            name = item.ProductName;
        graphData.push({period: day[0], price: item.Price});
    });
    if(graphData.length >0){
        //$("#productPrice-title").text(name);
        $("#productPrice-graph-morris-points").parent(".graph-box").children("h2").text(name);
        var graphLine = new Morris.Line({
            element: 'productPrice-graph-morris-points',
            data: graphData,
            lineColors: ['#ffffff'],
            xkey: 'period',
            ykeys: ['price'],
            labels: ['price'],
            pointSize: 3,
            hideHover: 'auto',
            gridTextColor: '#ffffff',
            gridLineColor: 'rgba(255, 255, 255, 0.3)',
            resize: true
        });
    }
    else{
        $("#productPrice-graph-morris-points").parent(".graph-box").children("h2").text("没有数据记录");
    }
};