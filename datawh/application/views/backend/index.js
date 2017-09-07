/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var backendHome = {
    debug:false
};

backendHome.RequestCount = function() {
    $.ajax({
        url: 'ws/GetStatistics.php',
        type: 'POST',
        data: {format:"json"},
        dataType: 'json',
        async: false,
        success: function(json) {
            if(json.WebService.FileList.File.length > 0){
                var fileData = json.WebService.FileList.File;
                backendHome.DrawFileInfoGraphic(fileData);
                backendHome.DrawAnalyticsGroup(fileData);
            }
            if(json.WebService.ExpenseCount){
                var fileData = json.WebService.ExpenseCount;
                backendHome.DrawExpenseCount(fileData);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            BootstrapDialog.alert("访问GetStatistics服务异常!" + XMLHttpRequest.responseText);
        }
    });
};

backendHome.GetFileTypeIcon = function(type) {
    var icon = "fa fa-file purple-bg";
    if(type === "3DModel"){
        icon = "fa fa-file green-bg";
    }
    else if(type === "Texture"){
        icon = "fa fa-picture-o emerald-bg";
    }
    else if(type === "Money"){
        icon = "fa fa-money red-bg";
    }
    else if(type === "Book"){
        icon = "fa fa-book gray-bg";
    }
    else if(type === ""){
        icon = "fa fa-money white-bg";
    }
    else if(type === ""){
        icon = "fa fa-money yellow-bg";
    }
    return icon;
};

backendHome.DrawFileInfoGraphic = function(fileData) {
    var html =  "";
    $.each(fileData,function(index,fileType) {
        var icon = backendHome.GetFileTypeIcon(fileType.FileTypeName);
        html+=  '<div class="col-lg-3 col-sm-6 col-xs-12">'+
                    '<div class="main-box infographic-box">'+
                        '<i class="'+icon+'"></i>'+
                        '<span class="headline">'+fileType.FileTypeName+'</span>'+
                        '<span class="value">'+
                            '<span class="timer" data-from="0" data-to="'+fileType.FileCount+'" data-speed="1000" data-refresh-interval="50">'+fileType.FileCount+'</span>'+
                        '</span>'+
                    '</div>'+
                '</div>';
    });
    $("#infographic-box-group").append(html);
};

backendHome.DrawExpenseCount = function(fileData) {
    var html = "";
    $.each(fileData,function(index,item) {
        var icon = backendHome.GetFileTypeIcon("Money");
        html+=  '<div class="col-lg-3 col-sm-6 col-xs-12">'+
                    '<div class="main-box infographic-box">'+
                        '<i class="'+icon+'"></i>'+
                        '<span class="headline">'+index+'</span>'+
                        '<span class="value">'+
                            '<span class="timer" data-from="0" data-to="'+item+'" data-speed="1000" data-refresh-interval="50">'+item+'</span>'+
                        '</span>'+
                    '</div>'+
                '</div>';
    });
    $("#infographic-box-group").append(html);
};

backendHome.Clear = function() {
    $("#infographic-box-group").empty();
};

backendHome.DrawAnalyticsGroup = function(data) {
    $.each(data,function(index,item) {
        var id = item.FileTypeName + "graph-flot-points";
        //var html = '<div class="main-box" ><div class="main-box-header clearfix"><h2>'+item.FileTypeName+'近一个月上传量记录</h2></div><div class="main-box-body clearfix" ><div id="'+id+'" class="row" style="margin:10px 0;height: 400px;"></div></div></div>';        
        var html = '<div class="main-box" ><div class="graph-box emerald-bg"><h2>'+item.FileTypeName+'近一个月上传量记录</h2><div class="graph" id="'+id+'" style="height: 250px;"></div></div></div>';
                    
        $("#graph-flot-points").append(html);
        backendHome.DrawGraph(id,item);
    });
};
    
backendHome.DrawGraphFlot = function(id,data) {
    // graph with points
    if ($('#'+id).length) {
        var name = data.FileTypeName;
        var points = [];
        $.each(data.DayList.Day,function(index,item) {
            var month = item.Day.replace(/-/g, "");
            var num = item.Count;
            points.push([month,num]);
        });
        
        var plot = $.plot($("#"+id),
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
        $("#"+id).bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint !== item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                    showTooltip(item.pageX, item.pageY, "文件：" + item.series.label + " <br>日期： " + parseInt(x) + " <br>數量： " + y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    }
};

backendHome.DrawGraph = function(id,data) {
    var graphData = [];
    $.each(data.DayList.Day,function(index,item) {
        graphData.push({period: item.Day, count: item.Count});
    });
        
    var graphLine = new Morris.Line({
        element: id,
        data: graphData,
        lineColors: ['#ffffff'],
        xkey: 'period',
        ykeys: ['count'],
        labels: ['count'],
        pointSize: 3,
        hideHover: 'auto',
        gridTextColor: '#ffffff',
        gridLineColor: 'rgba(255, 255, 255, 0.3)',
        resize: true
    });
};

$(document).ready(function () {
    backendHome.Clear();
    backendHome.RequestCount();
    $('.infographic-box .value .timer').countTo({});
});