<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../invoice/invoice_class.php';

class InvoiceTest extends TestCase
{
    public function testDiscountGetterSetter()
    {
        $inv = new Invoice();
        $inv->setDiscount(12.34);
        $this->assertEquals(12.34, $inv->getDiscount());
    }
}
