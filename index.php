<?php
session_start();
?>
<html lang="en">
    <head>
        <title>Quiz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <style>
            .html{overflow: hidden;}
        </style>
    </head>
    <body onload="loader()" class="limiter">
        <div id="mained" class="wrap-login100" style="vertical-align: middle;text-align: center;">
            <div id="content" class="wrapasdf" style="display: none;vertical-align: middle;"></div>
        </div>
        <script src="js/bootstrap.min.js" type="168b875311079e67a1884235-text/javascript"></script>
        <script src="js/main.js" type="168b875311079e67a1884235-text/javascript"></script>
        <script src="js/jquery.min.js" ></script>
        <script>
<?php
date_default_timezone_set('Asia/Kolkata');
echo "var serv_curr_time = " . time() . sprintf('%03d', (int) (explode(" ", microtime())[0] * 1000)) . ";";
echo "var start_time = " . (time()+10) . "999;\n";
//echo "var start_time = " . strtotime("2019-11-29 21:14:00") . "000;\n";
echo "var questions = ";
$fh = fopen('quiz_question.json', 'r');
while ($line = fgets($fh)) {
    echo($line);
}
fclose($fh);
?>
        //seconds
        var qus_time = 15;
        var buff_time = 10;
        var res_time = 15;
        var time_adj = serv_curr_time - new Date().getTime();
        function calTime() {
            return (new Date().getTime() + time_adj);
        }
        var no_of_qus = Object.keys(questions).length;
        var per_qus_time = (qus_time + buff_time + res_time) * 1000;
        var quiz_time = no_of_qus * per_qus_time;
        var qus_start = [0];
        var question, score = 0, name = "xyz", timerObject, x, y, serial = 0, answer = "0", total = 0, resultTable;
        var curr_time = calTime();
        console.log("curr_time = " + curr_time);
        console.log("start_time    = " + start_time);
        for (i = 1; i <= no_of_qus; i++) {
            qus_start[i] = start_time + ((i - 1) * per_qus_time);

            console.log("ques_start[" + i + "] = " + qus_start[i]);
        }
        var main = document.getElementById("content");
        function pad(num) {
            var s = num + "";
            while (s.length < 2)
                s = "0" + s;
            return s;
        }
        function loader() {
            var curr_time = calTime();
            if (curr_time <= start_time + quiz_time - per_qus_time) {
                start();
            } else if (curr_time <= start_time + quiz_time) {
                main.innerHTML = "<p class='lead justify-content-center'>All Questions Over<br>Waiting for Participants to submit Answers<br><div class='justify-content-center' id='timer_display'>--</div></p>";
                timer(start_time + quiz_time - curr_time, 4);
                $("#content").fadeIn(500);
                setTimeout(function () {
                    finals();
                }, start_time + quiz_time - curr_time);
            } else {
                finals();
            }
        }
        function start() {
            main.innerHTML = "<h2 class='mb-3'>Bible Quiz</h2><p class='lead justify-content-center'>01<sup>st</sup> Dec 2019<br>Bretheren Assembly<br>Vadodara</p>"
                    + "<br><h2 class='mb-4'>Enter your Name below :</h2>"
                    + "<input name='name' id = 'name' type='text' style='' placeholder='Your Name Here'>"
                    + "<br><br>"
                    + "<input type='button' autofocus value = 'Start' class = 'btn btn-primary' onclick='saveName()'><br><br>"
                    + "<div class='justify-content-center' id='timer_display'>--</div>";
            var curr_time = calTime();
            timer(start_time - curr_time, 1);
            $("#content").fadeIn(500);
        }
        function saveName() {
            console.log('save name');
            clearInterval(x);
            clearInterval(y);
            name = document.getElementById('name').value;
            $.get("quiz_name.php?name=" + name, function (data, status) {
                serial = data;
            });
            var curr_time = calTime();
            console.log('start : ' + start_time + ", curr : " + curr_time);
            if (start_time > curr_time) {
                waitForStart();
            } else {
                quiz();
            }
        }
        function waitForStart() {
            clearInterval(y);
            console.log('waitForStart');
            var curr_time = calTime();
            timer((start_time - curr_time), 2);
            main.innerHTML = "<h5 class='m-7'>Waiting for others to join</h5><div class='justify-content-center' id='timer_display'>--</div>";
            $("#content").fadeIn(500);
            console.log('curr_time : ' + curr_time);
            console.log('start_time : ' + start_time);
        }
        function quiz() {
            clearInterval(y);
            console.log("quiz");
            question = getCurrQus();
            console.log("question : " + question);
            var curr_time = calTime();
            console.log('curr_time : ' + curr_time);
            if (curr_time < start_time + quiz_time) {
                var curr_qus_start = qus_start[question];
                if (curr_time < curr_qus_start + (qus_time * 1000)) {
                    main.innerHTML = "<table width='100%' class = 'mb-3' style='text-align:center'><tr><td width='33%'><h6 class='mb-0'>Name : " + name + "</h6></td></tr>"
                            + "<tr><td width='33%'><h6 class='mb-0'>Question : " + question + "/" + no_of_qus
                            + "</h6></td></tr></table>"
                            + "<h2 class='mb-3'>" + questions["" + question]["question"] + "</h2><div class='justify-content-center' id='timer_display'>--</div><br>"
                            + "<table align='center' class = 'mb-5' >"
                            + "<tr><td style='padding:7px'><input type='radio' value='a' onclick='calScore(" + curr_qus_start + ")' name = 'answer'>" + questions["" + question]["a"] + "</td></tr>"
                            + "<tr><td style='padding:7px'><input type='radio' value='b' onclick='calScore(" + curr_qus_start + ")' name = 'answer'>" + questions["" + question]["b"] + "</td></tr>"
                            + "<tr><td style='padding:7px'><input type='radio' value='c' onclick='calScore(" + curr_qus_start + ")' name = 'answer'>" + questions["" + question]["c"] + "</td></tr>"
                            + "<tr><td style='padding:7px'><input type='radio' value='d' onclick='calScore(" + curr_qus_start + ")' name = 'answer'>" + questions["" + question]["d"] + "</td></tr>"
                            + "<tr><td style='padding:7px'><input type='radio' value='0' hidden name = 'answer' checked='checked'> None of the Above </td></tr></table>"
                            + "<input type='button' onclick='submitAnswer()' class = 'btn btn-primary' align='center' value='Submit' id = 'qus_button'><br>";
                    console.log('curr_qus_start : ' + qus_start[question]);
                    console.log('qus_time : ' + qus_time);
                    console.log('timer : ' + ((curr_qus_start - curr_time) + (qus_time * 1000)));
                    timer(((curr_qus_start - curr_time) + (qus_time * 1000)), 3);
                } else {
                    var content = "Name : " + name + "<br>";
                    content += "Question : " + question + "/" + no_of_qus + "<br><br>";
                    content += "<h4 class='mb-3'>Your Missed the question</h4>";
                    content += "<br>Next Question in : <div class='justify-content-center' id='timer_display'>--</div>";
                    main.innerHTML = content;
                    timer((curr_qus_start + per_qus_time - curr_time), 5);
                }
                $("#content").fadeIn(500);
            } else {
                finals();
            }
        }
        function getCurrQus() {
            var curr_time = calTime();
            var count = 0;
            while (count < no_of_qus) {
                if ((curr_time > ((count * per_qus_time) + start_time)) && (curr_time < (((count + 1) * per_qus_time) + start_time))) {
                    return count + 1;
                }
                count = count + 1;
            }
        }
        function submitAnswer() {
            clearInterval(x);
            answer = "" + $("input[name='answer']:checked").val();
            console.log("submitAnswer");
            var temp = ((((question * per_qus_time) + start_time) - calTime()) - (res_time * 1000));
            var immunity = Math.floor((buff_time / 100) * 80);
            var max = temp - immunity;
            if (temp < immunity) {
                max = 1;
            }
            var random = Math.floor(Math.random() * (+max - +0)) + +0;
            console.log("random : " + random);
            console.log("next question start at : " + ((question * per_qus_time) + start_time));
            main.innerHTML = "<br><br><h4 class='mb-3'>Waiting </h4><div class='justify-content-center' id='timer_display'>--</div><h4 class='mb-3'>for others to submit</4>";
            console.log("max (result in ): " + (max + immunity));
            var corr_answer = questions["" + question]["ans"];
            var ans_text = "None";
            if (corr_answer != answer && answer != "0") {
                score = score * -1;
            }
            if (answer != "0") {
                ans_text = questions["" + question]["" + answer];
            }
            total = total + score;
            var message = "<br><h3 class='mb-3'>Results</h3>"
                    + "<h6 class='mb-2'>Your Answer    : " + ans_text + ""
                    + "<h6 class='mb-2'>Correct Answer : " + questions["" + question]["" + questions["" + question]["ans"]] + "</h6>"
                    + "<h6 class='mb-2'>Score : " + score + "</h6>"
                    + "<h6 class='mb-2'>Total : " + total + "</h6>";
            if (question === no_of_qus) {
                message += "Final Results in : ";
            } else {
                message += "Next Question in : ";
            }
            message += "<div class='justify-content-center' id='timer_display'>--</div><br><div id='tab'></tab><br>";
            answer = "0";
            setTimeout(function () {
                ajaxSubmitAnswer(score);
            }, random);
            timer((max + immunity), 4);
            setTimeout(function () {
                clearInterval(x);
                result(message);
            }, max + immunity);
        }
        function ajaxSubmitAnswer(score) {
            $.get("quiz_answer.php?serial=" + serial + "&score=" + score, function (data, status) {
            });
        }
        function calScore(qus_start) {
            var curr_time = calTime();
            score = Math.floor((qus_start + (qus_time * 1000) - curr_time) / 100);
            console.log("score : " + score);
        }
        function result(message) {
            main.innerHTML = message;
            $.get("quiz_result.php", function (data, status) {
                document.getElementById("tab").innerHTML = data;
            });
            $("#content").fadeIn(500);
            var next_qus = (question * per_qus_time) + start_time - calTime();
            timer(next_qus, 5);
        }
        function finals() {
            main.innerHTML = "<h3 class='mb-3'>Final Results</h3><br><div id='tab'></tab>";
            $.get("quiz_result.php", function (data, status) {
                document.getElementById("tab").innerHTML = data;
            });
            $("#content").fadeIn(500);
        }
        function timer(distance, type) {
            console.log("distance : " + distance);
            x = setInterval(function () {
                if (distance < 1000) {
                    clearInterval(x);
                    console.log("x terminated");
                    console.log("type : " + type);
                    if (type === 1) {
                        var bold_flag = 0;
                        var over = "Quiz has started";
                        y = setInterval(function () {
                            if (name === "xyz") {
                                var curr_time = calTime();
                                if (curr_time < start_time + quiz_time)
                                {
                                    if (bold_flag === 0) {
                                        document.getElementById("timer_display").innerHTML = over.bold();
                                        bold_flag = 1;
                                    } else {
                                        document.getElementById("timer_display").innerHTML = "--";
                                        bold_flag = 0;
                                    }
                                } else {
                                    clearInterval(y);
                                    finals();
                                }
                            } else {
                                clearInterval(y);
                            }
                        }, 400);
                    } else if (type === 2) {
                        console.log("timer over");
                        quiz();
                    } else if (type === 3) {
                        $("#qus_button").click();
                    } else if (type === 5) {
                        quiz();
                    }
                } else {
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    var countdown = "";
                    if (type !== 3 && type !== 4 && type !== 5) {
                        countdown = "Quiz Starts in : ";
                    }
                    if (type === 3) {
                        countdown = "Time Left : ";
                    }
                    var flag = 0;
                    if (days > 0) {
                        countdown += pad(days) + "Dys ";
                        flag = 1;
                    }
                    if (hours > 0 || flag === 1) {
                        countdown += pad(hours) + "Hrs ";
                        flag = 1;
                    }
                    if (minutes > 0 || flag === 1) {
                        countdown += pad(minutes) + "Min ";
                        flag = 1;
                    }
                    if (seconds > 0 || flag === 1) {
                        countdown += pad(seconds) + " Sec";
                        flag = 1;
                    }
                    if (flag === 0) {
                        countdown += "00 Seconds";
                    }
                    document.getElementById("timer_display").innerHTML = countdown;
                    distance = distance - 1000;
                }
            }, 1000);
        }
        </script>
    </body>
</html>
