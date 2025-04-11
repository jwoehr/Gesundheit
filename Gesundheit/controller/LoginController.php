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

require_once __DIR__ . '/../util/Util.php';
require_once __DIR__ . '/../model/DbModel.php';
require_once __DIR__ . '/../model/UserModel.php';

/**
 * LoginController validates logins
 * 
 * WARNING this is just an example of the control flow
 * of a secure login and OFFERS NO REAL SECURITY!!!
 *
 * @author jwoehr
 */
class LoginController {

    public static function validateLogin(DbModel $dbmodel, string $username, string $password): ?UserModel {
        $userModel = new UserModel();
        $success = $userModel->loadByNameAndPassword(name: $username, password: $password, dbmodel: $dbmodel);
        return $success ? $userModel : null;
    }

    public static function setLoginCookie(UserModel $usermodel): bool {
        $usernum = strval(value: $usermodel->getUsernum());
        return setcookie('GESUNDHEIT', "{$usermodel->getName()}:$usernum", ["path" => "/", "expires" => time() + 1800]);
    }

    public static function validateLoginCookie(DbModel $dbmodel): ?UserModel {
        $usermodel = null;
        // $g = $_COOKIE['GESUNDHEIT'];
        $g = filter_input(type: INPUT_COOKIE, var_name: 'GESUNDHEIT', filter: FILTER_DEFAULT);
        if ($g) {
            $e = explode(separator: ':', string: $g);
            if (sizeof($e) === 2) {
                $name = $e[0];
                $usernum = intval($e[1]);
                $usermodel = UserModel::newUserModelFromLoad(dbmodel: $dbmodel, name: $name);
                $usermodel = ($usermodel->getUsernum() === $usernum) ? $usermodel : null;
            }
        }
        return $usermodel;
    }

    public static function logout(): void {
        setcookie("GESUNDHEIT", "", time() - 3600);
    }
}
