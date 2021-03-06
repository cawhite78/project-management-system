<?php

namespace Mage2\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Mage2\Project\Models\Project;
use Mage2\User\Models\AdminUser;

class ProjectUpdate extends Model {

    protected $fillable = ['content', 'admin_user_id', 'project_id'];

    /**
     * Project update belongs to Project
     * 
     * @return \Mage2\Project\Models\Project
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }

    /**
     * Project update belongs to Admin User
     * 
     * @return \Mage2\Project\Models\Project
     */
    public function adminuser() {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }

}
