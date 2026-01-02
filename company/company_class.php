<?php
include_once '../ppa/entity_class.php';
include_once '../common/date_functions.php';

class Company extends Entity implements EntityInterface {
	private $name;
	private $contact;
	private $address_line_1;
	private $address_line_2;
	private $phone_number;
	private $mobile_number;
	private $email;
	private $iban;
	private $bic;
	private $legal_persons_register;
	private $vat_number;
	private $invoice_year;
	private $invoice_sequence;
	private $me;
	
	public function getProperties() {
		return get_object_vars($this);
	}
	
	public function setFromPost($post) {
		$this->setName(isset($post['name']) ? htmlspecialchars($post['name']) : '');
		$this->setContact(isset($post['contact']) ? htmlspecialchars($post['contact']) : '');
		$this->setAddressLine1(isset($post['address_line_1']) ? htmlspecialchars($post['address_line_1']) : '');
		$this->setAddressLine2(isset($post['address_line_2']) ? htmlspecialchars($post['address_line_2']) : '');
		$this->setPhoneNumber(isset($post['phone_number']) ? htmlspecialchars($post['phone_number']) : '');
		$this->setMobileNumber(isset($post['mobile_number']) ? htmlspecialchars($post['mobile_number']) : '');
		$this->setEmail(isset($post['email']) ? htmlspecialchars($post['email']) : '');
		$this->setIban(isset($post['iban']) ? htmlspecialchars($post['iban']) : '');
		$this->setBic(isset($post['bic']) ? htmlspecialchars($post['bic']) : '');
		$this->setLegalPersonsRegister(isset($post['legal_persons_register']) ? htmlspecialchars($post['legal_persons_register']) : '');
		$this->setVatNumber(isset($post['vat_number']) ? htmlspecialchars($post['vat_number']) : '');
		$this->setInvoiceYear(isset($post['invoice_year']) ? htmlspecialchars($post['invoice_year']) : '');
		$this->setInvoiceSequence(isset($post['invoice_sequence']) ? htmlspecialchars($post['invoice_sequence']) : '');
	}
	
	public static function findAllNotMe() {
		return Company::findAllByCriteria("me='false'");
	}
			
	/**
	 * Getter and Setters
	 */
	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getContact()
	{
	    return $this->contact;
	}

	public function setContact($contact)
	{
	    $this->contact = $contact;
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

	public function getPhoneNumber()
	{
	    return $this->phone_number;
	}

	public function setPhoneNumber($phone_number)
	{
	    $this->phone_number = $phone_number;
	}

	public function getMobileNumber()
	{
	    return $this->mobile_number;
	}

	public function setMobileNumber($mobile_number)
	{
	    $this->mobile_number = $mobile_number;
	}

	public function getEmail()
	{
	    return $this->email;
	}

	public function setEmail($email)
	{
	    $this->email = $email;
	}

	public function getIban()
	{
	    return $this->iban;
	}

	public function setIban($iban)
	{
	    $this->iban = $iban;
	}

	public function getBic()
	{
	    return $this->bic;
	}

	public function setBic($bic)
	{
	    $this->bic = $bic;
	}

	public function getLegalPersonsRegister()
	{
	    return $this->legal_persons_register;
	}

	public function setLegalPersonsRegister($legal_persons_register)
	{
	    $this->legal_persons_register = $legal_persons_register;
	}

	public function getVatNumber()
	{
	    return $this->vat_number;
	}

	public function setVatNumber($vat_number)
	{
	    $this->vat_number = $vat_number;
	}

	public function getInvoiceYear()
	{
	    return $this->invoice_year;
	}

	public function setInvoiceYear($incoice_year)
	{
	    $this->invoice_year = $incoice_year;
	}

	public function getInvoiceSequence()
	{
	    return $this->invoice_sequence;
	}

	public function setInvoiceSequence($invoice_sequence)
	{
	    $this->invoice_sequence = $invoice_sequence;
	}

	public function getMe()
	{
	    return $this->me;
	}

	public function setMe($me)
	{
	    $this->me = $me;
	}
}