<?php

use App\Models\{Company, CompanyUser, Permission, ResourceProfile, Role, Skill, User, UserSkill};

it('can create model instances via factories', function () {
    $user = User::factory()->create();
    $company = Company::factory()->create();
    $skill = Skill::factory()->create();

    $resourceProfile = ResourceProfile::factory()->for($user)->create();
    $this->assertEquals($user->id, $resourceProfile->user_id);

    $companyUser = CompanyUser::factory()->for($company)->for($user)->create();
    $this->assertEquals($company->id, $companyUser->company_id);
    $this->assertEquals($user->id, $companyUser->user_id);

    $permission = Permission::factory()->create();
    $role = Role::factory()->create();

    // Attach relations (pivot tables) to ensure existence
    $role->permissions()->attach($permission->id);
    $companyUser->roles()->attach($role->id);

    $userSkill = UserSkill::factory()->for($user)->for($skill, 'skill')->create();
    $this->assertEquals($user->id, $userSkill->user_id);
    $this->assertEquals($skill->id, $userSkill->skill_id);

    // Assert all models were persisted
    foreach ([$user,$company,$skill,$resourceProfile,$companyUser,$permission,$role,$userSkill] as $model) {
        expect($model->exists)->toBeTrue();
    }
});
