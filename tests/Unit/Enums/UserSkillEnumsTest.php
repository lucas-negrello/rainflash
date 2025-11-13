<?php

use App\Enums\UserSkillProficiencyLevelEnum;

it('has expected values for UserSkillProficiencyLevelEnum', function () {
    expect(UserSkillProficiencyLevelEnum::VERY_LOW->value)->toBe(1)
        ->and(UserSkillProficiencyLevelEnum::LOW->value)->toBe(2)
        ->and(UserSkillProficiencyLevelEnum::MEDIUM->value)->toBe(3)
        ->and(UserSkillProficiencyLevelEnum::HIGH->value)->toBe(4)
        ->and(UserSkillProficiencyLevelEnum::VERY_HIGH->value)->toBe(5);
});

it('returns correct labels for UserSkillProficiencyLevelEnum', function () {
    expect(UserSkillProficiencyLevelEnum::VERY_LOW->label())->toBe('Muito Baixa');
    expect(UserSkillProficiencyLevelEnum::LOW->label())->toBe('Baixa');
    expect(UserSkillProficiencyLevelEnum::MEDIUM->label())->toBe('Média');
    expect(UserSkillProficiencyLevelEnum::HIGH->label())->toBe('Alta');
    expect(UserSkillProficiencyLevelEnum::VERY_HIGH->label())->toBe('Muito Alta');
});
