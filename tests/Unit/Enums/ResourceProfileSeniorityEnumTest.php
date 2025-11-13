<?php

use App\Enums\ResourceProfileSeniorityEnum;

it('has expected values for ResourceProfileSeniorityEnum', function () {
    expect(ResourceProfileSeniorityEnum::INTERN->value)->toBe(1)
        ->and(ResourceProfileSeniorityEnum::JUNIOR->value)->toBe(2)
        ->and(ResourceProfileSeniorityEnum::MID_LEVEL->value)->toBe(3)
        ->and(ResourceProfileSeniorityEnum::SENIOR->value)->toBe(4)
        ->and(ResourceProfileSeniorityEnum::LEAD->value)->toBe(5)
        ->and(ResourceProfileSeniorityEnum::MANAGER->value)->toBe(6)
        ->and(ResourceProfileSeniorityEnum::DIRECTOR->value)->toBe(7)
        ->and(ResourceProfileSeniorityEnum::EXECUTIVE->value)->toBe(8);
});

it('returns correct labels for ResourceProfileSeniorityEnum', function () {
    expect(ResourceProfileSeniorityEnum::INTERN->label())->toBe('Estagiário');
    expect(ResourceProfileSeniorityEnum::JUNIOR->label())->toBe('Júnior');
    expect(ResourceProfileSeniorityEnum::MID_LEVEL->label())->toBe('Pleno');
    expect(ResourceProfileSeniorityEnum::SENIOR->label())->toBe('Senior');
    expect(ResourceProfileSeniorityEnum::LEAD->label())->toBe('Líder');
    expect(ResourceProfileSeniorityEnum::MANAGER->label())->toBe('Gerente');
    expect(ResourceProfileSeniorityEnum::DIRECTOR->label())->toBe('Diretor');
    expect(ResourceProfileSeniorityEnum::EXECUTIVE->label())->toBe('Executivo');
});
