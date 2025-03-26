/*
 * Copyright 2025 Jack J. Woehr
 * jwoehr@softwoehr.com
 * MIT License open source example code
 * No warranty, no guarantee!
 */

/*
 * node:
 *  require('./create_collections.js');
 */
import { MongoClient } from "mongodb";

const user_options = {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: ["usernum", "name", "password"],
            properties: {
                usernum: {
                    bsonType: "int",
                    minimum: 1,
                    description: "arbitrary number",
                },
                name: {
                    bsonType: "string",
                    description: "user name index unique",
                },
                password: {
                    bsonType: "string",
                    description: "password",
                },
            },
        },
    },
    w: "majority",
};
const issue_options = {
    validator: {
        $jsonSchema: {
            bsonType: "object",
            required: [
                "issue_number",
                "user",
                "description",
                "conversation",
                "resolved",
            ],
            properties: {
                issue_number: {
                    bsonType: "int",
                    minimum: 1,
                    description: "sequence number",
                },
                user: {
                    bsonType: "string",
                    description: "user who reported issue",
                },
                description: {
                    bsonType: "string",
                    description: "description of issue",
                },
                conversation: {
                    bsonType: "array",
                    items: {
                        bsonType: "object",
                        required: ["user", "posting"],
                        additionalProperties: false,
                        properties: {
                            user: {
                                bsonType: "string",
                                description: "username",
                            },
                            posting: {
                                bsonType: "string",
                                description: "the posting",
                            },
                        },
                    },
                    description: "conversation thread around the issue",
                },
                resolved: {
                    bsonType: "bool",
                    description: "true .iff. resolved",
                },
            },
        },
    },
    w: "majority",
};

async function dropIt(db, collname) {
    var cur = await db.listCollections({ name: collname });
    var collExists = await cur.hasNext();
    if (collExists) {
        var coll = await db.collection(collname);
        await coll.dropIndexes();
        await db.dropCollection(collname, {
            w: "majority",
        });
    }
}

async function create_user(uri, dbname = "gesundheit") {
    var client = new MongoClient(uri);
    try {
        await client.connect();
        var mydb = client.db(dbname);
        await dropIt(mydb, "user");
        await mydb.createCollection("user", user_options);
        await mydb.createIndex(
            "user",
            {
                name: 1,
            },
            {
                unique: true,
            }
        );
    } finally {
        await client.close();
        console.log("done create_user()");
    }
}

async function create_issue(uri, dbname = "gesundheit") {
    var client = new MongoClient(uri);
    try {
        await client.connect();
        var mydb = client.db(dbname);
        await dropIt(mydb, "issue");
        await mydb.createCollection("issue", issue_options);
        await mydb.createIndex(
            "issue",
            {
                issue_number: 1,
            },
            {
                unique: true,
            }
        );
    } finally {
        await client.close();
        console.log("done create_issue()");
    }
}

/* usage, e.g.:
 * var x = require('./scripts/create_collections.js');
 *   x.user('mongodb+srv://myuser:mypassword@clusterx-xxxx.mongodb.net/test?tls=true', 'gesundheit')
 *   x.issue('mongodb+srv://myuser:mypassword@clusterx-xxxx.mongodb.net/test?tls=true', 'gesundheit')
 */

export const user = create_user;
export const issue = create_issue;
