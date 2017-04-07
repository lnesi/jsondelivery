<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
class AdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users',function(Blueprint $table){
            $sql="INSERT INTO users(name,email,password,is_admin) VALUES('System Administrator','admin@system.com','".Hash::make("123456")."',1)";
            DB::connection()->getPdo()->exec($sql);
            $sql="CREATE TRIGGER `bd_users` BEFORE DELETE ON `users` FOR EACH ROW
                    BEGIN
                        IF (OLD.id=1) THEN
                            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'You cannot delete System administrator';
                        END IF;
                    END";
             DB::connection()->getPdo()->exec($sql);    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
