'use strict';

(function (jQuery) {

    var $ = jQuery;

    $(document).ready(function () {
        // タスクを完了・キャンセルのイベント
        $('#active-todo .task-form').on('submit', { tasktype: 'todo' }, taskDone);
        $('#active-routine .task-form').on('submit', { tasktype: 'routine' }, taskDone);
        $('#done-todo .task-form').on('submit', { tasktype: 'todo' }, taskCancel);
        $('#done-routine .task-form').on('submit', { tasktype: 'routine' }, taskCancel);

        // カレンダーの月を切り替えるイベント
        $('#prev-month').on('click', updateCalendar);
        $('#next-month').on('click', updateCalendar);

        // チェックボックスで有効期限フォームの有効・無効を切り替える
        $('#expire-check0').on('change', toggleExpire);
        $('#reminder-time-check0').on('change', toggleExpire);
        $('#reminder-datetime-check0').on('change', toggleExpire);
        // 最初のチェックを読み込み時に行う
        if ($('#expire-check0').is(':checked')) {
            $('.expire').prop('disabled', false);
        } else {
            $('.expire').prop('disabled', true);
        }
        if ($('#reminder-time-check0').is(':checked')) {
            $('.reminder-time').prop('disabled', false);
        } else {
            $('.reminder-time').prop('disabled', true);
        }
        if ($('#reminder-datetime-check0').is(':checked')) {
            $('.reminder-datetime').prop('disabled', false);
        } else {
            $('.reminder-datetime').prop('disabled', true);
        }

        // フォームのerrorクラスを削除するイベント
        $('input[type=text]').on('change', removeErrorClass);
        $('input[type=password]').on('change', removeErrorClass);
        $('textarea').on('change', removeErrorClass);
        $('input[type=email]').on('change', removeErrorClass);
        $('input[type=radio]').on('change', removeErrorClass);
        $('select').on('change', removeErrorClass);
        $('input[type=checkbox]').on('change', removeErrorClass);

        // タスク一覧のチェックを全て切り替える
        $('#task-delete-all').on('change', checkAllBox);
    });

    // フォームのerrorクラスを削除する
    function removeErrorClass(e) {
        $(e.target).removeClass('form-error');
    }

    // 有効期限フォームの有効・無効を切り替える
    function toggleExpire(e) {
        var target_class = '.' + $(e.target).attr('id').replace('-check0', '');
        if ($(e.target).is(':checked')) {
            $(target_class).prop('disabled', false);
        } else {
            $(target_class).prop('disabled', true);
        }
    }

    // タスク完了チェックの切り替え
    function toggleCheck(e) {
        var target = $(e.target).find('.p-task-check__input');
        if (target.hasClass('checked')) {
            target.removeClass('checked');
        } else {
            target.addClass('checked');
        }
    }

    // タスク完了・キャンセル関係 --------------------------
    function taskDone(e) {
        // ボタンを一時無効に（重複送信を防ぐため）
        $(e.target).find('input').prop('disabled', true);
        $(e.target).find('.c-loading').addClass('c-loading--show');

        var data = {
            Task: {
                id: $(e.target).children('.task-form-id').val(),
                task_type_id: $(e.target).children('.task-form-type').val()
            },
            _Token: {
                key: $(e.target).find('input[name="data[_Token][key]"]').val()
            },
            tasktype: e.data.tasktype
        };

        var url = '/tasks/done';
        var activeBlockId = '#active-' + data.tasktype;
        var doneBlockId = '#done-' + data.tasktype;
        var activeLen = $(activeBlockId).find('tr').length;
        var doneLen = $(doneBlockId).find('tr').length;
        var targetTrId = '#task-' + data.Task.id;
        var targetTitle = $(targetTrId).find('.task-name').html();

        // ajaxでpostのレスポンスを得てから処理
        ajaxMethod(data, url).done(function (response) {
            $(e.target).find('.c-loading').removeClass('c-loading--show');

            response = JSON.parse(response);
            if (response.result.code == 200) {
                // action属性を書き換え
                $(e.target).attr('action', '/tasks/cancel');
                // イベントの付け替え
                $(e.target).off('submit');
                $(e.target).on('submit', { tasktype: data.tasktype }, taskCancel);
                // 文字置き換え
                $(e.target).find('button').html('×');
                // タスクの移動処理
                if (doneLen === 1 && $(doneBlockId).find('tr').hasClass('task-table__none')) {
                    // 完了したタスクが無いとき
                    $(doneBlockId).find('tbody').empty();
                    $(targetTrId).appendTo(doneBlockId);
                } else {
                    // 完了したタスクがあるとき
                    $(doneBlockId).find('tr').each(function (index, element) {
                        var elementTitle = $(element).find('.task-name').html();

                        // タイトルを比較
                        if (elementTitle > targetTitle) {
                            $(targetTrId).insertBefore(element);
                            return false;
                        }

                        // 最後の要素を精査し終わった時
                        if (index === doneLen - 1) {
                            $(targetTrId).appendTo(doneBlockId);
                            return false;
                        }
                    });
                }

                // 未完了のタスクが1つだったとき
                if (activeLen === 1) {
                    $(activeBlockId).find('tbody').empty();
                    if (data.tasktype === 'todo') {
                        $(activeBlockId).find('tbody').html('<tr class="task-table__none p-task-table__none"><td>今日のTodoはありません</td></tr>');
                    } else {
                        $(activeBlockId).find('tbody').html('<tr class="task-table__none p-task-table__none"><td>今日のルーチンはありません</td></tr>');
                    }
                }

                // チェックを切り替える
                toggleCheck(e);

                // ボタンを有効にする
                $(e.target).find('input').prop('disabled', false);
            } else {
                // 更新が正常に終了しなかった時（セッション切れなど）
                location.href = '/';
            }
        });

        return false;
    }

    function taskCancel(e) {
        // ボタンを一時無効に（重複送信を防ぐため）
        $(e.target).find('input').prop('disabled', true);
        $(e.target).find('.c-loading').addClass('c-loading--show');

        var data = {
            Task: {
                id: $(e.target).children('.task-form-id').val(),
                task_type_id: $(e.target).children('.task-form-type').val()
            },
            _Token: {
                key: $(e.target).find('input[name="data[_Token][key]"]').val()
            },
            tasktype: e.data.tasktype
        };

        var url = '/tasks/cancel';
        var activeBlockId = '#active-' + data.tasktype;
        var doneBlockId = '#done-' + data.tasktype;
        var activeLen = $(activeBlockId).find('tr').length;
        var doneLen = $(doneBlockId).find('tr').length;
        var targetTrId = '#task-' + data.Task.id;
        var targetTitle = $(targetTrId).find('.task-name').html();

        // ajaxでpostのレスポンスを得てから処理
        ajaxMethod(data, url).done(function (response) {
            $(e.target).find('.c-loading').removeClass('c-loading--show');

            response = JSON.parse(response);
            if (response.result.code == 200) {
                // action属性を書き換え
                $(e.target).attr('action', '/tasks/done');
                // イベントの付け替え
                $(e.target).off('submit');
                $(e.target).on('submit', { tasktype: data.tasktype }, taskDone);
                // 文字置き換え
                $(e.target).find('button').html('○');
                // タスクの移動処理
                if (activeLen === 1 && $(activeBlockId).find('tr').hasClass('task-table__none')) {
                    // 未完了タスクが無いとき
                    $(activeBlockId).find('tbody').empty();
                    $(targetTrId).appendTo(activeBlockId);
                } else {
                    // 未完了タスクがあるとき
                    $(activeBlockId).find('tr').each(function (index, element) {
                        var elementTitle = $(element).find('.task-name').html();

                        // タイトルを比較
                        if (elementTitle > targetTitle) {
                            $(targetTrId).insertBefore(element);
                            return false;
                        }

                        // 最後の要素を精査し終わった時
                        if (index === activeLen - 1) {
                            $(targetTrId).appendTo(activeBlockId);
                            return false;
                        }
                    });
                }

                // 完了したタスクが1つだったとき
                if (doneLen === 1) {
                    $(doneBlockId).find('tbody').empty();
                    if (data.tasktype === 'todo') {
                        $(doneBlockId).find('tbody').html('<tr class="task-table__none p-task-table__none"><td>今日完了したTodoはありません</td></tr>');
                    } else {
                        $(doneBlockId).find('tbody').html('<tr class="task-table__none p-task-table__none"><td>今日完了したルーチンはありません</td></tr>');
                    }
                }

                // チェックを切り替える
                toggleCheck(e);

                // ボタンを有効にする
                $(e.target).find('input').prop('disabled', false);
            } else {
                // 更新が正常に終了しなかった時（セッション切れなど）
                location.href = '/';
            }
        });

        return false;
    }
    // --------------------------------------------

    /**
     * カレンダーをajaxで更新
     *
     * /histries/calendarにyearとmonthをGETで渡し、
     * カレンダーテーブルのHTMLを取得して、カレンダーを書き換える。
     * 前の月と次の月へのリンクを書き換える。
     *
     * @param event e イベントオブジェクト
     */
    function updateCalendar(e) {
        e.preventDefault();

        var path = $(e.delegateTarget).attr('href').split('?')[0];
        var query = $(e.delegateTarget).attr('href').split('?')[1];

        // クエリを分割
        var queryArray = query.split('&');
        var devidedQuery = [];
        for (var i = 0; queryArray[i]; i++) {
            var tmp = queryArray[i].split('=');
            devidedQuery[tmp[0]] = tmp[1];
        }

        var url = path;
        var data = {
            year: devidedQuery['year'],
            month: devidedQuery['month']
        };

        ajaxMethod(data, url, 'GET').done(function (response) {
            // カレンダーのHTMLを書き換え
            $('#calendar').html(response);

            // リンクの置き換え
            var prev_date = void 0;
            var next_date = void 0;
            if ($(e.delegateTarget).attr('id') === 'prev-month') {
                // 前の月のリンクなら
                prev_date = new Date(devidedQuery['year'], +devidedQuery['month'] - 2);
                next_date = new Date(devidedQuery['year'], +devidedQuery['month']);
            } else {
                // 次の月のリンクなら
                prev_date = new Date(devidedQuery['year'], +devidedQuery['month'] - 2);
                next_date = new Date(devidedQuery['year'], +devidedQuery['month']);
            }
            $('#prev-month').attr('href', path + '?year=' + prev_date.getFullYear() + '&month=' + ('0' + (+prev_date.getMonth() + 1)).slice(-2));
            $('#next-month').attr('href', path + '?year=' + next_date.getFullYear() + '&month=' + ('0' + (+next_date.getMonth() + 1)).slice(-2));

            // イベントを付け直す
            $('#prev-month').on('click', updateCalendar);
            $('#next-month').on('click', updateCalendar);
        }).fail(function () {
            location.href = '/users/login';
        });
    }

    /**
     * 削除チェックボックスを全てチェックする
     *
     * @param e イベントオブジェクト（Check Allチェックボックス）
     */
    function checkAllBox(e) {
        $.each($('#task-table input[type=checkbox]'), function (index, element) {
            if (e.target.checked) {
              element.checked = true;
            } else {
              element.checked = false;
            }
        });
    }

    /**
     * ajax通信を行う
     *
     * @param object data 送信するデータ
     * @param string url 送信先
     * @param string type 送信メソッド（デフォルトはPOST）
     */
    function ajaxMethod(data, url) {
        var type = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'POST';

        return $.ajax({
            url: url,
            type: type,
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
})(jQuery);
