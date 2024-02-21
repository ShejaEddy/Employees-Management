<?php

use App\Models\Admin;
use Facade\FlareClient\Http\Exceptions\NotFound;

trait AdminTrait {
    public function getAdminById($id, bool $throw_error = true): Admin {
        $admin = Admin::find($id);

        if (empty($admin) && $throw_error){
            throw new NotFound("Admin not found");
        }

        return $admin;
    }

    public function getAdminByEmail($email, bool $throw_error = true): Admin {
        $admin = Admin::where('email', $email)->first();

        if (empty($admin) && $throw_error){
            throw new NotFound("Admin not found");
        }

        return $admin;
    }

    public function updatePassword($admin, $password): Admin {
        $admin->password = bcrypt($password);
        $admin->save();

        return $admin;
    }
}
