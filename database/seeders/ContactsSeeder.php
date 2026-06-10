<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactsSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->firstOrFail();

        foreach (Party::query()->get() as $party) {
            Contact::query()->updateOrCreate(
                [
                    'parties_Id' => $party->Id,
                    'email' => $party->email,
                ],
                [
                    'team_id' => $party->team_id ?? $owner->currentTeam?->id,
                    'created_by' => $party->created_by ?? $owner->getKey(),
                    'updated_by' => $owner->getKey(),
                    'name' => 'Contacto principal '.str($party->legal_name)->before(' '),
                    'position' => 'Compras',
                    'phone' => $party->phone,
                    'is_primary' => true,
                ],
            );
        }
    }
}
