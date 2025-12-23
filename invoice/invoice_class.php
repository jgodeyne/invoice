<?php
include_once '../ppa/entity_class.php';
include_once '../common/date_functions.php';

class Invoice extends Entity implements EntityInterface {
	private $date;
	private $number;
	private $client_id;
	private $client_reference;
	private $base_amount;
	private $vat_amount;
	private $total_amount;
	private $due_date;
	private $status;
	private $discount;
	
	public function paid() {
		$this->status = "PAID";
		$this->save();
	}
		
	public function open() {
		$this->status = "OPEN";
		$this->save();
	}

	public function getDiscount()
	{
		return $this->discount;
	}

	public function setDiscount($discount)
	{
		$this->discount = $discount;
	}
	
	public function getProperties() {
		return get_object_vars($this);
	}
	
	public function setFromPost($post) {
	}
	
	/**
	 * Getter and Setter
	 */
	public function getDate()
	{
	    return convertUSToEuroDate($this->date);
	}

	public function setDate($date)
	{
	    $this->date = convertEuroToUSDate($date);
	}
	
	public function getOriginalDate() {
		return $this->date;
	}

	public function getNumber()
	{
	    return $this->number;
	}

	public function setNumber($number)
	{
	    $this->number = $number;
	}

	public function getClientId()
	{
	    return $this->client_id;
	}

	public function setClientId($client_id)
	{
	    $this->client_id = $client_id;
	}

	public function getClientReference()
	{
	    return $this->client_reference;
	}

	public function setClientReference($client_reference)
	{
	    $this->client_reference = $client_reference;
	}

	public function getBaseAmount()
	{
	    return $this->base_amount;
	}

	public function setBaseAmount($base_amount)
	{
	    $this->base_amount = $base_amount;
	}

	public function getVatAmount()
	{
	    return $this->vat_amount;
	}

	public function setVatAmount($vat_amount)
	{
	    $this->vat_amount = $vat_amount;
	}

	public function getTotalAmount()
	{
	    return $this->total_amount;
	}

	public function setTotalAmount($total_amount)
	{
	    $this->total_amount = $total_amount;
	}

	public function getDueDate()
	{
	    return convertUSToEuroDate($this->due_date);
	}

	public function setDueDate($due_date)
	{
	    $this->due_date = convertEuroToUSDate($due_date);
	}
	
	public function getOriginalDueDate() {
		return $this->due_date;
	}

	public function getStatus()
	{
	    return $this->status;
	}
}