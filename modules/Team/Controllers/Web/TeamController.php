<?php

namespace Modules\Team\Controllers\Web;

use Modules\Team\Models\Team;
use Modules\Team\Repositories\TeamRepo;
use Modules\Team\Requests\TeamRequest;

class TeamController
{
    public function __construct(private TeamRepo $teams) {}

    public function index()
    {
        return view('templates.team.list', ['teams' => $this->teams->all()]);
    }

    public function insert()
    {
        return view('templates.team.insert');
    }

    public function store(TeamRequest $request)
    {
        $this->teams->create($request->validated());

        return redirect()->route('team-list');
    }

    public function edit(Team $team)
    {
        return view('templates.team.edit', compact('team'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $this->teams->update($team, $request->validated());

        return redirect()->route('team-list');
    }

    public function destroy(Team $team)
    {
        $this->teams->delete($team);

        return redirect()->route('team-list');
    }
}
