<?php
$d = &$this->wholeData;
?>
<html>
<head>
    <title>Test results</title>
    <meta charset='utf-8'>
    <link href='http://fonts.googleapis.com/css?family=Varela+Round&v2' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript">
        var showAll = true;
        function showHide(nodeId, linkObj) {
            var subObj = document.getElementById('stepContainer' + nodeId);

            if (linkObj.innerHTML.indexOf('+') > 0) {
                linkObj.innerHTML = linkObj.innerHTML.replace('+', '-');
                subObj.style.display = 'block';
                subObj.style.width = '100%';
            } else {
                linkObj.innerHTML = linkObj.innerHTML.replace('-', '+');
                subObj.style.display = 'none';
            }
        }

        function showAllScenarios() {
            var toolbar = document.getElementById('toolbar-filter');
            for (var i = 0; i < toolbar.children.length; i++) {
                toolbar.children[i].className = '';
            }

            var trs = document.getElementsByTagName('tr');
            for (var z = 0; z < trs.length; z++) {
                trs[z].style.display = '';
            }
            showAll = true;
        }

        function toggleScenarios(name, linkObj) {
            var links = document.getElementById('toolbar-filter').children;
            var rows = document.getElementsByClassName('scenarioRow');
            if (showAll) {
                for (var i = 0; i < links.length; i++) {
                    links[i].className = 'disabled';
                }

                for (var i = 0; i < rows.length; i++) {
                    rows[i].style.display = 'none';
                }

            }
            showAll = false;

            if (linkObj.className == '') {
                linkObj.className = 'disabled';
                for (var i = 0; i < rows.length; i++) {
                    if (rows[i].classList.contains(name)) {
                        rows[i].style.display = 'none';
                    }
                }
                return;
            }
            if (linkObj.className == 'disabled') {
                linkObj.className = '';
                for (var i = 0; i < rows.length; i++) {
                    if (rows[i].classList.contains(name)) {
                        rows[i].style.display = 'table-row';
                    }
                }
                return;
            }

        }
    </script>
</head>

<body>
<ul id="toolbar-filter">
    <li><a href="#" title="Show all" onClick="showAllScenarios()">◯</a></li>
    <li><a href="#" title="Successful"
           onClick="toggleScenarios('scenarioSuccess', this.parentElement)"><strong>✔</strong> {successfulScenarios}</a>
    </li>
    <li><a href="#" title="Failed" onClick="toggleScenarios('scenarioFailed', this.parentElement)"><strong>✗</strong>
            {failedScenarios}</a></li>
    <li><a href="#" title="Skipped" onClick="toggleScenarios('scenarioSkipped', this.parentElement)"><strong>S</strong>
            {skippedScenarios}</a></li>
    <li><a href="#" title="Incomplete"
           onClick="toggleScenarios('scenarioIncomplete', this.parentElement)"><strong>I</strong> {incompleteScenarios}</a>
    </li>
</ul>
<?php
$id = 0;
?>
<div class="container">
    <div class="page-header"><h1><?= $d['run']['name'] ?></h1></div>
    <?php foreach ($d['suites'] as $suite) : ?>
        <!--Suite start-->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"> <?= $suite['name'] ?> : <span class="badge"><?= count($suite['scenarios']) ?>
                        scenarios</span></h3>
            </div>

            <div class="panel-body">
                <!-- Test start -->
                <?php foreach ($suite['scenarios'] as $scenario) : ?>
                    <?php $uid = md5($scenario['name']);?>
                    <ul class="nav nav-stacked" id="<?=$uid?>">
                        <li class="panel panel-success"><a data-toggle="collapse" data-parent="#<?=$uid?>"
                                   class="pull-left clickable"  href="#Link<?=$uid?>"></a><?=$scenario['name']?>
                            <ul id="Link<?=$uid?>" class="collapse">
                                <!-- Steps start    -->
                                <?php foreach ($scenario['steps'] as $step) : ?>
                                    <li class="list-group-item"><?= $step['html'] ?></li>
                                <?php endforeach; ?>
                                <!-- Steps end      -->
                            </ul>

                        </li>
                    </ul>
                <?php endforeach; ?>
                <!-- Test end-->
            </div>
        </div>
        <!--Suite end-->
    <?php endforeach; ?>


    <div class="layout">
        <pre><?php var_dump($this->wholeData); ?></pre>
    </div>
</div>
</body>
</html>
