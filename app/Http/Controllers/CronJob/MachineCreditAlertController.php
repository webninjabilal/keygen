<?php

namespace App\Http\Controllers\CronJob;

use App\MachineUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;

class MachineCreditAlertController extends Controller
{
    public function index()
    {
        $customerMachines = MachineUser::where('credits', '<=', 200)->get();
        if(count($customerMachines) > 0) {
            foreach ($customerMachines AS $customerMachine) {
                $customer = $customerMachine->customer;
                if($customer and isset($customerMachine->machine->nick_name)) {
                    $to_emails = $customer->user()->pluck('email')->toArray();
                    Mail::send('emails.machine_credit_alert', ['customer_name' => ($customer->name), 'machine_name' => $customerMachine->machine->nick_name], function ($message) use ($to_emails) {
                        $message->to($to_emails)
                            ->subject('Machine Credits Notification');
                    });
                }
            }
        }
    }
}
