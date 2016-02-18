<div class="panel panel-default" style="border: 0px solid #fff">
    <div class="panel-heading">
        <h3 class="panel-title">
            <span class="pull-left clickable scenario-collapse">
                <i class="glyphicon clickable glyphicon-th-list"></i>
            </span>
            <?= " ".$scenario['name']." "; ?>
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
                    <p>
                        <span class="glyphicon glyphicon-remove-circle"></span>
                        <?= $scenario['failure']; ?></p>

                </li>
            <?php endif; ?>
            <!--End Failed info-->
        </ol>
    </div>
</div>

