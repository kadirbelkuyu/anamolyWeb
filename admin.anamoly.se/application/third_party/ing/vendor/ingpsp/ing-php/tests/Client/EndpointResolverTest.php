<?php
namespace GingerPayments\Payment\Tests\Client;

use GingerPayments\Payment\Client\EndpointResolver;

class EndpointResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldReturnDefaultEndpoints()
    {
        $resolver = new EndpointResolver();
        
        $this->assertEquals(
            $resolver->getEndpointGinger(),
            EndpointResolver::ENDPOINT_GINGER
        );
        
        $this->assertEquals(
            $resolver->getEndpointIng(),
            EndpointResolver::ENDPOINT_ING
        );
        
        $this->assertEquals(
            $resolver->getEndpointKassa(),
            EndpointResolver::ENDPOINT_KASSA
        );
        
        $this->assertEquals(
            $resolver->getEndpointEpay(),
            EndpointResolver::ENDPOINT_EPAY
        );
    }
    
}
