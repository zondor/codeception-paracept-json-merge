<?php
//$d = &$this->wholeData;
$d = &$data;
$uid = md5(time());
$p = '
incomplete = default
failed = danger
success = success
skipped
';
?>
<html>
<head><title>Test results</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
    <style type="text/css">
        .bs-example {
            margin: 20px;
        }

        .ok-sign {
            color: #2ca02c;
        }

        .error-sign {
            color: #ac2925;
        }

        ol.step-list {
            list-style: decimal inside;
        }

        ol.step-list .list-group-item {
            display: list-item;

        }

        ol.step-list .list-group-item-danger {
            display: flex;
        }

        ol.step-list .list-group-item strong {
            font-weight: normal;
        }

        .suite-collapse, .scenario-collapse {
            margin-right: 15px;
            cursor: hand;
        }

    </style>
</head>
<body>
<div class="page-header"></div>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-header">
        <h1><?= $d['run']['name'] ?></h1>
    </div>
</nav>



<div class="panel panel-primary">
    <div class="panel-heading">
Stats
    </div>
    <div class="panel-body">
    <ul>
        <li>totalTime : <?=$d['stats']['totalTime']?></li>
        <li>parallelRuns : <?=$d['stats']['parallelRuns']?></li>
        <li>avgParralelRunTime : <?=$d['stats']['avgParralelRunTime']?></li>
        <li>slowestParallelRun : <?=$d['stats']['slowestParallelRun']?></li>
        <li>fastestParallelRun : <?=$d['stats']['fastestParallelRun']?></li>
        <li>slowestTest : <?=$d['stats']['slowestTest']['time']?> : <?=$d['stats']['slowestTest']['name']?></li>
        <li>fastestTest : <?=$d['stats']['fastestTest']['time']?> : <?=$d['stats']['fastestTest']['name']?></li>
        <li>maxstepsInOneTest : <?=$d['stats']['maxstepsInOneTest']['steps']?> : <?=$d['stats']['maxstepsInOneTest']['name']?></li>
    </ul>
    </div>
</div>



<div class="container">
    <?php foreach ($d['suites'] as $suite) : ?>
        <!--Suite start-->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a data-toggle="collapse"
                       class="pull-left clickable glyphicon glyphicon-th-large suite-collapse" href="#Link">
                    </a>

                    <?= $suite['name'] ?> : <span class="badge"><?= count($suite['scenarios']) ?>
                        scenarios</span>
                </h3>
            </div>
            <div class="panel-body">
                <!-- Suite content -->
                <?php foreach ($suite['scenarios'] as $scenario): ?>
                    <?php require('./sub/suite-template.php'); ?>
                <?php endforeach; ?>
                <!-- End Suite content -->
            </div>
        </div>
        <!--Suite end-->
    <?php endforeach; ?>
</div>

<pre><?php
    var_dump($d);
    die;
?></pre>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
    $("document").ready(function () {
        $(".suite-collapse").click(function () {
            $(this).parent().parent().parent().find(".panel-body").toggle();
        });

        $(".scenario-collapse").click(function () {
            $(this).parent().parent().parent().find(".panel-body").toggle();
        });

    });
</script>
</body>
</html>
