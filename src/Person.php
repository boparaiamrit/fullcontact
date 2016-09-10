<?php
/**
 * Created by PhpStorm.
 * User: boparaiamrit
 * Date: 9/10/16
 * Time: 4:25 PM
 */

namespace Boparaiamrit\FullContact;


use Services_FullContact_Person;

class Person extends Services_FullContact_Person
{
	protected $email;
	protected $profile;
	
	function __construct($apiKey)
	{
		parent::__construct($apiKey);
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail()
	{
		return $this->email;
	}
	
	/**
	 * @param mixed $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		$this->resetProfile();
	}
	
	public function fetchProfile()
	{
		$response = $this->lookupByEmail($this->email);
		
		$this->profile = object_to_array($response);
		
		return $this->profile;
	}
	
	private function getAccounts()
	{
		return array_get($this->getProfile(), 'socialProfiles', []);
	}
	
	public function getSocialAccount($social = 'twitter', $accounts = [])
	{
		if (empty($accounts)) {
			$accounts = $this->getAccounts();
		}
		
		$accounts = collect($accounts)->groupBy('type')
									  ->all();
		
		$socialAccount = array_get($accounts, $social, null);
		
		return array_first(array_first($socialAccount, null, []));
	}
	
	public function getProfile()
	{
		if (empty($this->profile)) {
			return $this->fetchProfile();
		}
		
		return $this->profile;
	}
	
	public function resetProfile()
	{
		$this->profile = null;
		
		return true;
	}
}