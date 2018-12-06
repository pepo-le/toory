'use strict';

window.onload = function () {
    function ajax(data, url) {
        return $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            data: data,
            success: function success(response) {
                return response;
            },
            error: function error() {
                return false;
            }
        });
    }

    var ctx = document.getElementById('chart');
    var pprev_button = document.getElementById('pprev');
    var prev_button = document.getElementById('prev');
    var next_button = document.getElementById('next');
    var nnext_button = document.getElementById('nnext');

    var api_url = '/histories/period?';
    var breakPoint = 600;
    var period = void 0; // 取得期間
    var page = 0;
    var today = new Date();
    var sdt = new Date(); // 初日の日付(操作対象)
    var req_url = void 0;

    if (document.body.offsetWidth < breakPoint) {
        period = 10;
    } else {
        period = 30;
    }

    // クエリからページ数を取得
    var hash = window.location.search.slice(1).split('&');
    var queries = [];
    var arr = void 0;
    hash.forEach(function (e) {
        arr = e.split('=');
        queries.push(arr[0]);
        queries[arr[0]] = arr[1];
    });

    if (queries.indexOf('page') >= 0 && queries['page'] > 0) {
        page = queries['page'];
    } else {
        page = 1;
    }

    req_url = api_url + 'page=' + page + '&period=' + period;
    // 初日を設定
    sdt.setDate(sdt.getDate() - period * page + 1);

    var chartOptions = {
        maintainAspectRatio: false,
        scales: {
            xAxes: [{
                id: 'x-axis-bar',
                stacked: true,
                beginAtZero: true,
                scaleLabel: {
                    labelString: 'Date'
                },
                ticks: {
                    autoSkip: false
                }
            }],
            yAxes: [{
                id: 'y-axis-bar',
                type: 'linear',
                position: 'right',
                ticks: {
                    beginAtZero: true,
                    max: 1,
                    stepSize: 1
                }
            }]

        }
    };

    // Depend on Chart.js
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                type: 'bar',
                label: 'Todo実行数',
                data: [],
                backgroundColor: 'rgba(45, 235, 166, 0.2)',
                borderColor: 'rgba(45, 235, 162, 1)',
                borderWidth: 1,
                yAxisID: 'y-axis-bar',
                stack: 1
            }, {
                type: 'bar',
                label: 'ルーチン実行数',
                data: [],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                xAxisID: 'x-axis-bar',
                yAxisID: 'y-axis-bar'
            }, {
                type: 'bar',
                label: 'ルーチン総数',
                data: [],
                backgroundColor: 'rgba(225, 62, 155, 0.2)',
                borderColor: 'rgba(142, 42, 235, 1)',
                borderWidth: 1,
                xAxisID: 'x-axis-bar',
                yAxisID: 'y-axis-bar'
            }]
        },
        options: chartOptions
    });

    function renderChart(url, period) {
        pprev_button.setAttribute('disabled', true);
        prev_button.setAttribute('disabled', true);
        next_button.setAttribute('disabled', true);
        nnext_button.setAttribute('disabled', true);

        var loading = document.getElementById('loading');
        var cls = loading.getAttribute('class');
        loading.setAttribute('class', cls + ' c-loading--show');

        ajax({}, url).done(function (response) {
            if ('errors' in response) {
                location.href = '/users/login';
            }

            var day = '';
            var days = [];
            var done_todo = [];
            var done_routine = [];
            var total_routine = [];
            var routine_rating = [];
            var max_count = 0;
            var beforeYear = 0;
            var beforeMonth = 0;
            var taskIndex = 0;
            var taskCount = response.length;

            for (var i = 0; i < period; i++) {
                day = sdt.getFullYear() + '-' + ('0' + (sdt.getMonth() + 1)).slice(-2) + '-' + ('0' + sdt.getDate()).slice(-2);

                var dayLabel = '';

                if (sdt.getFullYear() !== beforeYear) {
                    dayLabel += ('' + sdt.getFullYear()).slice(-2) + '/';
                    beforeYear = sdt.getFullYear();
                }
                if (sdt.getMonth() + 1 !== beforeMonth) {
                    dayLabel += ('0' + (sdt.getMonth() + 1)).slice(-2) + '/';
                    beforeMonth = sdt.getMonth() + 1;
                }

                days[i] = dayLabel + ('0' + sdt.getDate()).slice(-2);

                if (taskIndex < taskCount && response[taskIndex].History.date === day) {
                    done_todo[i] = +response[taskIndex].History.done_todo;
                    done_routine[i] = +response[taskIndex].History.done_routine;
                    total_routine[i] = +response[taskIndex].History.total_routine;
                    if (total_routine !== 0) {
                        routine_rating[i] = Math.round(+response[taskIndex].History.done_routine / +response[taskIndex].History.total_routine * 100);
                    } else {
                        routine_rating[i] = 0;
                    }

                    max_count = Math.max(max_count, +response[taskIndex].History.done_todo, +response[taskIndex].History.total_routine);
                    taskIndex++;
                } else {
                    done_todo[i] = 0;
                    done_routine[i] = 0;
                    total_routine[i] = 0;
                    routine_rating[i] = 0;
                }

                sdt.setDate(sdt.getDate() + 1);
            }

            var data = {
                labels: days,
                datasets: [{
                    type: 'bar',
                    label: 'Todo実行数',
                    data: done_todo,
                    backgroundColor: 'rgba(45, 235, 166, 0.2)',
                    borderColor: 'rgba(45, 235, 162, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-axis-bar',
                    stack: 1
                }, {
                    type: 'bar',
                    label: 'ルーチン実行数',
                    data: done_routine,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    xAxisID: 'x-axis-bar',
                    yAxisID: 'y-axis-bar'
                }, {
                    type: 'bar',
                    label: 'ルーチン総数',
                    data: total_routine,
                    backgroundColor: 'rgba(225, 62, 155, 0.2)',
                    borderColor: 'rgba(142, 42, 235, 1)',
                    borderWidth: 1,
                    xAxisID: 'x-axis-bar',
                    yAxisID: 'y-axis-bar'
                }]
            };

            myChart.options.scales.yAxes[0].ticks.max = max_count;
            myChart.options.scales.yAxes[0].ticks.stepSize = Math.max(Math.round(max_count / 20), 1);

            myChart.data = data;
            myChart.update();
            loading.setAttribute('class', cls);

            pprev_button.removeAttribute('disabled');
            prev_button.removeAttribute('disabled');
            if (page > 1) {
                next_button.removeAttribute('disabled');
                nnext_button.removeAttribute('disabled');
            }
        });
    }

    renderChart(req_url, period);

    // Control Buttons
    document.getElementById('pprev').addEventListener('click', prev);
    document.getElementById('prev').addEventListener('click', prev);
    document.getElementById('next').addEventListener('click', next);
    document.getElementById('nnext').addEventListener('click', next);

    function prev(e) {
        if (e.target.getAttribute('id') === 'pprev') {
            page = page + 2;
        } else {
            page++;
        }

        req_url = api_url + 'page=' + page + '&period=' + period;

        sdt.setTime(today.getTime());
        sdt.setDate(sdt.getDate() - period * page + 1);

        renderChart(req_url, period);
    }

    function next(e) {
        if (e.target.getAttribute('id') === 'nnext') {
            page = Math.max(page - 2, 1);
        } else {
            page = Math.max(page - 1, 1);
        }

        req_url = api_url + 'page=' + page + '&period=' + period;
        sdt.setTime(today.getTime());
        sdt.setDate(sdt.getDate() - period * page + 1);

        renderChart(req_url, period);
    }

    // ウィンドウサイズ変更時はグラフを再描画する
    var resizeTimer = void 0;
    window.onresize = function () {
        pprev_button.setAttribute('disabled', true);
        prev_button.setAttribute('disabled', true);
        next_button.setAttribute('disabled', true);
        nnext_button.setAttribute('disabled', true);

        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            page = 1;

            if (document.body.offsetWidth > breakPoint) {
                period = 30;
            } else {
                period = 10;
            }

            req_url = api_url + 'page=' + page + '&period=' + period;
            sdt.setTime(today.getTime());
            sdt.setDate(sdt.getDate() - period * page + 1);
            renderChart(req_url, period);
        }, 1000);
    };
};
