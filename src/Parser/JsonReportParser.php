<?php

namespace Codeception\Task\Parser;

use Robo\Exception\TaskException;

class JsonReportParser
{
    const VERSION = 'Codeception-Reporter-Json-zondor-version:1';
    const SECTION_RUN = 'run';
    const SECTION_VERSION = 'version';
    const SECTION_SUITES = 'suites';


    /**
     * @param $fileName
     * @return array with run and suites keys
     * @throws TaskException
     */
    public static function parseFile($fileName)
    {
        $return = [
            self::SECTION_RUN    => null,
            self::SECTION_SUITES => null,
        ];
        try {
            $data = json_decode(file_get_contents($fileName), true, 100, JSON_ERROR_CTRL_CHAR && JSON_BIGINT_AS_STRING);
        } catch (\Exception $e) {
            if (json_last_error() > 0) {
                $message = "JSON_DECODE: ".json_last_error_msg();
            } else {
                $message = $e->getMessage();
            }
            throw  new TaskException(static::class, $message);
        }
        if (empty($data)) {
            return $return;
        };

        if (self::VERSION !== $data[self::SECTION_VERSION] ||
            !isset($data[self::SECTION_RUN])
        ) {
            throw  new TaskException(
                static::class,
                $fileName." : JsonReportParser: json file version not compatible with parser.
                    Use zondor/codeception-json-reporter:~1 to generate right one."
            );
        }

        return [
            self::SECTION_RUN    => $data[self::SECTION_RUN],
            self::SECTION_SUITES => !isset($data[self::SECTION_SUITES]) ? null : $data[self::SECTION_SUITES],
        ];

    }
}
