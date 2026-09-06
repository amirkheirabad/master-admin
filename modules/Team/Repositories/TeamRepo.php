<?php

namespace Modules\Team\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Team\Models\Team;

class TeamRepo
{
    public function all(): Collection
    {
        return Team::orderBy('name')->get();
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function update(Team $team, array $data): Team
    {
        $team->update($data);

        return $team;
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }
}
