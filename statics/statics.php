<?php

    function statics()
    {
        return [
            "errors" => [
                "permissionerror" => "It seems that php couldn't write in the current directory. Please check the persion about adminify install directory.",
                "dbconnecterror" => "It seems that you provided wrong credentials for connexion to database.",
                "tableseterror" => "An error occured while attempting to install app.",
                "usernamefailed" => "You have to set a valid user name and password. User name most have more than 3 characters"
            ],

            "lang" => [
                "en" => [
                    "w1" => "WELCOME",
                    "w2" => "Fill the form to log into your Poki app.",
                    "w3" => "Give your username and password.",
                    "w4" => "Username",
                    "w5" => "Your password",
                    "w6" => "Login",
                    "w7" => "An error occured. Please check your internet connexion."
                ],
                "fr" => [
                    "w1" => "BIENVENUE",
                    "w2" => "Remplir le formulaire pour se connecter à l'application.",
                    "w3" => "Donnez votre nom d'utilisateur et mot de passe.",
                    "w4" => "Nom d'utilisateur",
                    "w5" => "Votre mot de passe",
                    "w6" => "Connexion",
                    "w7" => "Une erreur est survenue ! Verifiez votre connexion et réessayez."
                ]
            ]
        ];
    }

    function getStatics($type=false)
    {
        $statics = statics();
        return $type ? $statics[$type] : $statics;
    }

    function langexp($lang, $exp)
    {
        return getStatics('lang')[$lang][$exp];
    }