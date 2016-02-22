# HTML/JSON  repor merger for Codeception

Codeception json reports merge for parallel run, can merge json reports into one , can generate html  reports.
Uses https://github.com/zondor/codeception-json-reporter 

## Installation

1. Install [Codeception](http://codeception.com) via Composer
2. Add  `"zondor/codeception-paracept-json-merge": "~1.0"` to your `composer.json`
3. Run `composer install`.
4. Edit your Robo.php file

```
  $sources = [
  'tests/_output/report_1.json',
  'tests/_output/report_2.json',
  'tests/_output/report_3.json',  
  ];
  
  $parallel = $this->taskMergeJsonReports()
      ->from($sources)
      ->into("tests/_output/result_FINAL.html")
      ->setOutputFormat('html')
      ->run();


  $parallel = $this->taskMergeJsonReports()
      ->from($sources)
      ->into("tests/_output/result_FINAL.json")
      ->setOutputFormat('json')
      ->run();      
```            
