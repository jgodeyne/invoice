<?php
use PHPUnit\Framework\TestCase;

class InvoiceExistsTest extends TestCase
{
    public function testInvoiceClassExists()
    {
        $this->assertTrue(class_exists('Invoice'));
    }
}
