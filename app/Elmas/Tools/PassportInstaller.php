<?php

namespace App\Elmas\Tools;

use phpseclib\Crypt\RSA;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class PassportInstaller
{
    protected $rsa;
    protected $clients;

    public function __construct(){
    	$this->rsa = new RSA;
    	$this->clients = new ClientRepository;
    }
    
    /**
     * Install passport encryption keys
     *
     * @return void
     */
    public function installKeys(){
    	if(!$this->keysInstalled()){
    		$this->generateKeys($this->rsa);
    	}
    }

    /**
     * Install passport clients
     *
     * @return void
     */
    public function installClients(){
        if(!$this->clientsExists()){
            $this->createPersonalClient($this->clients);
            $this->createPasswordClient($this->clients);
        }
    }

    /**
     * Generate encryption keys
     *
     * @return void
     */
	protected function generateKeys(RSA $rsa){
    	$keys = $rsa->createKey(4096);

        list($publicKey, $privateKey) = [
            Passport::keyPath('oauth-public.key'),
            Passport::keyPath('oauth-private.key'),
        ];

        file_put_contents($publicKey, Arr::get($keys, 'publickey'));
        file_put_contents($privateKey, Arr::get($keys, 'privatekey'));
    }

    /**
     * Create Personal Client
     *
     * @return void
     */
   	protected function createPersonalClient(ClientRepository $clients)
    {
        $name = setting('app.name')." Personal Access Client";

        $client = $clients->createPersonalAccessClient(
            null, $name, 'http://localhost'
        );
    }

    /**
     * Create Password Client
     * 
     * @return void
     */
    protected function createPasswordClient(ClientRepository $clients)
    {
        $name = setting('app.name').' Password Grant Client';

        $client = $clients->createPasswordGrantClient(
            null, $name, 'http://localhost'
        );
    }

    /**
     * Check if passport keys installed
     *
     * @return boolean
     */
    protected function keysInstalled(){
    	list($publicKey, $privateKey) = [
            Passport::keyPath('oauth-public.key'),
            Passport::keyPath('oauth-private.key'),
        ];

        if ((file_exists($publicKey) || file_exists($privateKey))) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
     * Check if clients created
     * 
     * @return boolean
     */
    protected function clientsExists(){
    	$clients = DB::table('oauth_clients')->count();

    	return $clients === 0 ? false : true;
    }
}