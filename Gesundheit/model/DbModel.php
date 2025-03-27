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

    private ?string $myhost = null;
    private ?string $mongodb_uri = null;
    private ?string $mongodb_db_name = null;
    private ?string $mongodb_cert_path = null;
    private ?string $mongodb_ca_cert_path = null;
    private ?MongoDB\Client $mongodb_client = null;
    private ?MongoDB\Database $mongodb_db = null;

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
    public static function newDbModel(): DbModel {
        return new DbModel(
                host: filter_input(type: INPUT_SERVER, var_name: 'HTTP_HOST', filter: FILTER_SANITIZE_URL) ?: 'localhost',
                mongodb_uri: Util::getDotEnv(key: 'mongodb_uri'),
                mongodb_db_name: Util::getDotEnv(key: 'mongodb_db_name'),
                mongodb_cert_path: Util::getDotEnv(key: 'mongodb_cert_path'),
                mongodb_ca_cert_path: Util::getDotEnv(key: '$mongodb_ca_cert_path'),
        );
    }

    /**
     * Sanitize password out of mongodb uri
     * @param string $uri to be sanitized
     * @return string sanitized uri
     */
    public static function sanitize_uri(string $uri): string {
        $uri_tail = substr(string: $uri, offset: strpos(haystack: $uri, needle: '@'));
        $uri_stub = substr(string: $uri, offset: 0, length: strpos(haystack: $uri, needle: '@'));
        $uri_head = substr(string: $uri_stub, offset: 0, length: strrpos(haystack: $uri_stub, needle: ':') + 1);
        return $uri_head . 'xxxxxxxx' . $uri_tail;
    }

    /**
     * Get sanitized string representation of object
     * @return string sanitized representation of object
     */
    public function __toString(): string {
        $ext = ($this->mongodb_client ? self::get_mongodb_extension_info() : null);
        $result = "<br>myhost:          " . $this->myhost . "\n"
                . "<br>mongodb_uri:     " . $this->sanitize_uri(uri: $this->mongodb_uri) . "\n"
                . "<br>mongodb_db_name: " . $this->mongodb_db_name . "\n"
                . "<br>mongodb_cert_path: " . $this->mongodb_cert_path . "\n"
                . "<br>mongodb_ca_cert_path: " . $this->mongodb_ca_cert_path . "\n"
                . "<br>mongodb_client:  " . ($this->mongodb_client ? $this->sanitize_uri(uri: $this->mongodb_client) : '') . "\n"
                . "<br>mongodb_version: " . ($this->mongodb_client ? $this->get_mongodb_version() : '') . "\n"
                . "<br>monogodb_extension_name: " . ($ext ? $ext["name"] : 'unavailable') . "\n"
                . "<br>monogodb_extension_version: " . ($ext ? $ext["version"] : 'unavailable') . "\n"
                . "<br>monogodb_library_version: " . ($this->mongodb_client ? \Composer\InstalledVersions::getVersion(packageName: 'mongodb/mongodb') : '') . "\n"
                . "<br>mongodb_db:      " . $this->mongodb_db . "\n";
        return $result;
    }

    /**
     * Get mongodb version.
     * Note that Atlas obscures the `admin` collection so `system.version` can't be seen.
     * @return string mongodb version
     */
    public function get_mongodb_version(): string {
        $result = 'Unknown';
        try {
            $this->mongodb_db = $this->mongodb_client->selectDatabase(databaseName: 'admin');
            $version_collection = 'system.version';
            $a = $this->mongodb_db->$version_collection->find(["_id" => 'featureCompatibilityVersion'])->toArray();
            $result = $a[0]['version'];
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        } finally {
            $this->mongodb_db = $this->mongodb_client->selectDatabase(databaseName: $this->mongodb_db_name);
        }
        return $result;
    }

    /**
     * Get version string for mongodb extension
     * @return string the version string
     */
    public static function get_mongodb_extension_info(): array {
        $ext = new ReflectionExtension(name: 'mongodb');
        return ["name" => $ext->getName(), "version" => $ext->getVersion()];
    }

    /**
     * Connect to db
     */
    public function connect(): void {
        if ($this->mongodb_cert_path) {
            /* When we don't need a cert path to CA-signed MongoDB instance. */
            if ($this->mongodb_cert_path == 'USE_SERVER_CERT') {
                $this->mongodb_client = new MongoDB\Client(
                        uri: $this->mongodb_uri,
                        uriOptions: ['tls' => true]
                );
                /* 'tls' => true not necessary for mongodb+srv URIs */
            } else { /* Here's for self-signed certs */
                $this->mongodb_client = new MongoDB\Client(
                        uri: $this->mongodb_uri,
                        uriOptions: [
                    'tls' => true,
                    'tlsCAFile' => $this->mongodb_ca_cert_path,
                    'tlsCertificateKeyFile' => $this->mongodb_cert_path
                        ]
                );
            }
        } else {
            $this->mongodb_client = new MongoDB\Client(uri: $this->mongodb_uri);
        }
        $this->mongodb_db = $this->mongodb_client->selectDatabase(databaseName: $this->mongodb_db_name);
    }

    /**
     * Relinquish current connection to db
     */
    public function close(): void {
        // There's currently no "close" for the MongoDB PHP Driver itself
        $this->mongodb_db = null;
        $this->mongodb_client = null;
    }

    /**
     * Get mongodb db we use
     * @return MongoDB\Database instance of the mongodb db we use
     */
    public function get_mongodb_db(): ?MongoDB\Database {
        return $this->mongodb_db;
    }

    /**
     * Get the server host
     * @return string the server host
     */
    public function get_my_host(): string {
        return $this->myhost;
    }

    /**
     * Get the client object itself
     * @return object the MongoDB\Client object or null
     */
    public function get_client(): ?MongoDB\Client {
        return $this->mongodb_client;
    }

    /**
     * Get current connection to db
     * @return MongoDB\Database object current db or null
     */
    public function get_connection(): ?MongoDB\Database {
        return $this->mongodb_db;
    }

    /**
     * Indicate whether currently connected
     * @return bool true IFF connected (instanced with a MongoDB\Client object)
     */
    public function is_connected(): bool {
        return $this->get_client() != null;
    }

    /**
     * Select and return a Database object for the named database
     * @param string $dbname name of desired db
     * @return object a MongoDB\Database object
     */
    public function get_database(string $dbname): MongoDB\Database {
        return $this->mongodb_client->selectDatabase(databaseName: $dbname);
    }

    public function get_userdoc_by_name(string $name): ?MongoDB\Model\BSONDocument {
        $doc = null;
        $obj = $this->mongodb_db->user->find(["name" => $name]);
        if ($obj) {
            $array = $obj->toArray();
            if (count($array) > 0) {
                $doc = $array[0];
            }
        }
        return $doc;
    }

    public function get_userdoc_by_usernum(int $usernum): ?MongoDB\Model\BSONDocument {
        $doc = null;
        $obj = $this->mongodb_db->user->find(["usernum" => $usernum]);
        if ($obj) {
            $array = $obj->toArray();
            if (count($array) > 0) {
                $doc = $array[0];
            }
        }
        return $doc;
    }

    public function upsert_userdoc(MongoDB\Model\BSONDocument $doc): bool {
        $success = $this->mongodb_db->user->updateOne(
                ['usernum' => $doc['usernum']],
                ['$set' => $doc],
                [
                    'upsert' => true,
                    'writeConcern' => new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY)
                ]
        );
        return $success->getModifiedCount() > 0 || $success->getUpsertedCount() > 0;
    }
    
    public function get_issue_by_issue_number(int $issue_number): ?MongoDB\Model\BSONDocument {
        $doc = null;
        $obj = $this->mongodb_db->issue->find(["issue_number" => $issue_number]);
        if ($obj) {
            $array = $obj->toArray();
            if (count($array) > 0) {
                $doc = $array[0];
            }
        }
        return $doc;
    }

    public function upsert_issue(MongoDB\Model\BSONDocument $doc): bool {
        $success = $this->mongodb_db->user->updateOne(
                ['issue_number' => $doc['issue_number']],
                ['$set' => $doc],
                [
                    'upsert' => true,
                    'writeConcern' => new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY)
                ]
        );
        return $success->getModifiedCount() > 0 || $success->getUpsertedCount() > 0;
    }
}
