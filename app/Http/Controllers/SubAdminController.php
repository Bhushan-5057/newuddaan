<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\UserWallet;

use App\Models\CustomField;

use App\Models\AdvanceInfo;

use App\Models\CustomFieldValue;

use Illuminate\Support\Str;

use App\Models\MailTemplate;
use App\Models\PinDetail;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;





class SubAdminController extends Controller
{
    public function index()
    {
        return view('admin.subadmins.index', [
            'subadmins' => User::where('role','subadmin')->get(),
            'permissions' => config('admin_permissions')
        ]);
    }

    public function store(Request $r)
    {
        $user = User::create([
            'username' => $r->username,
            'name' => $r->name,
            'email' => $r->email,
            'password' => bcrypt($r->password),
            'role' => 'subadmin',
            'status' => 1
        ]);

        $this->savePermissions($user->id, $r->permissions);
        return back()->with('success','Sub Admin Added');
    }

    public function edit($id)
    {
        return response()->json([
            'user' => User::findOrFail($id),
            'permissions' => DB::table('user_permissions')->where('user_id',$id)->get()
        ]);
    }

    public function update(Request $r)
    {
        $user = User::findOrFail($r->id);
        $user->update([
            'name' => $r->name,
            'email' => $r->email
        ]);

       DB::table('user_permissions')->where('user_id',$user->id)->delete();
        $this->savePermissions($user->id, $r->permissions);

        return back()->with('success','Sub Admin Updated');
    }

    private function savePermissions($userId, $permissions)
    {
        foreach ($permissions ?? [] as $slug => $acts) {
            DB::table('user_permissions')->insert([
                'user_id' => $userId,
                'permission_slug' => $slug,
                'can_view' => isset($acts['view']),
                // 'can_add' => isset($acts['add']),
                // 'can_edit' => isset($acts['edit']),
                // 'can_delete' => isset($acts['delete']),
                'created_at' => now()
            ]);
        }
    }
}
