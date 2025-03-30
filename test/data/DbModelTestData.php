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

/**
 * Data for DbModelTest
 *
 * @author jwoehr
 */
class DbModelTestData {

    const EXAMPLE_LOOKUP_1 = 'Array
(
    [0] => MongoDB\Model\BSONDocument Object
        (
            [storage:ArrayObject:private] => Array
                (
                    [issue_number] => 1
                    [conversation] => MongoDB\Model\BSONArray Object
                        (
                            [storage:ArrayObject:private] => Array
                                (
                                    [0] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [usernum] => 2
                                                    [posting] => I had an issue
                                                )

                                        )

                                    [1] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [usernum] => 1
                                                    [posting] => I fixed the issue
                                                )

                                        )

                                )

                        )

                    [description] => This is a test issue
                    [resolved] => 1
                    [usernum] => 2
                    [user] => MongoDB\Model\BSONArray Object
                        (
                            [storage:ArrayObject:private] => Array
                                (
                                    [0] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [name] => sumi
                                                )

                                        )

                                )

                        )

                )

        )

)
';
    const EXAMPLE_LOOKUP_ALL = 'Array
(
    [0] => MongoDB\Model\BSONDocument Object
        (
            [storage:ArrayObject:private] => Array
                (
                    [issue_number] => 1
                    [conversation] => MongoDB\Model\BSONArray Object
                        (
                            [storage:ArrayObject:private] => Array
                                (
                                    [0] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [usernum] => 2
                                                    [posting] => I had an issue
                                                )

                                        )

                                    [1] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [usernum] => 1
                                                    [posting] => I fixed the issue
                                                )

                                        )

                                )

                        )

                    [description] => This is a test issue
                    [resolved] => 1
                    [usernum] => 2
                    [user] => MongoDB\Model\BSONArray Object
                        (
                            [storage:ArrayObject:private] => Array
                                (
                                    [0] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [name] => sumi
                                                )

                                        )

                                )

                        )

                )

        )

    [1] => MongoDB\Model\BSONDocument Object
        (
            [storage:ArrayObject:private] => Array
                (
                    [issue_number] => 2
                    [conversation] => MongoDB\Model\BSONArray Object
                        (
                            [storage:ArrayObject:private] => Array
                                (
                                    [0] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [usernum] => 1
                                                    [posting] => I had a new issue
                                                )

                                        )

                                    [1] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [usernum] => 1
                                                    [posting] => I have not fixed the new issue
                                                )

                                        )

                                )

                        )

                    [description] => This is another test issue
                    [resolved] => 
                    [usernum] => 1
                    [user] => MongoDB\Model\BSONArray Object
                        (
                            [storage:ArrayObject:private] => Array
                                (
                                    [0] => MongoDB\Model\BSONDocument Object
                                        (
                                            [storage:ArrayObject:private] => Array
                                                (
                                                    [name] => fred
                                                )

                                        )

                                )

                        )

                )

        )

)
';
}
