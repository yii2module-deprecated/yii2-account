<?php

$version = API_VERSION_STRING;

return [
	"GET {$version}/auth" => "account/auth/info",
	"POST {$version}/auth" => "account/auth/login",
    "OPTIONS {$version}/auth" => "account/auth/options",

	"{$version}/registration/<action:(create-account|activate-account|set-password|pseudo)>" => "account/registration/<action>",

	"{$version}/restore-password/<action:(request|check-code|confirm)>" => "account/restore-password/<action>",
    "{$version}/restore-password/resend-code" => "account/restore-password/resend-code",

	"{$version}/security/<action:(password|email)>" => "account/security/<action>",

	["class" => "yii\\rest\UrlRule", "controller" => ["{$version}/user" => "account/user"]],

	
	"{$version}/firebase/register-user" => "account/fire/register-user",
];
