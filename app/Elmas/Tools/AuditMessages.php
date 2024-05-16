<?php

namespace App\Elmas\Tools;

use App\Role;
use App\User;
use App\Permission;

class AuditMessages 
{
	
	/**
     * Get readable messages for audits
     *
     * @param object $audits
     * @return object $audits
     */
	static public function get($audits){
		foreach ($audits as $key => $value) {
            $audits[$key]['event_message'] = self::getMessage($value);
        }

        return $audits;
	}

	/**
     * Get specified messages for an audit
     *
     * @param object $audit
     * @return string $message
     */
	static public function getMessage($audit){
		$message = "";
        if(isset($audit->new_values['last_login_at'])){
            $message = __("Logged in.");
        } elseif(isset($audit->new_values['last_logout_at'])){
            $message = __("Logged out.");
        } elseif($audit->event == "created"){
            $message = self::getMessageForCreated($audit);
        } elseif($audit->event == "updated"){
            $message = self::getMessageForUpdated($audit);
        } elseif($audit->event == "deleted"){
            $message = self::getMessageForDeleted($audit);
        }

        return $message;
	}

	/**
	 * Get message for created event
	 *
	 * @param object $audit
	 * @return string $message
	 */
	static private function getMessageForCreated($audit){
		$message = "";

		if($audit->auditable_type == "App\User"){
            $auditale_user = User::find($audit->auditable_id);
            if($auditale_user){
                $message = __("Created a new user named")." ".$auditale_user->name;
            } else {
                $message = __("Created a new user that no longer exists.");
            }
        } elseif($audit->auditable_type == "App\Role"){
            $auditale_role = Role::find($audit->auditable_id);
            if($auditale_role){
                $message = __("Created a new role named")." ".$auditale_role->name;
            } else {
                $message = __("Created a new role that no longer exists.");
            }
        } elseif($audit->auditable_type == "App\Permission"){
            $auditale_permission = Permission::find($audit->auditable_id);
            if($auditale_permission){
                $message = __("Created a new permission named")." ".$auditale_permission->name;
            } else {
                $message = __("Created a new permission that no longer exists.");
            }
        }

	    return $message;
	}

	/**
	 * Get message for updated event
	 *
	 * @param object $audit
	 * @return string $message
	 */
	static private function getMessageForUpdated($audit){
		$message = "";

		if($audit->auditable_type == "App\User"){
			if(isset($audit->old_values['banned'])){
				$auditale_user = User::find($audit->auditable_id);
				if($auditale_user){
		            if($audit->new_values['banned']){
						$message = __("Banned a user account. User :")." ".$auditale_user->name;
					} else {
						$message = __("Activated a user account. User :")." ".$auditale_user->name;
					}
		        } else {
		        	if($audit->new_values['banned']){
						$message = __("Banned a user account that no longer exists.");
					} else {
						$message = __("Activated a user account that no longer exists.");
					}
		        }
			} else {
		        $auditale_user = User::find($audit->auditable_id);
		        if($auditale_user){
		            $message = __("Changed user information for")." ".$auditale_user->name;
		        } else {
		            $message = __("Changed user information for a user that no longer exists.");
		        }
	        }
	    } elseif($audit->auditable_type == "App\Role"){
	        $auditale_role = Role::find($audit->auditable_id);
	        if($auditale_role){
	            $message = __("Changed information for a role named")." ".$auditale_role->name;
	        } else {
	            $message = __("Changed information for a role that no longer exists.");
	        }
	    } elseif($audit->auditable_type == "App\Permission"){
	        $auditale_permission = Permission::find($audit->auditable_id);
	        if($auditale_permission){
	            $message = __("Changed information for a permission named")." ".$auditale_permission->name;
	        } else {
	            $message = __("Changed information for a permission that no longer exists.");
	        }
	    }

	    return $message;
	}

	/**
	 * Get message for deleted event
	 *
	 * @param object $audit
	 * @return string $message
	 */
	static private function getMessageForDeleted($audit){
		$message = "";

		if($audit->auditable_type == "App\User"){
            $message = __("Deleted a user named")." ".$audit->old_values['name'];
        } elseif($audit->auditable_type == "App\Role"){
            $message = __("Deleted a role named")." ".$audit->old_values['name'];
        } elseif($audit->auditable_type == "App\Permission"){
            $message = __("Deleted a permission named")." ".$audit->old_values['name'];
        }

	    return $message;
	}
}