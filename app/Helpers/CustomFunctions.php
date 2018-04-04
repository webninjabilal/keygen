<?php

function getSelectedCompany() {
    $company_id =  \Session::get('company_main');
    if($company_id != '') {
        return $company_id;
    }
    return (isset(\Auth::user()->id)) ? \App\Company::userCurrentCompany(\Auth::user()->id) : 1;
}