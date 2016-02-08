<?php
namespace Codeception\Task;

use Codeception\Task\Parser\JsonReportParser;
use Robo\Common\TaskIO;
use Robo\Contract\TaskInterface;
use Robo\Exception\TaskException;

class MergeJsonReportsTask implements TaskInterface
{
    use TaskIO;
    protected $src = [];
    protected $dest;
    protected $templatePath;
    protected $format = MERGE_FORMAT_JSON;
    protected $wholeData = [
        'run'    =>
            [
                "name"                => "Codeception Results",
                "status"              => true,
                "time"                => 0,
                "successfulScenarios" => 0,
                "failedScenarios"     => 0,
                "skippedScenarios"    => 0,
                "incompleteScenarios" => 0,
                "failures"            => [],
            ],
        'suites' => [],
        'stats'  => [
            'totalTime'          => 0,
            'slowestTest'        => ['name' => '', 'time' => 0],
            'fastestTest'        => ['name' => '', 'time' => 0],
            'maxstepsInOneTest'  => ['name' => '', 'steps' => 0],
            'parallelRuns'       => 0,
            'avgParralelRunTime' => 0,
            'slowestParallelRun' => 0,
            'fastestParallelRun' => 0,
        ],

    ];

    public function __construct($src = [])
    {
        $this->src = $src;
        $this->templatePath = __DIR__."/templates";
    }

    public function setOutputFormat($format)
    {
        if ($format == MERGE_FORMAT_JSON || $format == MERGE_FORMAT_HTML) {
            $this->format = $format;

            return $this;
        }
        throw new TaskException(
            $this,
            sprintf("setOutputFormat: format can be or \"%s\" or \"%s\" ", MERGE_FORMAT_JSON, MERGE_FORMAT_HTML)
        );


    }

    public function from(array $fileNames)
    {
        if (is_array($fileNames)) {
            $this->src = array_merge($fileNames, $this->src);

            return $this;
        }

        if (is_string($fileNames)) {
            $this->src[] = $fileNames;

            return $this;
        };

        throw new TaskException($this, "From: fileNames should be ether array or string");
    }

    public function into($fileName)
    {
        if (is_string($fileName)) {
            $this->dest = $fileName;

            return $this;
        };

        throw new TaskException($this, "Into: filename should be string");

    }

    public function run()
    {
        if (!$this->src) {
            throw new TaskException(
                $this,
                "No source files are set. Use `->from( array / string)` method to set input files list"
            );
        }
        if (!$this->dest) {
            throw new TaskException(
                $this,
                "No destination file is set. Use `->into()` method to set result file"
            );
        }
        $this->parseJsonFiles();
    }

    protected function parseJsonFiles()
    {
        foreach ($this->src as $file) {
            $data = JsonReportParser::parseFile($file);
            if ($data['run']) {
                $this->addRun($data['run']);
            }

            if ($data['suites']) {
                $this->addSuites($data['suites']);
            }
        }
        $this->{"generate".ucfirst($this->format)."Report"}();
    }

    protected function addSuites($suites)
    {
        $d = &$this->wholeData['suites'];
        $d = array_merge_recursive($d, $suites);
        $this->addStatsSuites($suites);
    }

    protected function addRun(array $run)
    {
        $d = &$this->wholeData['run'];

        $d["name"] = "Codeception Results";
        $d["status"] = $d['status'] === $run['status'];
        $d["time"] = ($d['time'] >= $run['time']) ? $d['time'] : $run['time'];
        $d["successfulScenarios"] = $d['successfulScenarios'] + $run['successfulScenarios'];
        $d["failedScenarios"] = $d['failedScenarios'] + $run['failedScenarios'];
        $d["skippedScenarios"] = $d['skippedScenarios'] + $run['skippedScenarios'];
        $d["incompleteScenarios"] = $d['incompleteScenarios'] + $run['incompleteScenarios'];
        $d["failures"] = array_merge($this->wholeData['run']['failures'], $run['failures']);

        $this->addRunStats($run);

    }

    protected function addRunStats($run)
    {
        $s = &$this->wholeData['stats'];
        $s['totalTime'] = round(($s['totalTime'] + $run['time']), 3);
        $s['parallelRuns']++;
        $s['avgParralelRunTime'] = round((($s['avgParralelRunTime'] + $run['time'])) / $s['parallelRuns'], 3);
        $s['slowestParallelRun'] = ($run['time'] > $s['slowestParallelRun']) ? $run['time'] : $s['slowestParallelRun'];
        $s['fastestParallelRun'] = ($run['time'] < $s['fastestParallelRun']) ? $run['time'] : $s['fastestParallelRun'];
    }

    protected function addStatsSuites($suites)
    {
        $s = &$this->wholeData['suites'];
        $s = &$this->wholeData['stats'];
        foreach ($suites as $suite) {
            foreach ($suite['scenarios'] as $test) {

                if ($s['slowestTest']['time'] < $test['time']) {
                    $s['slowestTest']['name'] = $test['name'];
                    $s['slowestTest']['time'] = $test['time'];
                }

                if ($s['fastestTest']['time'] == 0 || $s['fastestTest']['time'] > $test['time']) {
                    $s['fastestTest']['name'] = $test['name'];
                    $s['fastestTest']['time'] = $test['time'];
                }

                if ($s['maxstepsInOneTest']['steps'] <= count($test['steps'])) {
                    $s['maxstepsInOneTest']['name'] = $test['name'];
                    $s['maxstepsInOneTest']['steps'] = count($test['steps']);
                }
            }
        }


    }

    protected function generateJsonReport()
    {
        $format = strtoupper($this->format);
        $this->printTaskInfo("Merging JSON reports using {$format} format into: {$this->dest}");
    }

    protected function generateHtmlReport()
    {
        $format = strtoupper($this->format);
        $htmlData = $this->renderHtml();
        file_put_contents($this->dest, $htmlData);
        $this->printTaskInfo("Merging JSON reports using {$format} format into: {$this->dest}");
    }

    protected function renderHtml()
    {
        ob_start();
        include($this->templatePath.'/report-template.php');
        $htmlData = ob_get_contents();
        ob_clean();

        return $htmlData;
    }

    protected function mergeSuites(\DOMElement $resulted, \DOMElement $current)
    {

    }
}
