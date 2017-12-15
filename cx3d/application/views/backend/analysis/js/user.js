var User = {
    idPerfix: "scene",
    startDate:"10/01/2017",
    endDate:"12/31/2018"
};

$(function () {
    User.DrawDateTimeRangeBox();
});

User.DrawDateTimeRangeBox = function() {
    $('#user-datetime').daterangepicker({
            startDate: User.startDate,//moment().subtract(1,'month').startOf('month'),
            endDate: User.endDate,//moment(),
            minDate: '08/01/2017',
            maxDate: '12/31/2018',
            dateLimit: { months: 12 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker24Hour: true,
            ranges: {
                '今天': [moment(), moment()],
                '昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '过去7天': [moment().subtract(6, 'days'), moment()],
                '过去30天': [moment().subtract(29, 'days'), moment()]
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
            $('#user-datetime span').html(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
            User.startDate = start.format('MM/DD/YYYY');
            User.endDate = end.format('MM/DD/YYYY');
        }
    );
    //Set the initial state of the picker label
    //$('#user-datetime span').html(moment().subtract(1,'month').format('YYYY年MM月DD日') + ' - ' + moment().format('YYYY年MM月DD日'));
    $('#user-datetime span').html(User.startDate + ' - ' + User.endDate);
};

User.ReDrawAnalytics = function() {
    var url = "index.php?p=backend&c=analysis&a=user";
    url+= "&dt="+User.startDate+","+User.endDate;
    location.href = url;
};

User.ReDrawMap = function() {
    var url = "index.php?p=backend&c=analysis&a=user";
    url+= "&dt="+User.startDate+","+User.endDate;
    location.href = url;
};
