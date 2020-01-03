<?php
include_once("../ppa/entity_class.php");


class Client extends Entity implements EntityInterface {
	
	private $name;
	private $address_line_1;
	private $address_line_2;
	private $contact;
	private $email;
	private $invoice_payment_delay;
	private $language;
	private $mobile_number;
	private $phone_number;
	private $vat_number;
	private $vat_rate;
	private $remark;

	public function getProperties() {
		return get_object_vars($this);
	}
	
	public function setFromPost($post) {
		$this->setName(htmlspecialchars($post['name']));
		$this->setContact(htmlspecialchars($post['contact']));
		$this->setAddressLine1(htmlspecialchars($post['address_line_1']));
		$this->setAddressLine2(htmlspecialchars($post['address_line_2']));
		$this->setPhoneNumber(htmlspecialchars($post['phone_number']));
		$this->setMobileNumber(htmlspecialchars($post['mobile_number']));
		$this->setEmail(htmlspecialchars($post['email']));
		$this->setVatNumber(htmlspecialchars($post['vat_number']));
		$this->setVatRate(htmlspecialchars($post['vat_rate']));
		$this->setInvoicePaymentDelay(htmlspecialchars($post['invoice_payment_delay']));
		$this->setLanguage(htmlspecialchars($post['language']));
		$this->setRemark(htmlspecialchars($post['remark']));
	}
	
	/*
	 * Getters and Setters
	 *  
	 */
	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getAddressLine1()
	{
	    return $this->address_line_1;
	}

	public function setAddressLine1($address_line_1)
	{
	    $this->address_line_1 = $address_line_1;
	}

	public function getAddressLine2()
	{
	    return $this->address_line_2;
	}

	public function setAddressLine2($address_line_2)
	{
	    $this->address_line_2 = $address_line_2;
	}

	public function getContact()
	{
	    return $this->contact;
	}

	public function setContact($contact)
	{
	    $this->contact = $contact;
	}

	public function getEmail()
	{
	    return $this->email;
	}

	public function setEmail($email)
	{
	    $this->email = $email;
	}

	public function getInvoicePaymentDelay()
	{
	    return $this->invoice_payment_delay;
	}

	public function setInvoicePaymentDelay($invoice_payment_delay)
	{
	    $this->invoice_payment_delay = $invoice_payment_delay;
	}

	public function getLanguage()
	{
	    return $this->language;
	}

	public function setLanguage($language)
	{
	    $this->language = $language;
	}

	public function getMobileNumber()
	{
	    return $this->mobile_number;
	}

	public function setMobileNumber($mobile_number)
	{
	    $this->mobile_number = $mobile_number;
	}

	public function getPhoneNumber()
	{
	    return $this->phone_number;
	}

	public function setPhoneNumber($phone_number)
	{
	    $this->phone_number = $phone_number;
	}

	public function getVatNumber()
	{
	    return $this->vat_number;
	}

	public function setVatNumber($vat_number)
	{
	    $this->vat_number = $vat_number;
	}

	public function getVatRate()
	{
	    return $this->vat_rate;
	}

	public function setVatRate($vat_rate)
	{
	    $this->vat_rate = $vat_rate;
	}

	public function getRemark() {
		return $this->remark;
	}

	public function setRemark($remark) {
		$this->remark = $remark;
	}
}
?>