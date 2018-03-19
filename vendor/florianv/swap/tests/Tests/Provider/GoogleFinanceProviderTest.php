<?php

/**
 * This file is part of Swap.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swap\Tests\Provider;

use Swap\Model\CurrencyPair;
use Swap\Provider\GoogleFinanceProvider;

class GoogleFinanceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Swap\Exception\Exception
     */
    public function it_throws_an_exception_when_rate_not_supported()
    {
        $uri = 'http://www.google.com/finance/converter?a=1&from=EUR&to=XXL';
        $content = file_get_contents(__DIR__ . '/../../Fixtures/Provider/GoogleFinance/unsupported.html');

        $body = $this->getMock('Psr\Http\Message\StreamableInterface');
        $body
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue($content));

        $response = $this->getMock('\Ivory\HttpAdapter\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($body));

        $adapter = $this->getMock('Ivory\HttpAdapter\HttpAdapterInterface');

        $adapter
            ->expects($this->once())
            ->method('get')
            ->with($uri)
            ->will($this->returnValue($response));

        $provider = new GoogleFinanceProvider($adapter);
        $provider->fetchRate(new CurrencyPair('EUR', 'XXL'));
    }

    /**
     * @test
     */
    public function it_fetches_a_rate()
    {
        $url = 'http://www.google.com/finance/converter?a=1&from=EUR&to=USD';
        $content = file_get_contents(__DIR__ . '/../../Fixtures/Provider/GoogleFinance/success.html');

        $body = $this->getMock('Psr\Http\Message\StreamableInterface');
        $body
            ->expects($this->once())
            ->method('__toString')
            ->will($this->returnValue($content));

        $response = $this->getMock('\Ivory\HttpAdapter\Message\ResponseInterface');
        $response
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($body));

        $adapter = $this->getMock('Ivory\HttpAdapter\HttpAdapterInterface');

        $adapter
            ->expects($this->once())
            ->method('get')
            ->with($url)
            ->will($this->returnValue($response));

        $provider = new GoogleFinanceProvider($adapter);
        $rate = $provider->fetchRate(new CurrencyPair('EUR', 'USD'));

        $this->assertSame('1.1825', $rate->getValue());
        $this->assertInstanceOf('\DateTime', $rate->getDate());
    }
}
