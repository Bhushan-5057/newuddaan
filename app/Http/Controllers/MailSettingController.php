<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\CustomField;
use App\Models\MailSetting;

class MailSettingController extends Controller
{

public function update(Request $request)
{
    $data = $request->validate([
        'mailer' => 'required|string',
        'host' => 'required|string',
        'port' => 'required|numeric',
        'username' => 'required|string',
        'password' => 'required|string',
        'encryption' => 'nullable|string',
        'from_address' => 'required|email',
        'from_name' => 'required|string',
    ]);
// Encrypt password before saving
$data['password'] = encrypt($request->password);



    MailSetting::updateOrCreate(['id' => 1], $data);

    return back()->with('success', 'Mail settings updated successfully.');
}


}