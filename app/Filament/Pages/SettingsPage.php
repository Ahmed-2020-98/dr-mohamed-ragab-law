<?php

namespace App\Filament\Pages;

use App\Support\Settings;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

/**
 * Base for the admin "settings" screens: a form whose fields map 1:1 to settings keys.
 */
abstract class SettingsPage extends Page
{
    protected string $view = 'filament.pages.settings';

    protected static string|UnitEnum|null $navigationGroup = 'الإعدادات';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @return array<\Filament\Schemas\Components\Component> */
    abstract protected function fields(): array;

    public function mount(): void
    {
        $this->form->fill(app(Settings::class)->all());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components($this->fields())->statePath('data');
    }

    public function save(): void
    {
        app(Settings::class)->put($this->form->getState());

        Notification::make()->success()->title('تم حفظ الإعدادات')->send();
    }
}
