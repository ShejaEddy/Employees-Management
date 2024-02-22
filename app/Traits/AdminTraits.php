<?php

namespace App\Traits;

use App\Models\Admin;
use Facade\FlareClient\Http\Exceptions\NotFound;

trait AdminTraits {
    public function getAdminById(int $id, bool $throw_error = true): Admin {
        $admin = Admin::find($id);

        if (empty($admin) && $throw_error){
            throw new NotFound("Admin not found", 404);
        }

        return $admin;
    }

    public function getAdminByEmail(string $email, bool $throw_error = true): Admin {
        $admin = Admin::where('email', $email)->first();

        if (empty($admin) && $throw_error){
            throw new NotFound("Admin not found", 404);
        }

        return $admin;
    }

    public function updatePassword(Admin $admin, string $password): Admin {
        $admin->password = bcrypt($password);
        $admin->save();

        return $admin;
    }
}
