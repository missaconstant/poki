<?php

    /**
    * 
    */
    class Routes
    {

        static function get($action)
        {
            return BASE_URI . '/' . str_replace('.', '/', $action);
        }

        static function getRoutes()
        {
            return [
                "base-route" => "",
                "default" => "/",
                "install" => "/install",
                "configure" => "/configure",
                "makeinstall" => "/makeinstall",
                "configdone" => "/configdone",
                "errorpage" => "/errorpage",
                "login" => "/login",
                "home" => "/home",
                "blank" => "/blank",
                /* users controlleurs */
                "login-act" => "/users/login",
                "logout-act" => "/users/logout",
                /* admin */
                "dashboard" => "/home",
                /* users */
                "users-account" => "/users/account",
                "users-list" => "/users/list",
                "users-create" => "/users/create",
                "users-update" => "/users/update",
                "users-add-act" => "/users/add",
                "users-edit-act" => "/users/edit",
                "users-delete-act" => "/users/delete",
                "users-toggle-active" => "/users/toggle-active",
                /* categories */
                "categories" => "/categories",
                "category-show" => "/categories/show",
                "category-list" => "/categories/list-contents",
                "category-form" => "/categories/form",
                "category-api" => "/categories/apis",
                "category-field-delete" => "/categories/delete-field",
                "categories-create-act" => "/categories/create",
                "categories-delete-act" => "/categories/delete",
                "categories-link-act" => "/categories/link-field",
                "auto-search" => "/categories/search-count-key-in-all-categories",
                /* contents */
                "content-add" => "/contents/add",
                "content-edit" => "/contents/edit",
                "content-delete" => "/contents/delete",
                "content-list" => "/contents/list",
                "content-from-csv" => "/contents/add-from-csv",
                "content-get-csv" => "/contents/get-csv",
                /* files */
                "upload-file" => "/file/upload-file",
                /* apis */
                "api-toggle" => "/api/toggle",
                "api-set" => "/api/set",
                "api-change-key" => "/api/change-key",
                "api-do" => "/api/do",
                "api-watch" => "/api/watch",
                /* plugins */
                "plugins" => "/listener/app"
            ];
        }

        static function find($route)
        {
            $routes = self::getRoutes();
            return isset($routes[$route]) ? str_replace('noset', '', Config::$appfolder) . $routes[$route] : str_replace('noset', '', Config::$appfolder) . $routes['default'];
        }
    }