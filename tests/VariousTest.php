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



class VariousTest extends TestCase
{
    /**
     * Get information and validate registered middleware for the app.
     *
     * @return void
     */
    public function testMiddleware() {
        $reflection = new \ReflectionClass($this->app);
        $property = $reflection->getMethod('gatherMiddlewareClassNames');
        $property->setAccessible(true);
        $ret = $property->invokeArgs($this->app, ['auth']);

        $this->assertSame($ret[0], "App\Http\Middleware\Authenticate");
    }


    public function testRoutes() {

        $routes = $this->app->router->getRoutes();

        $this->assertArrayHasKey("POST/cn_imapuser/auth", $routes);

        $this->assertArrayHasKey("GET/cn_mail/MailAccounts", $routes);
        $this->assertSame("auth", $routes["GET/cn_mail/MailAccounts"]["action"]["middleware"][0]);

        $this->assertArrayHasKey("GET/cn_mail/MailAccounts/{mailAccountId}/MailFolders", $routes);
        $this->assertSame("auth", $routes["GET/cn_mail/MailAccounts"]["action"]["middleware"][0]);
    }


    public function testConcretes() {


        $reflection = new \ReflectionClass($this->app);
        $property = $reflection->getMethod('getConcrete');
        $property->setAccessible(true);


        $this->assertInstanceOf(
            \App\Imap\DefaultImapUserRepository::class,
            $this->app->build($property->invokeArgs($this->app, ['App\Imap\ImapUserRepository']))
        );

        $this->assertInstanceOf(
            \App\Imap\Service\DefaultMailFolderService::class,
            $this->app->build($property->invokeArgs($this->app, ['App\Imap\Service\MailFolderService']))
        );

    }
}