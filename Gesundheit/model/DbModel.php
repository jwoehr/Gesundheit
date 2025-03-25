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
require_once(__DIR__ . '/../util/Util.php');
/**
 * DBModel is the database model, application-specific building blocks 
 * implemented in MongoDB.
 *
 * @author jwoehr
 */
class DbModel {

    /**
     * Ctor
     * @param string $host the http host
     * @param string $mongodb_uri mongodb uri
     * @param string $mongodb_db_name name of db
     * @param string $mongodb_cert_path path to server cert pem cert+key .. if present, we're doing TLS.
     *               If the value is USE_SERVER_CERT the server has a genuine CA certificate.
     * @param string $mongodb_ca_cert_path path to server CA cert .. only checked if $mongodb_cert_path is a cert
     * @return object new HWDb instance
     */
    public function __construct(
            string $host,
            string $mongodb_uri,
            string $mongodb_db_name,
            string $mongodb_cert_path = null,
            string $mongodb_ca_cert_path = null
    ) {
        $this->myhost = $host;
        $this->mongodb_uri = $mongodb_uri;
        $this->mongodb_db_name = $mongodb_db_name;
        $this->mongodb_cert_path = $mongodb_cert_path;
        $this->mongodb_ca_cert_path = $mongodb_ca_cert_path;
    }

    /**
     * Factory New with defaults from dotenv environment
     * @return \DbModel
     */
    public static function new_DbModel(): DbModel {
        return new DbModel(
                filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL) ?: 'localhost',
                Util::getDotEnv('mongodb_uri'),
                Util::getDotEnv('mongodb_db_name'),
                Util::getDotEnv('mongodb_cert_path'),
                Util::getDotEnv('$mongodb_ca_cert_path'),
        );
    }
}
