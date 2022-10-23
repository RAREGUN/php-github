<?php

// Constants declaration
define('WORKING_DIR', getcwd() . '\\');
const FILE = 'school.xlsx';
const CONTENT_DIR = 'content\\';
const SHARED_STRINGS_FILE = 'sharedStrings.xml';
const RELATIVE_SHEETS_PATH = 'xl\\worksheets\\';
const RELATIVE_SHARED_STRINGS_PATH = 'xl\\' . SHARED_STRINGS_FILE;
const HOST = 'localhost';
const USER = 'root';
const PASSWORD = 'admin';
const DATABASE = 'school';
const PORT = 3306;

function main(): void // Entry point
{
    printf('Opening \'%s\' file...', FILE);
    // Check if xlsx file exists
    if (!file_exists(FILE)) {
        printf(" [ERROR]\r\n");
        throw new Exception(sprintf('File \'%s\' not found!', FILE));
    }

    // Open xlsx as archive
    $zip = new ZipArchive;
    $res = $zip->open(FILE);
    printf(" [DONE]\r\n");

    printf('Extracting \'%s\' file...', FILE);
    // Extract and check if success
    if ($res === TRUE) {
        $zip->extractTo(CONTENT_DIR);
        $zip->close();
    } else {
        printf(" [ERROR]\r\n");
        throw new Exception(sprintf('Extracting \'%s\' file failed!', FILE));
    }
    printf(" [DONE]\r\n");

    printf('Loading content of \'%s\' file...', SHARED_STRINGS_FILE);
    // Load sharedStrings.xml and move values to array
    $xml = simplexml_load_file(WORKING_DIR . CONTENT_DIR . RELATIVE_SHARED_STRINGS_PATH);
    $sharedStringsArr = array();

    foreach ($xml->children() as $item) {
        $sharedStringsArr[] = (string) $item->t;
    }
    printf(" [DONE]\r\n");


    printf('Data parsing...');
    // Load and iterate contents
    $handle = @opendir(WORKING_DIR . CONTENT_DIR . RELATIVE_SHEETS_PATH);
    $parsingResult = array();

    while ($file = @readdir($handle)) {
        if ($file !== "." && $file !== ".." && $file !== '_rels') {
            $xml = simplexml_load_file(WORKING_DIR . CONTENT_DIR . RELATIVE_SHEETS_PATH . $file);
            $row = 0;

            foreach ($xml->sheetData->row as $item) {
                $parsingResult[$file][$row] = array();
                $cell = 0;

                foreach ($item as $child) {
                    $attr = $child->attributes();

                    if (isset($child->v)) $value = (string) $child->v;
                    else $value = false;

                    if (isset($attr['t'])) $parsingResult[$file][$row][$cell] = (string) $sharedStringsArr[$value];
                    else $parsingResult[$file][$row][$cell] = (string) $child->v;

                    $cell++;
                }

                $row++;
            }
        }
    }
    printf(" [DONE]\r\n");

    printf('Connecting to database...');
    // Connecting to the MySQL server
    $connection = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);

    // Check connection
    if ($connection->connect_error) {
        printf(" [ERROR]\r\n");
        throw new Exception("Unable to connect to database:\r\n%s", mysqli_connect_error());
    }
    printf(" [DONE]\r\n");

    printf("Starting upload to database...\r\n");
    $start = hrtime(true); // Beautify INSERT progress...
    $period = 1000; // Period between parsing output

    $fileIdx = 0;
    foreach ($parsingResult as $file) {
        $rowIdx = 0;

        foreach ($file as $row) {
            $sql = sprintf("INSERT into pupils (login, password, name, phone, address, email) values ('%s', '%s', '%s', '%s', '%s', '%s')",
                $row[0], $row[1], $row[2], getProperPhoneNumber($row[3]), $row[4], getProperMailAddress($row[5]));

            try {
                $result = mysqli_query($connection, $sql);
            } catch (Exception $exc) {
                printf("Caught exception while mysql query.\r\nException: %s\r\nQuery: %s\r\n", $exc, $sql);
                return;
            }

            if (!$result) {
                printf("Query error has occurred: %s\r\n", mysqli_error($connection));
                return;
            }

            $rowIdx++;

            $now = hrtime(true);

            if (($now - $start) / 1e+6 > $period) {
                printf("Uploading progress: FILE(%s/%s) / ROW(%s/%s)\r\n", $fileIdx, count($parsingResult), $rowIdx, count($file));
                $start = $now;
            }
        }

        $fileIdx++;
    }

    printf("Uploading completed, finishing...\r\n");
}

function getProperPhoneNumber(string $str): string
{
    if (strpos($str, '.') !== false) // Decline wrong input which contains point character
        return '';

    if (strlen($str) > 1 && $str[0] === '+') {
        if ($str[1] === '7') {
            $str = substr_replace($str, '8', 0, 2); // Replacing one +7 at start of the string
        } else { // Wrong country code
            return '';
        }
    }

    $str = preg_replace('/[^0-9]+/', '', $str); // Exclude whitespaces and unnecessary characters

    if (strlen($str) < 11) // Too small
        return '';

    if (strlen($str) > 12) // Too big
        return '';

    if (strlen($str) === 11) // Add country code
        $str = '8' . $str;

    $str = substr($str, 0, -1); // Remove last character because I want it.

    return $str;
}

function getProperMailAddress(string $str): string
{
    $matches = [];

    if (preg_match('/\b[\w.-]+@[\w.-]+\.\w{2,4}\b/', $str, $matches)) { // Looking for matches of Regular Expression
        return $matches[0]; // Simply return first match
    }

    return '';
}

try {
    main(); // Entry point invoke
} catch (Exception $exc) {
    printf("Exception: %s\r\n", $exc);
}
