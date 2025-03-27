/*
 * Copyright 2025 Jack J. Woehr
 * jwoehr@softwoehr.com
 * MIT License open source example code
 * No warranty, no guarantee!
 */

/*
 * Create the Gesundheit collections.
 * node usage:
 * const { user, issue } = await import("./create_collections.js");
 *   user('mongodb+srv://myuser:mypassword@clusterx-xxxx.mongodb.net/?tls=true', 'gesundheit')
 *   issue('mongodb+srv://myuser:mypassword@clusterx-xxxx.mongodb.net/?tls=true', 'gesundheit')
 */

export const user = create_user;
export const issue = create_issue;

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
                "usernum",
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
                usernum: {
                    bsonType: "int",
                    description: "usernum of user who reported issue",
                },
                description: {
                    bsonType: "string",
                    description: "description of issue",
                },
                conversation: {
                    bsonType: "array",
                    items: {
                        bsonType: "object",
                        required: ["usernum", "posting"],
                        additionalProperties: false,
                        properties: {
                            usernum: {
                                bsonType: "int",
                                description: "usernum of user who posted",
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
