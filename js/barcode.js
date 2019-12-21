///////////////////////блок овтечающий за создание звукового сигнала////////////////////////////
var audioCtx = new(window.AudioContext ||
    window.webkitAudioContext ||
    window.audioContext)();

//All arguments are optional:

//duration of the tone in milliseconds. Default is 500
//frequency of the tone in hertz. default is 440
//volume of the tone. Default is 1, off is 0.
//type of tone. Possible values are sine, square, sawtooth, triangle, and custom. Default is sine.
//callback to use on end of tone
function beep(duration, frequency, volume, type, callback) {
    var oscillator = audioCtx.createOscillator();
    var gainNode = audioCtx.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioCtx.destination);

    if (volume) {
        gainNode.gain.value = volume;
    }
    if (frequency) {
        oscillator.frequency.value = frequency;
    }
    if (type) {
        oscillator.type = type;
    }
    if (callback) {
        oscillator.onended = callback;
    }

    oscillator.start();
    setTimeout(
        function() {
            oscillator.stop();
        },
        duration ? duration : 440
    );
}
////////////////////////////////////////////////////////////////////////////////////////////////
if (document.getElementById("js-btn")) {

    let bcInput = document.getElementById("js-barcode");
    let button = document.getElementById("js-btn");
    let cansel = document.getElementsByName("cansel");
    let fileInput = document.getElementById("js-fileInput");
    let regExp = /\b[0-9]{13}\b/;
    let msg;
    let bcTemp;
    let dt10;
    let dt20;
    let a9210;
    let a9220;
    let a9510;
    let a9520;
    let gaz10;
    let gaz20;
    let dt10Span;
    let dt20Span;
    let a9210Span;
    let a9220Span;
    let a9510Span;
    let a9520Span;
    let gaz10Span;
    let gaz20Span;

    button.onclick = function() {
        //проверка сканируемого кода
        if (regExp.test(bcInput.value)) {
            bcTemp = bcInput.value;

            if (!document.getElementById("js-post").value.includes(bcTemp)) {
                let bcUtput = document.getElementById("js-post").value;

                switch (true) {
                    case /\b2092010[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон Аи-92 - 10 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////
                        a9210 = +document.getElementById("a9210").innerText;
                        a9210 = a9210 + 1;
                        document.getElementById("a9210").innerHTML = a9210;
                        //////////////////////////////////////////////////////
                        a9210Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #FF0000;">' +
                            msg +
                            "</span><br>" +
                            a9210Span +
                            "<br>";
                        break;
                    case /\b2092020[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон Аи-92 - 20 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        a9220 = +document.getElementById("a9220").innerText;
                        a9220 = a9220 + 1;
                        document.getElementById("a9220").innerHTML = a9220;
                        //////////////////////////////////////////////////////

                        a9220Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #FF0000;">' +
                            msg +
                            "</span><br>" +
                            a9220Span +
                            "<br>";
                        break;
                    case /\b2095010[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон Аи-95 - 10 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        a9510 = +document.getElementById("a9510").innerText;
                        a9510 = a9510 + 1;
                        document.getElementById("a9510").innerHTML = a9510;
                        //////////////////////////////////////////////////////

                        a9510Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #008000;">' +
                            msg +
                            "</span><br>" +
                            a9510Span +
                            "<br>";
                        break;
                    case /\b2095020[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон Аи-95 - 20 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        a9520 = +document.getElementById("a9520").innerText;
                        a9520 = a9520 + 1;
                        document.getElementById("a9520").innerHTML = a9520;
                        //////////////////////////////////////////////////////

                        a9520Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #008000;">' +
                            msg +
                            "</span><br>" +
                            a9520Span +
                            "<br>";
                        break;
                    case /\b2055010[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон ДТ - 10 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        dt10 = +document.getElementById("dt10").innerText;
                        dt10 = dt10 + 1;
                        document.getElementById("dt10").innerHTML = dt10;
                        //////////////////////////////////////////////////////

                        dt10Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #0000CD;">' +
                            msg +
                            "</span><br>" +
                            dt10Span +
                            "<br>";
                        break;
                    case /\b2055020[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон ДТ - 20 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        dt20 = +document.getElementById("dt20").innerText;
                        dt20 = dt20 + 1;
                        document.getElementById("dt20").innerHTML = dt20;
                        //////////////////////////////////////////////////////

                        dt20Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #0000CD;">' +
                            msg +
                            "</span><br>" +
                            dt20Span +
                            "<br>";
                        break;
                    case /\b2078010[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон ГАЗ - 10 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        gaz10 = +document.getElementById("gaz10").innerText;
                        gaz10 = gaz10 + 1;
                        document.getElementById("gaz10").innerHTML = gaz10;
                        //////////////////////////////////////////////////////

                        gaz10Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #DAA520;">' +
                            msg +
                            "</span><br>" +
                            gaz10Span +
                            "<br>";
                        break;
                    case /\b2078020[0-9]{6}\b/.test(bcTemp):
                        msg = bcTemp + " " + "-" + " " + "Талон ГАЗ - 20 литров";
                        document.getElementById("js-post").value = bcUtput + bcTemp;
                        //////////////////////////////////////////////////////

                        gaz20 = +document.getElementById("gaz20").innerText;
                        gaz20 = gaz20 + 1;
                        document.getElementById("gaz20").innerHTML = gaz20;
                        //////////////////////////////////////////////////////

                        gaz20Span = document.getElementById("js-scan").innerHTML;
                        document.getElementById("js-scan").innerHTML =
                            '<span style="color: #DAA520;">' +
                            msg +
                            "</span><br>" +
                            gaz20Span +
                            "<br>";
                        break;
                    default:
                        document.getElementById("js-modal-alert").innerHTML =
                            bcInput.value + " " + "<br>" + " " + "Неизвестный талон!!!";
                        bcInput.disabled = true;
                        document.getElementById("js-modal-window").hidden = false;
                        beep();
                }
                bcInput.value = "";
                document.getElementById("js-table").hidden = false;
            } else {
                document.getElementById("js-modal-alert").innerHTML =
                    bcInput.value +
                    " " +
                    "<br>" +
                    " " +
                    "талон с данным штрих-кодом уже был отсканирован!!!";
                bcInput.disabled = true;
                document.getElementById("js-modal-window").hidden = false;
                beep();
                // alert("талон уже был отсканирован");
            }
        } else {
            document.getElementById("js-modal-alert").innerHTML =
                bcInput.value +
                " " +
                "<br>" +
                " " +
                "Отсканирован неверный формат штрих-кода!!!";
            bcInput.disabled = true;
            document.getElementById("js-modal-window").hidden = false;
            beep();
            //alert("отсканирован неверный формат штрих-кода");

        }
    };

    cansel.onclick = function() {
        //отмена сканирования
        msg = "";
        bcTemp = "";
        dt10 = "";
        dt20 = "";
        a9210 = "";
        a9220 = "";
        a9510 = "";
        a9520 = "";
        gaz10 = "";
        gaz20 = "";
        dt10Span = "";
        dt20Span = "";
        a9210Span = "";
        a9220Span = "";
        a9510Span = "";
        a9520Span = "";
        gaz10Span = "";
        gaz20Span = "";
        document.getElementById("js-scan").innerHTML = "";
        document.getElementById("js-post").value = "";
        ocument.getElementById("js-table").hidden = true;
    };

    /////блок скрывающий модальное окно
    var modal = document.querySelector("#modal"),
        modalOverlay = document.querySelector("#modal-overlay"),
        closeButton = document.querySelector("#close-button"),
        openButton = document.querySelector("#open-button");

    closeButton.addEventListener("click", function() {
        document.getElementById("js-modal-window").hidden = true;
        bcInput.disabled = false;
        bcInput.value = "";
        bcInput.focus();
    });
    /////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////
};

function fileRead(files) {
    let file = files[0];
    let reader = new FileReader();
    reader.readAsText(file);
    reader.onload = function(event) {
        let contents = event.target.result;
        let str = contents.replace(/[^0-9]/g, "");
        if ((str.length % 13) == 0) {
            let myArr = str.match(/[0-9]{13}/g);
            let regExp = /\b[0-9]{13}\b/;
            let msg;
            let bcTemp;
            let dt10;
            let dt20;
            let a9210;
            let a9220;
            let a9510;
            let a9520;
            let gaz10;
            let gaz20;
            let dt10Span;
            let dt20Span;
            let a9210Span;
            let a9220Span;
            let a9510Span;
            let a9520Span;
            let gaz10Span;
            let gaz20Span;

            for (let i = 0; i < myArr.length; i++) {

                if (!document.getElementById("js-post").value.includes(myArr[i])) {
                    let bcUtput = document.getElementById("js-post").value;

                    switch (true) {
                        case /\b2092010[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон Аи-92 - 10 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////
                            a9210 = +document.getElementById("a9210").innerText;
                            a9210 = a9210 + 1;
                            document.getElementById("a9210").innerHTML = a9210;
                            //////////////////////////////////////////////////////
                            a9210Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #FF0000;">' +
                                msg +
                                "</span><br>" +
                                a9210Span +
                                "<br>";
                            break;
                        case /\b2092020[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон Аи-92 - 20 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            a9220 = +document.getElementById("a9220").innerText;
                            a9220 = a9220 + 1;
                            document.getElementById("a9220").innerHTML = a9220;
                            //////////////////////////////////////////////////////

                            a9220Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #FF0000;">' +
                                msg +
                                "</span><br>" +
                                a9220Span +
                                "<br>";
                            break;
                        case /\b2095010[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон Аи-95 - 10 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            a9510 = +document.getElementById("a9510").innerText;
                            a9510 = a9510 + 1;
                            document.getElementById("a9510").innerHTML = a9510;
                            //////////////////////////////////////////////////////

                            a9510Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #008000;">' +
                                msg +
                                "</span><br>" +
                                a9510Span +
                                "<br>";
                            break;
                        case /\b2095020[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон Аи-95 - 20 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            a9520 = +document.getElementById("a9520").innerText;
                            a9520 = a9520 + 1;
                            document.getElementById("a9520").innerHTML = a9520;
                            //////////////////////////////////////////////////////

                            a9520Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #008000;">' +
                                msg +
                                "</span><br>" +
                                a9520Span +
                                "<br>";
                            break;
                        case /\b2055010[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон ДТ - 10 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            dt10 = +document.getElementById("dt10").innerText;
                            dt10 = dt10 + 1;
                            document.getElementById("dt10").innerHTML = dt10;
                            //////////////////////////////////////////////////////

                            dt10Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #0000CD;">' +
                                msg +
                                "</span><br>" +
                                dt10Span +
                                "<br>";
                            break;
                        case /\b2055020[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон ДТ - 20 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            dt20 = +document.getElementById("dt20").innerText;
                            dt20 = dt20 + 1;
                            document.getElementById("dt20").innerHTML = dt20;
                            //////////////////////////////////////////////////////

                            dt20Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #0000CD;">' +
                                msg +
                                "</span><br>" +
                                dt20Span +
                                "<br>";
                            break;
                        case /\b2078010[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон ГАЗ - 10 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            gaz10 = +document.getElementById("gaz10").innerText;
                            gaz10 = gaz10 + 1;
                            document.getElementById("gaz10").innerHTML = gaz10;
                            //////////////////////////////////////////////////////

                            gaz10Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #DAA520;">' +
                                msg +
                                "</span><br>" +
                                gaz10Span +
                                "<br>";
                            break;
                        case /\b2078020[0-9]{6}\b/.test(myArr[i]):
                            msg = myArr[i] + " " + "-" + " " + "Талон ГАЗ - 20 литров";
                            document.getElementById("js-post").value = bcUtput + myArr[i];
                            //////////////////////////////////////////////////////

                            gaz20 = +document.getElementById("gaz20").innerText;
                            gaz20 = gaz20 + 1;
                            document.getElementById("gaz20").innerHTML = gaz20;
                            //////////////////////////////////////////////////////

                            gaz20Span = document.getElementById("js-scan").innerHTML;
                            document.getElementById("js-scan").innerHTML =
                                '<span style="color: #DAA520;">' +
                                msg +
                                "</span><br>" +
                                gaz20Span +
                                "<br>";
                            break;
                        default:
                            document.getElementById("js-modal-alert").innerHTML =
                                bcInput.value + " " + "<br>" + " " + "Неизвестный талон!!!";
                            bcInput.disabled = true;
                            document.getElementById("js-modal-window").hidden = false;
                            beep();
                            document.getElementById("js-post").value = "";
                            document.getElementById("js-fileInput").value = "";
                            document.getElementById("js-table").hidden = true;
                            document.getElementById("js-scan").hidden = true;
                            return false;
                    }
                    //bcInput.value = "";
                    document.getElementById("js-table").hidden = false;
                } else {

                    document.getElementById("js-modal-alert").innerHTML =
                        myArr[i] +
                        " " +
                        "<br>" +
                        " " +
                        "в файле содержаться повторяющиеся талоны, операция будет прекращена!";
                    // bcInput.disabled = true;
                    document.getElementById("js-modal-window").hidden = false;
                    beep();
                    document.getElementById("js-post").value = "";
                    document.getElementById("js-fileInput").value = "";
                    document.getElementById("js-table").hidden = true;
                    document.getElementById("js-scan").hidden = true;
                    return false;
                }

            }

            // console.log("делится на 13");
            // console.log(myArr);
        } else {
            alert("файл содержит ошибочные данные! и не может быть обработан!")
            return false;
        }
        //let myArr = contents.split('\n')

        //console.log(str);
    };

}