$(function () {

    //【一覧画面】ページ読み込み時は更新/削除/メモ出力ボタンを無効化
    $(document).ready(function () {
        if ($("#editProductId").val() == "" &&
            $("#editConditionId").val() == "") {                         //クリックされた商品のidが入るタグ
            $("#editProductBtn").attr('disabled', 'disabled');           //商品更新ボタン無効化
            $("#deleteProductBtn").attr('disabled', 'disabled');         //商品削除ボタン無効化
            $("#editConditionBtn").attr('disabled', 'disabled');         //商品状態更新ボタン無効化
            $("#deleteConditionBtn").attr('disabled', 'disabled');       //商品状態削除ボタン無効化
            $("#export").attr('disabled', 'disabled');                   //メモ出力ボタン無効化
            $('#product_memo').val("");                                  //商品詳細クリア
            $('#condition_menmo').val("");                               //商品状態クリア
        }
    });


    //【一覧画面】商品がクリックされた時の処理
    $(document).on("click", ".productName", function () {
        productId = $(this).next().next().val();                         //商品ID取得
        productMemo = $(this).next().text();                             //商品詳細取得
        exportFileName = $(this).next().next().next().val();             //ファイル出力用文字列を取得
        $('#editProductId').val(productId);                              //hiddenに商品ID設定
        $('#deleteProductId').val(productId);                            //hiddenに商品ID設定
        $('#product_memo').val(productMemo);                             //商品メモを詳細ボックスへ表示
        $('#exportFileName').val(exportFileName);                        //ファイル名設定

        //更新/削除ボタンの有効/無効化処理
        if ($("#editProductId").val() == "") {
            $("#editProductBtn").attr('disabled', 'disabled');
            $("#deleteProductBtn").attr('disabled', 'disabled');
        } else {
            $("#editProductBtn").removeAttr('disabled');
            $("#deleteProductBtn").removeAttr('disabled');
        }
        //メモ出力ボタン有効/無効の更新
        refreshExportBtn();
    });


    //【一覧画面】商品状態がクリックされた時の処理
    $(document).on("click", ".conditionMemo", function () {
        conditionId = $(this).next().next().val();                       //商品状態ID取得
        condition_memo = $(this).next().text();                          //商品詳細取得
        $('#editConditionId').val(conditionId);                          //hiddenに商品ID設定
        $('#deleteConditionId').val(conditionId);                        //hiddenに商品ID設定
        $('#condition_memo').val(condition_memo);                        //商品状態を詳細ボックスへ表示

        //更新/削除ボタンの有効/無効化処理
        if ($("#editConditionId").val() == "") {
            $("#editConditionBtn").attr('disabled', 'disabled');
            $("#deleteConditionBtn").attr('disabled', 'disabled');
        } else {
            $("#editConditionBtn").removeAttr('disabled');
            $("#deleteConditionBtn").removeAttr('disabled');
        }
        //メモ出力ボタン有効/無効の更新
        refreshExportBtn();
    });


    //【一覧画面】メモ出力ボタンがクリックされた時の処理
    $("#export").on('click', function () {
        exportFile();
    });


    //【一覧画面】ハイライト管理
    controlHighlight('.productName');                                   //商品名のハイライト管理
    controlHighlight('.conditionMemo');                                 //商品状態のハイライト管理

    //【一覧画面】商品一覧のマウスオーバーと商品クリックのハイライト処理
    function controlHighlight(className) {
        var onHighlightFlg = false;                                     //ハイライト中の行が｢mouseleave｣イベントを無視するかの判断用

        //商品名クリックでハイライト処理
        $(document).on("click", className, function () {
            $(className).removeClass("alert-primary");
            $(this).addClass("alert-primary");
            onHighlightFlg = true;
        });

        //マウスオーバーでハイライトON
        $(document).on('mouseenter', className, function () {
            if ($(this).hasClass("alert-primary")) {
                onHighlightFlg = true;
            } else {
                $(this).addClass("alert-primary");
            }
        });
        //マウスリー部でハイライトOFF
        $(document).on('mouseleave', className, function () {
            if (onHighlightFlg) {
                onHighlightFlg = false;
            } else {
                $(this).removeClass("alert-primary");
            }
        });
    }


    //【一覧画面】Ajaxの通信順序の制御用
    var controlAjaxCommunicate = $.get("");

    //【一覧画面】入力値のリアルタイム検索(Ajax処理)
    $('#searchValue').on('input', checkAjaxCommunicateFunc);

    //【一覧画面】Ajax通信が完了しているかチェック
    function checkAjaxCommunicateFunc() {
        $.when(controlAjaxCommunicate)
            .done(function () {
                searchValueFunc();
            })
            .fail(function () {
                console.log("通信エラーが発生しました");
            });
    }

    //【一覧画面】検索処理
    function searchValueFunc() {
        //検索欄のワードを取得(両端の空白は削除)
        var searchValue = $('#searchValue').val().trim();

        controlAjaxCommunicate = $.ajax({
            url: './ajax_products.php',
            type: 'POST',
            data: {
                'searchValue': searchValue
            }
        })
            // Ajaxリクエストが成功した時発動
            .done((data) => {
                $('#products').html(data);                              //商品一覧の更新
                $("#editProductId").val("");                            //選択商品のhiddenタグの値クリア
                $("#deleteProductId").val("");                          //選択商品のhiddenタグの値クリア
                $('#product_memo').val("");                             //商品詳細画面のクリア
                $("#editProductBtn").attr('disabled', 'disabled');      //更新ボタン無効化
                $("#deleteProductBtn").attr('disabled', 'disabled');    //削除ボタン無効化
                refreshExportBtn();                                     //メモ出力ボタン有効/無効の更新
                reloadPagingProduct();                                  //ページングの再描画
            })
            // Ajaxリクエストが失敗した時発動
            .fail((data) => {
                alert("検索に失敗しました");
            })
            // Ajaxリクエストが成功・失敗どちらでも発動
            .always((data) => {
            });
    }


    //【一覧画面】クリアボタン処理
    $('#clearBtn').on('click', function () {
        $('#searchValue').val("");
        searchValueFunc() //検索処理
    });


    //【一覧画面】商品のページング設定
    if ($('#pagingProducts').length) {
        var $pagingProducts = $('#pagingProducts');
        var productDefaultOpts = {
            totalPages: calculatePageCount($('#productsTotalCount').val(), 100),
            visiblePages: 9,
            initiateStartPageClick: false,
            first: "最初",
            prev: "前へ",
            next: "次へ",
            last: "最後",
            onPageClick: function (event, page) {
                $.ajax({
                    url: './ajax_products.php',
                    type: 'POST',
                    data: {
                        'page': page
                    }
                })
                    // Ajaxリクエストが成功した時発動
                    .done((data) => {
                        $('#products').html(data);                              //商品一覧の更新
                        $("#editProductId").val("");                            //選択商品のhiddenタグの値クリア
                        $("#deleteProductId").val("");                          //選択商品のhiddenタグの値クリア
                        $('#product_memo').val("");                             //詳細画面のクリア
                        $("#editProductBtn").attr('disabled', 'disabled');      //更新ボタン無効化
                        $("#deleteProductBtn").attr('disabled', 'disabled');    //削除ボタン無効化
                        refreshExportBtn();                                     //メモ出力ボタン有効/無効の更新
                    })
                    // Ajaxリクエストが失敗した時発動
                    .fail((data) => {
                        alert("データの取得に失敗しました");
                    })
                    // Ajaxリクエストが成功・失敗どちらでも発動
                    .always((data) => {
                    });
            }
        };
        $pagingProducts.twbsPagination(productDefaultOpts);
        //ページングの再描画(総登録数が1ページの上限表示数以下の場合、ページングを非表示にする)
        reloadPagingProduct();
    }


    //【一覧画面】商品一覧のページングタグ再読み込み
    function reloadPagingProduct() {
        var totalPages = calculatePageCount($('#productsTotalCount').val(), 100);
        var currentPage = 1;
        $pagingProducts.twbsPagination('destroy');

        if ($('#productsTotalCount').val() > 100) {
            $pagingProducts.twbsPagination($.extend({}, productDefaultOpts, {
                startPage: currentPage,
                totalPages: totalPages
            }));
        }
    }


    //【一覧画面】取得データ数からページング表示数を計算
    function calculatePageCount(totalCount, splitNum) {

        //合計0件時の0割対策
        if (totalCount == 0) {
            totalCount = 1;
        }

        if (totalCount % splitNum === 0) {
            var totalPages = totalCount / splitNum;
        } else {
            var totalPages = (totalCount / splitNum) + 1;
        }
        return totalPages;
    }


    //【一覧画面】商品状態のページング設定用
    var conditionTabCurrentPage = [];                                                                             //タブ移動時。他のタブのカレントページ保持
    var activeTabConditionId = $('#activeTabConditionId').val();                                                  //選択タブのコンディションIDを保持
    var selectTabTotalPagesNum = calculatePageCount($('#conditionTotalNameWithId' + activeTabConditionId).val(), 20); //選択タブの表示ページ数

    //【一覧画面】商品状態のページング設定
    if ($('#pagingConditions').length) {
        var $pagingConditions = $('#pagingConditions');
        var conditionDefaultOpts = {
            totalPages: selectTabTotalPagesNum,
            visiblePages: 9,
            initiateStartPageClick: false,
            first: "最初",
            prev: "前へ",
            next: "次へ",
            last: "最後",
            onPageClick: function (event, page) {
                //選択タブの現在のカレントページ保持
                conditionTabCurrentPage[activeTabConditionId] = page;
                $.ajax({
                    url: './ajax_conditions.php',
                    type: 'POST',
                    data: {
                        'condition_id': activeTabConditionId,
                        'page': page,
                    }
                })
                    // Ajaxリクエストが成功した時発動
                    .done((data) => {
                        $('#conditions').html(data);                              //商品状態一覧の更新
                        $("#editConditionId").val("");                            //選択商品状態メモのhiddenタグ値クリア
                        $("#deleteConditionId").val("");                          //選択商品状態メモのhiddenタグ値クリア
                        $('#condition_memo').val("");                             //商品状態メモのクリア
                        $("#editConditionBtn").attr('disabled', 'disabled');      //更新ボタン無効化
                        $("#deleteConditionBtn").attr('disabled', 'disabled');    //削除ボタン無効化
                        refreshExportBtn();                                       //メモ出力ボタン有効/無効の更新
                    })
                    // Ajaxリクエストが失敗した時発動
                    .fail((data) => {
                        alert("データの取得に失敗しました");
                    })
                    // Ajaxリクエストが成功・失敗どちらでも発動
                    .always((data) => {
                    });
            }
        };
        $pagingConditions.twbsPagination(conditionDefaultOpts);
        //ページングの再描画(総登録数が1ページの上限表示数以下の場合、ページングを非表示にする)
        reloadPagingCondition(activeTabConditionId, conditionTabCurrentPage[activeTabConditionId]);
    }


    //【一覧画面】商品状態ボタンクリック(Ajax処理)
    $('.nav-link').on('click', function () {
        //タブクリック時に選択タブを保存
        activeTabConditionId = $(this).next().val();

        if (conditionTabCurrentPage[activeTabConditionId] == null) {
            //各タブの最初のページ数
            conditionTabCurrentPage[activeTabConditionId] = 1;
        }

        $.ajax({
            url: './ajax_conditions.php',
            type: 'POST',
            data: {
                'condition_id': activeTabConditionId,
                'page_num': conditionTabCurrentPage[activeTabConditionId],
            }
        })
            // Ajaxリクエストが成功した時発動
            .done((data) => {
                $('#conditions').html(data);
                $("#editConditionId").val("");                                 //選択商品状態メモのhiddenタグ値クリア
                $("#deleteConditionId").val("");                               //選択商品状態メモのhiddenタグ値クリア
                $('#condition_memo').val("");                                  //商品状態メモのクリア
                $("#editConditionBtn").attr('disabled', 'disabled');           //更新ボタン無効化
                $("#deleteConditionBtn").attr('disabled', 'disabled');         //削除ボタン無効化
                refreshExportBtn();                                            //メモ出力ボタン有効/無効の更新
                reloadPagingCondition(activeTabConditionId, conditionTabCurrentPage[activeTabConditionId]); //ページングの再描画
            })
            // Ajaxリクエストが失敗した時発動
            .fail((data) => {
                alert("データの取得に失敗しました");
            })
            // Ajaxリクエストが成功・失敗どちらでも発動
            .always((data) => {
            });
    });


    //【一覧画面】商品状態一覧のページングタグ再読み込み
    function reloadPagingCondition(activeTabConditionId, currentPageNum) {
        if (currentPageNum == null) {
            currentPageNum = 1;
        }

        var conditionTotalNameWithId = "#conditionTotalNameWithId" + activeTabConditionId;
        var conditionTotalCount = $(conditionTotalNameWithId).val();

        var totalPages = calculatePageCount(conditionTotalCount, 20);
        var currentPage = currentPageNum;
        $pagingConditions.twbsPagination('destroy');

        if ($(conditionTotalNameWithId).val() > 20) {
            $pagingConditions.twbsPagination($.extend({}, conditionDefaultOpts, {
                startPage: currentPage,
                totalPages: totalPages
            }));
        }
    }


    //【一覧画面】メモ出力ボタンの活性/非活性のリフレッシュ
    function refreshExportBtn() {
        if ($('#editProductId').val() != '' && $('#editConditionId').val() != '') {
            $("#export").removeAttr('disabled');
        } else {
            $("#export").attr('disabled', 'disabled');
        }
    }

    //【一覧画面】メモ出力処理
    function exportFile() {
        var text = '';
        if ($('#product_memo') && $('#condition_memo')) {
            text = $('#product_memo').val() + '\n\n\n' + $('#condition_memo').val();
            
            // 客先からの要請により次行(合計1行)「改行コード(\n) を '<br>\n' に置き換える処理」を追加 
            text = text.replace(/\r?\n/g, '<br>\n');    // Added 2019.0829 by T.Nohara

        }
        // バイナリデータ作成
        var blob = new Blob([text], { type: "text/plain" });
        // デフォルトファイル名
        var fileName = 'productName.txt';
        if ($('#exportFileName').val() != '') {
            fileName = $('#exportFileName').val();
        }
        // IEか他ブラウザかの判定
        if (window.navigator.msSaveBlob) {
            // IEなら独自関数を使います。
            window.navigator.msSaveBlob(blob, fileName);
        }
        else if (window.URL && window.URL.createObjectURL) {
            // for Firefox
            var a = document.createElement("a");
            a.href = window.URL.createObjectURL(blob);
            a.target = '_blank';
            a.download = fileName + ".txt";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
        else {
            // for Chrome
            var a = document.createElement("a");
            a.href = URL.createObjectURL(blob);
            a.target = '_blank';
            a.download = fileName;
            a.click();
        }
    }


    //【一覧画面】トースト表示処理
    if ($('#toast').length) {
        // トーストの表示情報取得
        var toast = $('#toast').val();

        // トースト設定
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        // トースト実行
        Command: toastr["success"](toast);
    }


    //【商品登録画面】入力チェック
    $("#submitProductBtn").on('click', function () {
        var chkSubmit = true;
        // 商品名の必須チェック
        if ($.trim($("#product_name").val()) === "") {
            // メッセージ表示
            $("#submitProductErrMsg").text("商品名は必須です。空白以外の文字列を入力して下さい。");
            chkSubmit = false;
        } else {
            // ボタンクリック後、submitボタン無効化
            $('#submitProductBtn').css('pointer-events', 'none');
            // ボタンクリック後、キャンセルボタン無効化
            $('#cancelBtn').css('pointer-events', 'none');
        }
        return chkSubmit;
    });

    //【商品登録画面】enterキーによるsubmit無効化
    $("input"). keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        } else {
            return true;
        }
    });


    //【商品状態登録画面】入力チェック
    $(".submitConditionBtn").on('click', function () {
        var chkSubmit = true;
        // 商品名の必須チェック
        if ($.trim($("#condition_memo").val()) === "") {
            // メッセージ表示
            $("#submitConditionErrMsg").text("商品状態は必須です。空白以外の文字列を入力して下さい。");
            chkSubmit = false;
        } else {
            // ボタンクリック後、submitボタン無効化
            $('.submitConditionBtn').css('pointer-events', 'none');
            // ボタンクリック後、キャンセルボタン無効化
            $('#cancelBtn').css('pointer-events', 'none');
        }
        return chkSubmit;
    });

    //【商品･商品状態登録画面】キャンセルボタンクリック後、他ボタン無効化
    $("#cancelBtn").on('click', function () {
        $(':submit').css('pointer-events', 'none');
    })

});
