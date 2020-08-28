<?php
interface EntityInterface {
	public function getProperties();
	public function setFromPost($post);
}