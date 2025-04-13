#!/usr/bin/env php
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

require_once '../Gesundheit/util/Util.php';
require_once '../Gesundheit/model/DbModel.php';
require_once '../Gesundheit/model/UserModel.php';

function usage(string $progname): string {
    $usage = <<< END
    Usage: $progname envpath op [ name ] [ password ]
        where op is one of:
            * list
            * delete name
            * create name password
            * change name password
        Returns:
            0   success
            101 'create' requires name and password
            102 Error creating new user
            103 'delete' requires name
            104 Error deleting user
            105 'password' requires name and password
            106 Error changing password for user
            107 Unknown op
        
END;
    return $usage . PHP_EOL;
}

/**
 * 
 * @param string $envpath path to environment file
 * @param string $dbname database name
 * @param string $op operation, one of list | create | delete | change
 * @param string|null $name user name (where required)
 * @param string|null $password user password (where required)
 * @return int see usage message for meanings
 */
function usermanage(string $progname, string $envpath, string $op, ?string $name = null, ?string $password = null): int {
    $rc = 0;
    $lastpos = strrpos($envpath, '/');
    $dir = substr($envpath, 0, $lastpos);
    $file = substr($envpath, $lastpos + 1);
    Util::loadEnv($dir, $file);
    $dbmodel = DbModel::newDbModel();
    $dbmodel->connect();
    switch ($op) {
        case 'list':
            $userarray = $dbmodel->get_all_userdocs();
            foreach ($userarray as $doc) {
                $usermodel = UserModel::newUserModelFromDoc($doc);
                print "user number: {$usermodel->getUsernum()} name: {$usermodel->getName()}" . PHP_EOL;
            }
            break;
        case 'create':
            if (!$name || !$password) {
                print usage($progname);
                echo "'create' requires name and password" . PHP_EOL;
                $rc = 101;
                break;
            }
            UserModel::newUserModelNumbered($name, $password, $dbmodel)->save($dbmodel);
            break;
        case 'delete':
            if (!$name) {
                print usage($progname);
                echo "'delete' requires name" . PHP_EOL;
                $rc = 103;
                break;
            }
            $opresult = $dbmodel->delete_user_by_name($name);
            if (!$opresult) {
                echo "Error deleting user $name." . PHP_EOL;
                $rc = 104;
            }
            break;
        case 'change':
            if (!$name || !$password) {
                print usage($progname);
                echo "'password' requires name and password\n";
                $rc = 105;
                break;
            }
            $opresult = UserModel::changePassword($name, $password, $dbmodel);
            if (!$opresult) {
                echo "Error changing password for user $name.\n";
                $rc = 106;
            }
            break;
        default:
            print usage($progname);
            echo "Unknown op $op\n";
            $rc = 107;
    }
    $dbmodel->close();
    return $rc;
}

$rc = 0;

if ((array_key_exists(1, $argv) && (($argv[1] === '-h') || ($argv[1] == "--help")))) {
    print usage($argv[0]);
} elseif ($argc < 2) {
    print "Insufficient arguments" . PHP_EOL;
    print usage($argv[0]);
    $rc = 1;
} else {
    $envpath = $argv[1];
    $op = strtolower($argv[2]);
    $name = ($argc > 3) ? $argv[3] : null;
    $password = ($argc > 4) ? $argv[4] : null;
    $rc = usermanage($argv[0], $envpath, $op, $name, $password);
}

exit($rc);
