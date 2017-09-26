<?php

/*
 *     __						    _
 *    / /  _____   _____ _ __ _   _| |
 *   / /  / _ \ \ / / _ \ '__| | | | |
 *  / /__|  __/\ V /  __/ |  | |_| | |
 *  \____/\___| \_/ \___|_|   \__, |_|
 *						      |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author LeverylTeam
 * @link https://github.com/LeverylTeam
 *
*/

declare(strict_types = 1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\utils\Utils;

class LoginPacket extends DataPacket {
	const NETWORK_ID = ProtocolInfo::LOGIN_PACKET;

	const MOJANG_PUBKEY = "MHYwEAYHKoZIzj0CAQYFK4EEACIDYgAE8ELkixyLcwlZryUQcu1TvPOmI2B7vX83ndnWRUaXm74wFfa5f/lwQNTfrLVHa2PmenpGI6JhIMUJaWZrjmMj90NoKNFSNBuKdm8rYiXsfaz3K36x/1U26HpG0ZxK/V1V";

	const EDITION_POCKET = 0;

	public $username;
	public $protocol;
	public $clientUUID;
	public $clientId;
	public $identityPublicKey;
	public $serverAddress;

	public $skinId;
	public $skin = "";

	public $chainData;
	public $clientData;
	public $clientDataJwt;
	public $decoded;

	public $deviceos;
	public $devicemodel;
	public $uiprofile;
	public $guiscale;
	public $controls;
	public $TenantID;
	public $defaultInputMode;
	public $AdRole;
	public $LanguageCode;

	public function canBeSentBeforeLogin(): bool{
		return true;
	}

	public function decode(){
		$this->protocol = $this->getInt();

		if(!in_array($this->protocol, ProtocolInfo::ACCEPTED_PROTOCOLS)){
			$this->buffer = null;

			return; //Do not attempt to decode for non-accepted protocols
		}

		$this->setBuffer($this->getString(), 0);

		$this->chainData = json_decode($this->get($this->getLInt()));
		$chainKey = self::MOJANG_PUBKEY;
		foreach($this->chainData->{"chain"} as $chain){
			list($verified, $webtoken) = Utils::decodeJWT($chain, $chainKey);
			if(isset($webtoken["extraData"])){
				if(isset($webtoken["extraData"]["displayName"])){
					$this->username = $webtoken["extraData"]["displayName"];
				}
				if(isset($webtoken["extraData"]["identity"])){
					$this->clientUUID = $webtoken["extraData"]["identity"];
				}
			}
			if($verified and isset($webtoken["identityPublicKey"])){
				if($webtoken["identityPublicKey"] != self::MOJANG_PUBKEY){
					$this->identityPublicKey = $webtoken["identityPublicKey"];
				}
			}
		}

		$this->clientDataJwt = $this->get($this->getLInt());
		$this->decoded = Utils::decodeJWT($this->clientDataJwt, $this->identityPublicKey);
		$this->clientData = $this->decoded[1];

		$this->clientId = $this->clientData["ClientRandomId"] ?? null;
		$this->serverAddress = $this->clientData["ServerAddress"] ?? null;
		$this->skinId = $this->clientData["SkinId"] ?? null;

		if(isset($this->clientData["SkinData"])){
			$this->skin = base64_decode($this->clientData["SkinData"]);
		}
		if(isset($this->clientData["DeviceOS"])){
			$this->deviceos = $this->clientData["DeviceOS"];
		}
		if(isset($this->clientData["DeviceModel"])){
			$this->devicemodel = $this->clientData["DeviceModel"];
		}
		if(isset($this->clientData["UIProfile"])){
			$this->uiprofile = $this->clientData["UIProfile"];
		}
		if(isset($this->clientData["GuiScale"])){
			$this->guiscale = $this->clientData["GuiScale"];
		}
		if(isset($this->clientData["CurrentInputMode"])){
			$this->controls = $this->clientData["CurrentInputMode"];
		}
		if(isset($this->clientData["TenantId"])){
			$this->TenantID = $this->clientData["TenantId"];
		}
		if(isset($this->clientData["DefaultInputMode"])){
			$this->defaultInputMode = $this->clientData["DefaultInputMode"];
		}
		if(isset($this->clientData["AdRole"])){
			$this->AdRole = $this->clientData["AdRole"];
		}
		if(isset($this->clientData["LanguageCode"])){
			$this->LanguageCode = $this->clientData["LanguageCode"];
		}
	}

	public function encode(){
		//TODO
	}
}
