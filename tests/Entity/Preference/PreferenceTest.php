<?php

namespace MercadoPago\PP\Sdk\Tests\Entity\Preference;

use MercadoPago\PP\Sdk\HttpClient\Response;
use MercadoPago\PP\Sdk\Common\Manager;
use MercadoPago\PP\Sdk\Entity\Preference\Preference;
use MercadoPago\PP\Sdk\Tests\Mock\PreferenceMock;

/**
 * Class PreferenceTest
 *
 * @package MercadoPago\PP\Sdk\Tests\Entity\Preference
 */
class PreferenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Preference
     */
    private $preference;

    /**
     * @var array
     */
    private $preferenceMock;

    /**
     * @var MockObject
     */
    protected $managerMock;

    /**
     * @var MockObject
     */
    protected $responseMock;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->preferenceMock = PreferenceMock::COMPLETE_PREFERENCE;

        $this->managerMock = $this->getMockBuilder(Manager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->preference = new Preference($this->managerMock);
        $this->preference->setEntity($this->preferenceMock);
    }

    function testSubclassesTypes()
    {
        $backUrl = $this->preference->back_urls;
        $items = $this->preference->items;
        $item = $items->getIterator()[0];

        $payer = $this->preference->payer;
        $address = $payer->address;
        $payerIdentification = $payer->identification;
        $phone = $payer->phone;

        $tracks = $this->preference->tracks;
        $track = $tracks->getIterator()[0];

        $paymentMethod = $this->preference->payment_methods;
        $excludedPaymentMethods = $paymentMethod->excluded_payment_methods;
        $excludedPaymentMethod = $excludedPaymentMethods->getIterator()[0];
        $excludedPaymentTypes = $paymentMethod->excluded_payment_types;
        $excludedPaymentType = $excludedPaymentTypes->getIterator()[0];

        $shipment = $this->preference->shipments;
        $freeMethods = $shipment->free_methods;
        $freeMethod = $freeMethods->getIterator()[0];
        $receiverAddress = $shipment->receiver_address;

        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\BackUrl", $backUrl);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\Item", $item);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\ItemList", $items);

        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\Payer", $payer);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\Address", $address);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\PayerIdentification", $payerIdentification);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\Phone", $phone);

        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\PaymentMethod", $paymentMethod);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\ExcludedPaymentMethod", $excludedPaymentMethod);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\ExcludedPaymentMethodList", $excludedPaymentMethods);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\ExcludedPaymentType", $excludedPaymentType);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\ExcludedPaymentTypeList", $excludedPaymentTypes);

        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\Shipment", $shipment);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\FreeMethod", $freeMethod);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\FreeMethodList", $freeMethods);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\ReceiverAddress", $receiverAddress);

        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\Track", $track);
        $this->assertInstanceOf("MercadoPago\PP\Sdk\Entity\Preference\TrackList", $tracks);
    }

    function testGetAndSetSuccess()
    {
        $this->preference->external_reference = 'XXX';

        $actual = $this->preference->__get('external_reference');
        $expected = 'XXX';

        $this->assertEquals($expected, $actual);
    }

    function testGetUriSuccess()
    {
        $actual = $this->preference->getUris();

        $this->assertTrue(is_array($actual));
    }

    function testSaveSuccess()
    {
        $this->responseMock->expects(self::any())->method('getStatus')->willReturn(201);
        $this->responseMock->expects(self::any())->method('getData')->willReturn($this->preferenceMock);

        $this->managerMock->expects(self::any())->method('getEntityUri')->willReturn('/v1/asgard/preferences');
        $this->managerMock->expects(self::any())->method('getHeader')->willReturn([]);
        $this->managerMock->expects(self::any())->method('execute')->willReturn($this->responseMock);
        $this->managerMock->expects(self::any())->method('handleResponse')->willReturn(true);

        $actual = $this->preference->save();

        $this->assertTrue($actual);
    }

    function testJsonSerializeSuccess()
    {
        $actual = $this->preference->jsonSerialize();
        $expected = 'WC-XX';

        $this->assertTrue(is_array($actual));
        $this->assertEquals($expected, $actual['external_reference']);
    }
}
