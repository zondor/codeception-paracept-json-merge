<?php
$d = &$this->wholeData;

function getColor($status)
{
    switch ($status) {
        case 'failed':
            return 'danger';
        case 'success':
            return 'success';
        case 'skipped':
            return 'warning';
        case "incomplete":
        default:
            return 'default';
    }
}
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
    <div class="container-fluid pull-left">
        <h2><?= $d['run']['name'] ?></h2>
    </div>
    <div class="container-fluid pull-right" style="line-height: 67px;">
        <div class="navbar-header">
            <button class="btn filter-btn btn-success" data-type="success" type="button">
                Success <span class="badge"><?= $d['run']['successfulScenarios'] ?></span>
            </button>
            <button class="btn filter-btn btn-danger" data-type="danger" type="button">
                Failed <span class="badge"><?= $d['run']['failedScenarios'] ?></span>
            </button>
            <button class="btn filter-btn btn-warning" data-type="warning" type="button">
                Skipped <span class="badge"><?= $d['run']['skippedScenarios'] ?></span>
            </button>
            <button class="btn filter-btn btn-default" data-type="default" type="button">
                Incomplete <span class="badge"><?= $d['run']['incompleteScenarios'] ?></span>
            </button>
        </div>
</nav>
<div class="container">
    <?php foreach ($d['suites'] as $suite) : ?>
        <!--Suite start-->
        <div class="panel suites panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a data-toggle="collapse"
                       class="pull-left clickable glyphicon glyphicon-th-large suite-collapse" href="#Link">
                    </a>
                    <?php
                    //TODO quickFix , need to move it into Class JsonReportParser
                    $suiteName = (is_array($suite['name']))? current($suite['name']) : $suite['name'];
                    ?>
                    <?= $suiteName ?> : <span class="badge"><?= count($suite['scenarios']) ?>
                        scenarios</span>
                </h3>
            </div>
            <div class="panel-body">
                <!-- Suite content -->
                <?php foreach ($suite['scenarios'] as $scenario): ?>
                    <div class="panel scenarios panel-<?= getColor($scenario['scenarioStatus']) ?>"
                         style="border: 0px solid #fff">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="pull-left clickable scenario-collapse">
                                    <i class="glyphicon clickable glyphicon-th-list"></i>
                                </span>
                                <?= " " . $scenario['name'] . " "; ?>
                                <span class="pull-right">
                                    <span class="badge"> time : <?= $scenario['time'] ?>s </span>
                                </span>
                            </h3>
                        </div>
                        <div class="panel-body" style="padding: 0px;display: none;">
                            <ol class="list-group step-list">
                                <?php foreach ($scenario['steps'] as $k => $step) : ?>
                                    <li class="list-group-item">
                                        <p>
                                            <?php if ($step['status'] == 'success') : ?>
                                                <span class="glyphicon ok-sign glyphicon-ok-circle"></span>
                                            <?php else: ?>
                                                <span class="glyphicon error-sign glyphicon-remove-circle"></span>
                                            <?php endif; ?>
                                            <?= $step['html'] ?>

                                        </p>
                                    </li>
                                <?php endforeach; ?>

                                <!--Failed info-->
                                <?php if ($step['status'] !== 'success') : ?>
                                    <li class="list-group-item list-group-item-danger">
                                        <p><span
                                                class="glyphicon glyphicon-remove-circle"></span><?= $scenario['failure']; ?>
                                        </p>
                                    </li>
                                <?php endif; ?>
                                <!--End Failed info-->
                            </ol>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- End Suite content -->
            </div>
        </div>
        <!--Suite end-->
    <?php endforeach; ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            Stats
        </div>
        <div class="panel-body">
            <ul>
                <li>totalTime : <?= $d['stats']['totalTime'] ?></li>
                <li>parallelRuns : <?= $d['stats']['parallelRuns'] ?></li>
                <li>avgParralelRunTime : <?= $d['stats']['avgParralelRunTime'] ?></li>
                <li>slowestParallelRun : <?= $d['stats']['slowestParallelRun'] ?></li>
                <li>fastestParallelRun : <?= $d['stats']['fastestParallelRun'] ?></li>
                <li>slowestTest : <?= $d['stats']['slowestTest']['time'] ?>
                    : <?= $d['stats']['slowestTest']['name'] ?></li>
                <li>fastestTest : <?= $d['stats']['fastestTest']['time'] ?>
                    : <?= $d['stats']['fastestTest']['name'] ?></li>
                <li>maxstepsInOneTest : <?= $d['stats']['maxstepsInOneTest']['steps'] ?>
                    : <?= $d['stats']['maxstepsInOneTest']['name'] ?></li>
            </ul>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
    $("document").ready(function () {
        $(".filter-btn").click(function () {
            $(".filter-btn").not(this).removeClass("active");
            var filter = $(this).attr("data-type");

            if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $('.suites .panel').show();
            } else {
                $(this).addClass("active")
                $('.suites .panel').hide();
                $('.suites .panel-' + filter).show();
            }
        });

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
