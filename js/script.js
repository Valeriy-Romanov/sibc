$(document).ready(function() {
    //////////////////////////////скрывает\показывает меню "отчеты"//////////////////////////////////
    $("#menu ul").hide();
    $("#menu li span").click(function() {
        $(this)
            .next()
            .slideToggle("normal");
    });

    //////////////////////////////устанавливает ограничения на выбор второй даты периода отчета//////
    $("#date1").blur(function() {
        let a = $("#date1").val();
        $("#date2").datepicker({
            // Можно выбрать только даты, идущие за датой, указанной как начало периода
            minDate: new Date(a)
        });
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////контролирует корректность заполнения периода отчета////////////////
    $("#date2").focus(function() {
        let a = $("#date1").val();
        console.log(a);
        if (a == "undefined" || a == "") {
            alert("Сначала нужно ввести дату начала периода!");
        }
    });
    /*
     *вносим изменения в карточку пользователя
     */
    $("[name=update]").click(function() {
        let userId = this.id;
        let chckbox = "#" + "chckb-" + userId;
        let divBtn = "#" + "btn-" + userId;
        let h4 = "#" + "h4-" + userId;

        let loginId = "#" + "lgn-" + userId;
        let pswId = "#" + "psw-" + userId;
        let fioId = "#" + "fio-" + userId;
        let roleId = "#" + "role-" + userId;

        let login = $(loginId).val();
        let psw = $(pswId).val();
        let fio = $(fioId).val();
        let role = $(roleId).val();

        $.ajax({
            url: "../php/ajax/ajax.php",
            method: "POST",
            data: {
                userId: userId,
                loginUser: login,
                pswUser: psw,
                fioUser: fio,
                roleUser: role
            },
            cash: false,
            beforeSend: function() {
                $("[name=" + userId + "]").hide();
                $("#" + userId + "").show();
                $("#" + userId + "").addClass("beforSend");
                $("#" + userId + "").html(
                    '<span id="span">Подождите, идет обработка запроса...</span>'
                );
            },
            error: function() {
                alert(
                    "Произошла ошибка при отправке запроса. Попробуйте позже или сообщиите администратору!"
                );
                $("#" + userId + "").hide();
                $("[name=" + userId + "]").show();
                $("#" + userId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(loginId).prop("readonly", true);
                $(pswId).prop("readonly", true);
                $(fioId).prop("readonly", true);
                $(roleId).prop("disabled", true);
            },
            success: function(data) {
                $("#" + userId + "").hide();
                $("[name=" + userId + "]").show();
                $("#" + userId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(loginId).prop("readonly", true);
                $(pswId).prop("readonly", true);
                $(fioId).prop("readonly", true);
                $(roleId).prop("disabled", true);
                if (data == true) {
                    $(h4).text(fio);
                    alert("Данные о пользователе " + fio + " успешно изменены!");
                } else {
                    alert("Данные о пользователе " + fio + " не были изменены!");
                }
            }
        });
        $(divBtn).hide();
    });
    /*
     *удаляем данные о пользователе
     */
    $("[name=delete]").click(function() {
        let userId = this.id;
        let p = "#" + "p-" + userId;
        let loginId = "#" + "lgn-" + userId;
        let pswId = "#" + "psw-" + userId;
        let fioId = "#" + "fio-" + userId;
        let roleId = "#" + "role-" + userId;
        let chckbox = "#" + "chckb-" + userId;
        let divBtn = "#" + "btn-" + userId;
        let fio = $(fioId).val();

        $.ajax({
            url: "../php/ajax/ajax.php",
            method: "POST",
            data: { deleteUser: userId },
            cash: false,
            beforeSend: function() {
                $("[name=" + userId + "]").hide();
                $("#" + userId + "").show();
                $("#" + userId + "").addClass("beforSend");
                $("#" + userId + "").html(
                    '<span id="span">Подождите, идет обработка запроса...</span>'
                );
            },
            error: function() {
                alert(
                    "Произошла ошибка при отправке запроса. Попробуйте позже или сообщиите администратору!"
                );
                $("#" + userId + "").hide();
                $("[name=" + userId + "]").show();
                $("#" + userId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(loginId).prop("readonly", true);
                $(pswId).prop("readonly", true);
                $(fioId).prop("readonly", true);
                $(roleId).prop("disabled", true);
            },
            success: function(data) {
                $("#" + userId + "").hide();
                $("[name=" + userId + "]").show();
                $("#" + userId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(loginId).prop("readonly", true);
                $(pswId).prop("readonly", true);
                $(fioId).prop("readonly", true);
                $(roleId).prop("disabled", true);
                if (data == true) {
                    $(p).remove();
                    alert("Данные о пользователе " + fio + " успешно удалены!");
                } else {
                    alert("Данные о пользователе " + fio + " не были удалены!");
                }
            }
        });
        $(divBtn).hide();
    });
    /*
     *вносим изменения в карточку клиента
     */
    $("[name=updateClnt]").click(function() {
        let clntId = this.id;
        let chckbox = "#" + "chckb-" + clntId;
        let divBtn = "#" + "btn-" + clntId;
        let h4 = "#" + "h4-" + clntId;

        let nameId = "#" + "name-" + clntId;

        let clntName = $(nameId).val();

        $.ajax({
            url: "../php/ajax/ajax.php",
            method: "POST",
            data: { clntId: clntId, clntName: clntName },
            cash: false,
            beforeSend: function() {
                $("[name=" + clntId + "]").hide();
                $("#" + clntId + "").show();
                $("#" + clntId + "").addClass("beforSend");
                $("#" + clntId + "").html(
                    '<span id="span">Подождите, идет обработка запроса...</span>'
                );
            },
            error: function() {
                alert(
                    "Произошла ошибка при отправке запроса. Попробуйте позже или сообщиите администратору!"
                );
                $("#" + clntId + "").hide();
                $("[name=" + clntId + "]").show();
                $("#" + clntId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(nameId).prop("readonly", true);
            },
            success: function(data) {
                $("#" + clntId + "").hide();
                $("[name=" + clntId + "]").show();
                $("#" + clntId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(nameId).prop("readonly", true);

                if (data == true) {
                    $(h4).text(clntName);
                    alert("Данные о клиенте " + clntName + " успешно изменены!");
                } else {
                    alert("Данные о клиенте " + clntName + " не были изменены!");
                }
            }
        });
        $(divBtn).hide();
    });
    /*
     *удаляем данные о клиенте
     */
    $("[name=deleteClnt]").click(function() {
        let clntId = this.id;
        let p = "#" + "p-" + clntId;
        let chckbox = "#" + "chckb-" + clntId;
        let divBtn = "#" + "btn-" + clntId;

        let nameId = "#" + "name-" + clntId;

        let clntName = $(nameId).val();
        console.log(p);
        $.ajax({
            url: "../php/ajax/ajax.php",
            method: "POST",
            data: { deleteClnt: clntId },
            cash: false,
            beforeSend: function() {
                $("[name=" + clntId + "]").hide();
                $("#" + clntId + "").show();
                $("#" + clntId + "").addClass("beforSend");
                $("#" + clntId + "").html(
                    '<span id="span">Подождите, идет обработка запроса...</span>'
                );
            },
            error: function() {
                alert(
                    "Произошла ошибка при отправке запроса. Попробуйте позже или сообщиите администратору!"
                );
                $("#" + clntId + "").hide();
                $("[name=" + clntId + "]").show();
                $("#" + clntId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(nameId).prop("readonly", true);
            },
            success: function(data) {
                $("#" + clntId + "").hide();
                $("[name=" + clntId + "]").show();
                $("#" + clntId + "").removeClass("beforSend");
                $(chckbox).prop("checked", false);
                $(nameId).prop("readonly", true);

                if (data == true) {
                    $(p).remove();
                    alert("Данные о клиенте " + clntName + " успешно удалены!");
                } else {
                    alert("Данные о клиенте " + clntName + " не были удалены!");
                }
            }
        });
        $(divBtn).hide();
    });

    /*
     ******слушаем изменение чекбоксов
     */

    $(".js-chck").bind("change", function() {
        let id = $(this).attr("id");
        let clntNameId = "#" + "js-clntName-" + id;
        let invoiceId = "#" + "js-invoice-" + id;
        let btn = "#" + "btn-" + id;
        let clntNameOld = $(clntNameId).attr("name");
        let invoiceOld = $(invoiceId).attr("name");

        if ($(this).prop("checked")) {
            $(clntNameId).attr("contenteditable", "true");
            $(invoiceId).attr("contenteditable", "true");
            $(btn).show();
            $(clntNameId).bind("keydown", function(e) {
                //обработчик нажатия Escape
                if (e.keyCode == 27) {
                    e.preventDefault();
                    $(clntNameId).html(clntNameOld);
                    //возвращаем текст до редактирования
                }
            });
            $(invoiceId).bind("keydown", function(e) {
                //обработчик нажатия Escape
                if (e.keyCode == 27) {
                    e.preventDefault();
                    $(invoiceId).html(invoiceOld);
                    //возвращаем текст до редактирования
                }
            });
        } else {
            $(clntNameId).attr("contenteditable", "false");
            $(invoiceId).attr("contenteditable", "false");
            $(btn).hide();
            $(clntNameId).html(clntNameOld);
            $(invoiceId).html(invoiceOld);
        }
    });
    /*
     *вносим изменения в карточку талона
     */
    $("[name=js-updateTlns]").click(function() {
        let id = $(this).attr("id");
        let clntNameId = "#" + "js-clntName-" + id;
        let invoiceId = "#" + "js-invoice-" + id;
        let clntName = $(clntNameId).text();
        let invoice = $(invoiceId).text();
        let clntNameOld = $(clntNameId).attr("name");
        let invoiceOld = $(invoiceId).attr("name");
        // console.log(clntName);
        // console.log(invoice);
        // console.log(id);
        $.ajax({
            url: "../php/ajax/ajax.php",
            method: "POST",
            data: { talon_id: id, clients_name: clntName, invoice: invoice },
            cash: false,
            beforeSend: function() {
                $("[name=main" + id + "]").hide();
                $("[name=befor" + id + "]").show();
                $("[name=befor" + id + "]").addClass("beforSend");
                $("[name=befor" + id + "]").html(
                    '<span id="span">Подождите, идет обработка запроса...</span>'
                );
            },
            error: function() {
                alert(
                    "Произошла ошибка при отправке запроса. Попробуйте позже или сообщиите администратору!"
                );
                $("[name=befor" + id + "]").hide();
                $("[name=main" + id + "]").show();
                $("[name=befor" + id + "]").removeClass("beforSend");
                $(clntNameId).text(clntNameOld);
                $(invoiceId).text(invoiceOld);
            },
            success: function(data) {
                $("[name=befor" + id + "]").hide();
                $("[name=main" + id + "]").show();
                $("[name=befor" + id + "]").removeClass("beforSend");

                if (data == true) {
                    $("[name=chck" + id + "").prop("checked", false);
                    $("#" + "btn-" + id + "").hide();
                    alert("Данные успешно изменены!");
                    $(clntNameId).attr("contenteditable", "false");
                    $(invoiceId).attr("contenteditable", "false");
                } else {
                    // $("[name=chck" + id + "").prop("checked", true);
                    // $("#" + "btn-" + id + "").show();
                    $(clntNameId).text(clntNameOld);
                    $(invoiceId).text(invoiceOld);
                    alert("Данные не были изменены! " + data + "");
                }
                //console.log(data);
            }
        });

        // console.log(clntName);
        // console.log(invoice);
    });
    /*
     *удаляем данные о талоне
     */
    $("[name=js-deleteTlns]").click(function() {
        let id = $(this).attr("id");
        let clntNameId = "#" + "js-clntName-" + id;
        let invoiceId = "#" + "js-invoice-" + id;
        let clntName = $(clntNameId).text();
        let invoice = $(invoiceId).text();
        let clntNameOld = $(clntNameId).attr("name");
        let invoiceOld = $(invoiceId).attr("name");

        let ansver = confirm("Вы точно хотите удалить талон?");
        if (ansver == false) {
            return false;
        }
        $.ajax({
            url: "../php/ajax/ajax.php",
            method: "POST",
            data: { deleteTlns: id },
            cash: false,
            beforeSend: function() {
                $("[name=main" + id + "]").hide();
                $("[name=befor" + id + "]").show();
                $("[name=befor" + id + "]").addClass("beforSend");
                $("[name=befor" + id + "]").html(
                    '<span id="span">Подождите, идет обработка запроса...</span>'
                );
            },
            error: function() {
                alert(
                    "Произошла ошибка при отправке запроса. Попробуйте позже или сообщиите администратору!"
                );
                $("[name=befor" + id + "]").hide();
                $("[name=main" + id + "]").show();
                $("[name=befor" + id + "]").removeClass("beforSend");
                $(clntNameId).text(clntNameOld);
                $(invoiceId).text(invoiceOld);
            },
            success: function(data) {
                $("[name=befor" + id + "]").hide();
                $("[name=main" + id + "]").show();
                $("[name=befor" + id + "]").removeClass("beforSend");

                if (data == true) {
                    $("[name=main" + id + "]").remove();
                    alert("Данные успешно удалены!");
                } else {
                    alert("Данные не были удалены!");
                }
            }
        });
    });
});
/*********************************************************************************************
 ********************************************************************************************
 ********************************************************************************************/

//////////////////////////////Функция удаляет значения в поле дата при выборе за все время///////
function allchk() {
    if ($("#js-all").prop("checked")) {
        $("#date1").val("");
        $("#date2").val("");
        $("#date1").prop("disabled", true);
        $("#date2").prop("disabled", true);
    } else {
        $("#date1").prop("disabled", false);
        $("#date2").prop("disabled", false);
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////Функция удаляет значения в поле дата при выборе за все время///////
function getInvoice() {
    if ($("#js-clnt-name").val() != "") {
        let clnt_name = $("#js-clnt-name").val();
        $("#js-invoice").load("/php/requer.php", { js_clnt_name: clnt_name });
        $("#js-hide").show();
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////Функция скрывает выбор периода и вида отчета///////
function hideAfterInvoice() {
    if ($("#js-invoice").val() != "") {
        $("#js-hide").hide();
        $("#js-invoice").load("/php/requer.php", { js_clnt_name: clnt_name });
    } else {
        $("#js-hide").show();
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////

///////////функция скрывает\показывает кнопки в карточке пользователя и разбл ввод//////////////
function showButton(n) {
    let chckbox = "#" + "chckb-" + n;
    let login = "#" + "lgn-" + n;
    let psw = "#" + "psw-" + n;
    let fio = "#" + "fio-" + n;
    let role = "#" + "role-" + n;
    let btn = "#" + "btn-" + n;
    let loginDefaultValue = $(login).prop("defaultValue");
    let pswDefaultValue = $(psw).prop("defaultValue");
    let fioDefaultValue = $(fio).prop("defaultValue");

    if ($(chckbox).prop("checked")) {
        //console.log($(role).val());
        $(login).prop("readonly", false);
        $(psw).prop("readonly", false);
        $(fio).prop("readonly", false);
        $(role).prop("disabled", false);
        $(btn).show();
    } else {
        $(role).prop("disabled", true);
        $(login).val(loginDefaultValue);
        $(psw).val(pswDefaultValue);
        $(fio).val(fioDefaultValue);
        role = role + " :first";
        $(role).prop("selected", true);
        $(login).prop("readonly", true);
        $(psw).prop("readonly", true);
        $(fio).prop("readonly", true);
        $(btn).hide();
        //console.log($(role).val());
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////

///////////функция скрывает\показывает кнопки в карточке клиента и разбл ввод//////////////
function showButtonClnt(n) {
    let chckbox = "#" + "chckb-" + n;
    let clntName = "#" + "name-" + n;
    let btn = "#" + "btn-" + n;
    let h4 = "#" + "h4-" + n;

    if ($(chckbox).prop("checked")) {
        //console.log($(role).val());
        $(clntName).prop("readonly", false);
        $(btn).show();
    } else {
        $(clntName).prop("readonly", true);
        $(clntName).val($(h4).text());
        $(btn).hide();
        //console.log($(role).val());
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////