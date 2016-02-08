<?php
namespace Codeception\Task;

const MERGE_FORMAT_JSON = 'json';
const MERGE_FORMAT_HTML = 'html';

trait MergeJsonReports
{
    protected function taskMergeJsonReports($src = [])
    {
        return new MergeJsonReportsTask($src);
    }
}
