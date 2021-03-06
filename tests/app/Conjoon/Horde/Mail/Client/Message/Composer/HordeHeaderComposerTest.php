<?php
/**
 * conjoon
 * php-cn_imapuser
 * Copyright (C) 2019 Thorsten Suckow-Homberg https://github.com/conjoon/php-cn_imapuser
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

use Conjoon\Horde\Mail\Client\Message\Composer\HordeHeaderComposer,
    Conjoon\Mail\Client\Message\Composer\HeaderComposer,
    Conjoon\Mail\Client\Data\CompoundKey\MessageKey,
    Conjoon\Mail\Client\Message\MessageItemDraft,
    Conjoon\Mail\Client\Data\MailAddress,
    Conjoon\Mail\Client\Data\MailAddressList;


/**
 * Class HordeHeaderComposerTest
 *
 */
class HordeHeaderComposerTest extends TestCase {


    public function testClass() {

        $strategy = new HordeHeaderComposer();
        $this->assertInstanceOf(HeaderComposer::class, $strategy);
    }


    public function testWrite_1() {

        $composer = new HordeHeaderComposer();

        $messageItemDraft = new MessageItemDraft(new MessageKey("a", "b", "c"));
        $messageItemDraft->setSubject("Test");
        $msgText = ["Subject: foobar"];
        $result  = [
            "Subject: Test",
            "Date: " . (new \DateTime())->format("r"),
            "User-Agent: php-conjoon",
            "",
            ""
        ];
        $msgText = implode("\n", $msgText);
        $this->assertEqualsCanonicalizing($result, explode("\n", $composer->compose($msgText, $messageItemDraft)));
    }

    public function testWrite_2() {

        $composer = new HordeHeaderComposer();


        $messageItemDraft = new MessageItemDraft(new MessageKey("a", "b", "c"));
        $messageItemDraft->setSubject("Test");
        $messageItemDraft->setTo($this->createAddress("to"));
        $messageItemDraft->setCc($this->createAddress("cc"));
        $messageItemDraft->setBcc($this->createAddress("bcc"));
        $messageItemDraft->setReplyTo($this->createAddress("replyTo"));
        $messageItemDraft->setFrom($this->createAddress("from"));
        $messageItemDraft->setDate(new \DateTime());

        $msgText = ["Subject: foobar"];
        $result  = [
            "Subject: Test",
            "From: " . $this->createAddress("from")->toString(),
            "Reply-To: " . $this->createAddress("replyTo")->toString(),
            "To: " . $this->createAddress("to")->toString(),
            "Cc: " . $this->createAddress("cc")->toString(),
            "Bcc: " . $this->createAddress("bcc")->toString(),
            "Date: " . (new \DateTime())->format("r"),
            "User-Agent: php-conjoon",
            "",
            ""
        ];
        $msgText = implode("\n", $msgText);
        $this->assertEqualsCanonicalizing($result, explode("\n", $composer->compose($msgText, $messageItemDraft)));
    }


    /**
     * Makes sure header fields are not removed if target fields are not
     * explicitely defined.
     */
    public function testFieldsAreNotRemoved() {

        $composer = new HordeHeaderComposer();

        $messageItemDraft = new MessageItemDraft(new MessageKey("a", "b", "c"));

        $msgText = [
            "Subject: foobar",
            "From: a",
            "Reply-To: b",
            "To: c",
            "Cc: d",
            "Bcc: e",
        ];
        $result  = [
            "Subject: foobar",
            "From: a",
            "Reply-To: b",
            "To: c",
            "Cc: d",
            "Bcc: e",
            "Date: " . (new \DateTime())->format("r"),
            "User-Agent: php-conjoon",
            "",
            ""
        ];
        $msgText = implode("\n", $msgText);
        $result  = $result;
        $this->assertEqualsCanonicalizing($result, explode("\n", $composer->compose($msgText, $messageItemDraft)));

    }

    /**
     * Makes sure everything works as expected if header address fields are null
     */
    public function testNullAddress() {

        $composer = new HordeHeaderComposer();

        $messageItemDraft = new MessageItemDraft(new MessageKey("a", "b", "c"));


        $messageItemDraft->setFrom(null);
        $messageItemDraft->setReplyTo(null);
        $messageItemDraft->setCc(null);
        $messageItemDraft->setBcc(null);
        $messageItemDraft->setTo(null);

        $result  = [
            "Date: " . (new \DateTime())->format("r"),
            "User-Agent: php-conjoon",
            "",
            ""
        ];
        $this->assertEqualsCanonicalizing($result, explode("\n", $composer->compose("", $messageItemDraft)));
    }

    public function testStringable() {

        $composer = new HordeHeaderComposer();


        $messageItemDraft = new MessageItemDraft(new MessageKey("a", "b", "c"));
        $messageItemDraft->setSeen(true);

        $result  = [
            "Date: " . (new \DateTime())->format("r"),
            "User-Agent: php-conjoon",
            "",
            ""
        ];

        $this->assertEqualsCanonicalizing($result, explode("\n", $composer->compose("", $messageItemDraft)));
    }

// +-----------------------------------
// | Helper
// +-----------------------------------

    /**
     * @param $type
     *
     * @return mixed
     */
    protected function createAddress($type) {

        $mailAddresses = [
            new MailAddress($type . "1@" . strtolower($type) . ".com", $type . "1"),
            new MailAddress($type . "2@" . strtolower($type) . ".com", $type . "2"),
            new MailAddress($type . "3@" . strtolower($type) . ".com", $type . "3")
        ];

        switch ($type) {

            case "to":
            case "cc":
            case "bcc":

                $list = new MailAddressList();
                foreach ($mailAddresses as $add) {
                    $list[] = $add;
                }
                return $list;

            case "from":
            case "replyTo":
                return $mailAddresses[0];

        }


    }


}