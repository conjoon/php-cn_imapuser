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

use Conjoon\Mail\Client\Attachment\FileAttachmentItemList,
    Conjoon\Mail\Client\Attachment\FileAttachmentItem,
    Conjoon\Mail\Client\Data\CompoundKey\MessageKey,
    Conjoon\Mail\Client\Data\CompoundKey\AttachmentKey;

class AttachmentControllerTest extends TestCase
{
    use TestTrait;


    /**
     * Tests index() to make sure method returns list of available Attachments
     * for the specified keys.
     *
     * @return void
     */
    public function testIndex_success()
    {
        $service = $this->getMockBuilder('Conjoon\Mail\Client\Service\DefaultAttachmentService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->app->when(App\Http\Controllers\AttachmentController::class)
            ->needs(Conjoon\Mail\Client\Service\AttachmentService::class)
            ->give(function () use ($service) {
                return $service;
            });

        $messageKey = new MessageKey("dev", "INBOX", "123");

        $resultList   = new FileAttachmentItemList;
        $resultList[] = new FileAttachmentItem(
            new AttachmentKey("dev", "INBOX", "123", "1"),
            ["size" => 0,
             "type" => "text/plain",
             "text"  => "file",
            "downloadUrl" => "",
            "previewImgSrc" => ""]
        );

        $service->expects($this->once())
            ->method('getFileAttachmentItemList')
            ->with($messageKey)
            ->willReturn($resultList);


        $response = $this->actingAs($this->getTestUserStub())
            ->call('GET', 'cn_mail/MailAccounts/dev/MailFolders/INBOX/MessageItems/123/Attachments');

        $this->assertEquals(200, $response->status());

        $this->seeJsonEquals([
            "success" => true,
            "data"    => $resultList->toJson()
        ]);

    }

}
