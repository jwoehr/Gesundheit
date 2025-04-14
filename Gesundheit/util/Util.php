<?php

/*
 * The MIT License
 *
 * Copyright 2025 jwoehr.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Util is public static utility routines for working and crafting a page.
 *
 * @author jwoehr
 */
class Util {

    /**
     * Set up dotenv
     * @param array|string $dirpath director(y|ies) to search for the dotenv environment file
     * @param string $filename env file name, defaults to ".env"
     * @return 
     */
    public static function loadEnv(array|string $dirpath, string $filename = '.env'): Dotenv\Dotenv {
        $dotenv = Dotenv\Dotenv::createImmutable(paths: $dirpath, names: $filename);
        $dotenv->load();
        return $dotenv;
    }

    /**
     * Test for key existence in dotenv array and if present, return value
     * @param string $key the sought env value
     * @return string|null the value or null if key does not exist
     */
    public static function getDotEnv(string $key): ?string {
        $result = null;
        if (array_key_exists(key: $key, array: $_ENV)) {
            $result = $_ENV[$key];
        }
        return $result;
    }

    public static function stylesheet($url): string {
        return '<link rel="stylesheet" href="' . $url . '">' . PHP_EOL;
    }

    /**
     * Generate html for an h-tag of any level
     * @param int $level 1 for h1, 2 for h2 ...
     * @param string $text the inner HTML
     * @param string $cssclass css class(es), if any
     * @param string $id id if any
     * @return void
     */
    public static function htmlHTag(
            int $level,
            string $text,
            string $cssclass = null,
            string $id = null
    ): string {
        $out = "<h{$level}"
                . ($id ? " id=\"{$id}\"" : "")
                . ($cssclass ? " class=\"{$cssclass}\"" : "")
                . ">"
                . $text
                . "</h{$level}>"
                . PHP_EOL;
        return $out;
    }

    /**
     * Generate html for a row of issue table
     * @param array $data
     * @param string $class
     * @param string $id
     * @return string
     */
    public static function htmlTableRow(array $data, string $class = null, string $id = null): string {
        $out = "<tr";
        $out .= $id ? " id = \"{$id}" : "";
        $out .= $class ? " class=\"{$class}" : "";
        $out .= ">";
        $issue_id = array_shift(array: $data); // issue id is special, it's  a link
        $out .= "<td><a href=\"./issueEdit.php?issue={$issue_id}\">{$issue_id}</a></td>" . PHP_EOL;
        foreach ($data as $datum) { // process the rest of the row
            $out .= "<td>{$datum}</td>" . PHP_EOL;
        }
        $out .= "</tr>" . PHP_EOL;
        return $out;
    }

    /**
     * 
     * @return string
     */
    public static function htmlLogout(): string {
        $out = '<form id="logout_form" action="login.php" method="post" enctype="multipart/form-data" ' . PHP_EOL
                . 'autocomplete="on"><input type="submit" id="submitLogout" name="submitLogout" value="Logout" onclick="Cookies.remove(\'GESUNDHEIT\');">' . PHP_EOL
                . '<input type="hidden" id="Logout" name="Logout" value="Logout">' . PHP_EOL
                . '</form>' . PHP_EOL;

        return $out;
    }

    /**
     * 
     * @return string
     */
    public static function htmlNewIssue(): string {
        $out = '<form id="new_issue_form" action="issueNew.php" method="get" enctype="multipart/form-data" ' . PHP_EOL
                . 'autocomplete="on"><input type="submit" id="submitNewIssue" name="submitNewIssue" value="Submit new issue">' . PHP_EOL
                . '</form>' . PHP_EOL;
        return $out;
    }

    /**
     * 
     * @return string
     */
    public static function htmlIssueView(): string {
        $out = '<form id="view_issues_form" action="index.php" method="get" enctype="multipart/form-data" ' . PHP_EOL
                . 'autocomplete="on"><input type="submit" id="viewIssues" name="viewIssues" value="View issues">' . PHP_EOL
                . '</form>' . PHP_EOL;
        return $out;
    }

    /**
     * 
     * @return string
     */
    public static function htmlIssueResolve(int $issuenumber): string {
        $out = '<form id="view_issues_form" action="./issueEdit.php" method="post" enctype="multipart/form-data" '
                . 'autocomplete="on">' . PHP_EOL
                . '<input type=hidden id="issue" name="issue" value ="' . $issuenumber . '">' . PHP_EOL
                . '<input type=hidden id="resolve" name="resolve" value ="resolve">' . PHP_EOL
                . '<input type="submit" id="resolveIssue" name="resolveIssue" value="Resolve issue">' . PHP_EOL
                . '</form>' . PHP_EOL;
        return $out;
    }

    /**
     * 
     * @return string
     */
    public static function htmlSavePosting(int $issuenumber): string {
        $out = '<form id="view_issues_form" action="./issueEdit.php" method="post" enctype="multipart/form-data" '
                . 'autocomplete="on">' . PHP_EOL
                . '<input type=hidden id="issue" name="issue" value ="' . $issuenumber . '">' . PHP_EOL
                . '<input type=hidden id="save" name="save" value ="save">' . PHP_EOL
                . '<label for="posting">New Posting to Issue</label><br>' . PHP_EOL
                . '<textarea id="posting" name="posting" rows="5" cols="33"></textarea><br>' . PHP_EOL
                . '<input type="submit" id="savePosting" name="savePosting" value="Save Posting">' . PHP_EOL
                . '</form>' . PHP_EOL;
        return $out;
    }
}
