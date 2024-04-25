<?php
namespace sgpbsubscriptionplus;
// define method that MUST be implemented by addon here, if its optional, put it on abstract
interface SGPBProviderInterface {

	/**
	 * Use it to instantiate provider class
	 *
	 * @param string $className 
	 * @return self
	 */
	public static function getInstance();

}
