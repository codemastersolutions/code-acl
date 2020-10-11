<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\CodeAclRegister;

class Register extends CodeAclRegister {
    public function getCacheFromConfig()
    {
        $this->getCacheDriver();
        return $this->getCacheStoreFromConfig();
    }

    protected function getCacheDriver()
    {
        return null;
    }
}
