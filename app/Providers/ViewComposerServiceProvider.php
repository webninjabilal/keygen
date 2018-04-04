<?php

namespace App\Providers;

use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeCompaniesDropdown();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    private function composeCompaniesDropdown()
    {

        view()->composer(
            '_partials.header', function ($view) {
            if (Auth::user()) {
                $user = Auth::user();
                $companies = Company::userLevelCompanies($user->id);
                $dropdown = '<select style="width: 150px;" name="company_main" class="form-control">';
                $pcompany_id = getSelectedCompany();
                if ($companies) {
                    asort($companies);
                    foreach ($companies as $company_id => $company_name) {
                        $dropdown .= '<option value="' . $company_id . '" ' . (($pcompany_id == $company_id) ? 'selected' : '') . '>' . $company_name . '</option>';
                    }
                }
                $dropdown .= '</select>';
                if (count($companies) > 1) {
                    $view->with('companies_dropdown', $dropdown);
                } else {
                    $company = Company::findOrFail(getSelectedCompany());
                    $view->with('companies_dropdown', $company->name);
                }
            } else {
                $company = Company::findOrFail(getSelectedCompany());
                $view->with('companies_dropdown', $company->name);
            }
        }
        );
    }
}
