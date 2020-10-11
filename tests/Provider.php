<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\CodeAclServiceProvider;
use CodeMaster\CodeAcl\Test\Route;

class Provider extends CodeAclServiceProvider {
    public function __construct()
    {
        parent::__construct(app());
    }

    public function modelBinds()
    {
        return $this->registerModelBindings();
    }

    public function macroHelpers()
    {
        $this->getRouteClass();
        return $this->registerMacroHelpers();
    }

    protected function getRouteClass()
    {
        return Route::class;
    }
}
